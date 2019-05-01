<?php

namespace App\Http\Controllers;

use App\Bankaccount;
use App\Bankcode;
use App\Bubankaccess;
use App\Businessunit;
use App\Businessunitpis;
use App\Cashcategory;
use App\Cashcategoryaccess;
use App\Cashlog;
use App\Cashlogbook;
use App\Cashpullout;
use App\Check;
use App\Check2;
use App\Currency;
use App\Denomination;
use App\Denominationcs;
use App\Department;
use App\Departmentccm;
use App\Departmentpis;
use App\Depositslip;
use App\Employeepis;
use App\Rules\SalesDate;
use App\Sectionpis;
use App\Smbreakdown;
use App\Subsectionpis;
use App\Totaldetails;
use App\User;
use App\Useraccess;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TreasuryController extends Controller
{
    /**
     * ------------------------------------------------------------------------------------
     *  LEGEND
     *  1. __construct
     *  2. logbookedit
     *  3. addunittolist
     *  4. addunits
     *  5. cashdesc
     *  6. getunits
     *  7. add_manual_cash_adj
     * -------------------------------------------------------------------------------------
     */
    /**
     * TreasuryController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function logbookCashierList(Request $request) {

        $employees = $this->getDenomination($request);

        $args = [];
        foreach ($employees as $e) {
            $employee = Employeepis::where('emp_id', $e->emp_id)->first();
            $args[] = [
                $employee->emp_id,
                $employee->name,
                ($e->den_total)?number_format($e->den_total,2):number_format($e->total_denomination, 2)
//                '<button href="'.route('tr.logbook.cashier.details', ['emp_id'=>$e->emp_id,'code'=>$request->code,'date'=>$request->date]).'" class="btn btn-primary btn-sm view-cashier-detail" data-toggle="modal" data-target=".cashier-details-modal">View</button>'
            ];
        }

        $total = ($employees[0]->den_total)?$employees->sum('den_total'):$employees->sum('total_denomination');

        return [
            'details' => $args,
            'total' => number_format($total,2)
        ];
    }

    private function getDenomination($request, $action='lists') {
        $login_user = Auth::user();
        $values = explode('|',$request->code);
        list($company_code, $bunit_code, $dept_code, $section_code, $sub_section_code) = $values;

        if (strtolower($login_user->businessunit->bname) == 'plaza marcela') {
            $employees = Denominationcs::where('company_code', (int)$company_code)
                ->where('bunit_code', (int)$bunit_code)
                ->where('delete_status', '!=', 'deleted')
                ->where('date_shrt', $request->date);

            if ((int)$dept_code>0) {
                $employees->where('dept_code', (int)$dept_code);
            }

            if ((int)$section_code>0) {
                if ((int)$section_code == 1) {
                    $employees->whereIn('section_code',[2,3,5,6,7,8,9]);
                } else {
                    $employees->where('section_code', (int)$section_code);
                }
            }

            if ((int)$sub_section_code>0) {
                $employees->where('sub_sec_code', (int)$sub_section_code);
            }

        } else {
            $employees = Denomination::where('com_code', (int)$company_code)
                ->where('bu_code', (int)$bunit_code)
                ->where('date_shrt', $request->date);

            if ((int)$dept_code>0) {
                $employees->where('dept_code', (int)$dept_code);
            }

            if ((int)$section_code>0) {
                if ((int)$section_code == 1) {
                    $employees->whereIn('sec_code',[2,3,5,6,7,8,9]);
                } else {
                    $employees->where('sec_code', (int)$section_code);
                }
            }

            if ((int)$sub_section_code>0) {
                $employees->where('sub_sec_code', (int)$sub_section_code);
            }

        }

        if($action == 'details') {
            return $employees->where('emp_id', $request->emp_id)
                ->get();
        }

        return $employees->get();
    }

    public function logbookedit(Request $request) {
        $login_user = Auth::user();
        $logbook_items = Cashlogbook::where('bank_id', $request->bank)
            ->where('deposit_date', $request->date)
            ->where('bu_unit', $login_user->bunitid)
            ->get();
        $bank = Bankaccount::where('id',$request->bank)
            ->where('buid', $login_user->bunitid)
            ->first();
        $deposit_date = Carbon::parse($request->date);
        return view('treasury.logbook.edit', compact('logbook_items', 'deposit_date', 'bank'));
    }

    public function addunittolist(Request $request) {
        return $request;
        $view = view('treasury.logbook.add_unit_to_list')->render();

        return $view;
    }

    public function addunits(Request $request) {
        $login_user = Auth::user();
        $cpo = Cashpullout::where('pull_out_date', $request->sales_date)
            ->where('bu_id', $login_user->bunitid)
            ->sum('amount');

        $cashdesc = $this->cashdesc($request, $login_user, $cpo);
        $cashCon = $cashdesc['cashCon'];
        $view = view('treasury.logbook.addbu', compact('cashCon'))->render();
        return [
            'view' => $view
        ];
    }

    /**
     * @param $request (id,sales_date)
     * @param $login_user
     */
    private function cashdesc($request, $login_user, $cpo) {

        $user_access = Useraccess::where('bank_id',$request->id)
            ->get();
        $checks = $request->pdcTotal + $request->dueChecks;
        $test = [];
        $liq_less_checks_total = 0;
        $liq_amount = 0;
        $liq_amount_net = 0;
        $cashCon = [];

        foreach ($user_access as $key => $u) {
            $company_code = $u->company_code;
            $bunit_code = $u->bunit_code;
            $dept_code = $u->dept_code;
            $section_code = $u->section_code;
            $sub_section_code = $u->sub_section_code;
            $codes = $company_code.'|'.$bunit_code.'|'.$dept_code.'|'.$section_code.'|'.$sub_section_code;
            if ($company_code!=0 && $bunit_code!=0 && $dept_code!=0 && $section_code!=0) {


                if (strtolower($login_user->businessunit->bname) === 'island city mall') {

                    $detail = Sectionpis::where('company_code', (int)$company_code)
                        ->where('bunit_code', (int)$bunit_code)
                        ->where('dept_code', (int)$dept_code)
                        ->where('section_code', (int)$section_code)
                        ->first();
                    $detail_dept = Departmentpis::where('company_code', (int)$company_code)
                        ->where('bunit_code', (int)$bunit_code)
                        ->where('dept_code', (int)$dept_code)
                        ->first();
                    if ($detail->section_name) {
                        if (strtolower($detail->section_name) === 'selling area' && strtolower($detail_dept->dept_name) === 'supermarket') {

                            if (strtolower($login_user->businessunit->bname) === 'plaza marcela') {
                                $cash = Denominationcs::select(DB::raw('ROUND(SUM(total_denomination),2) as amount'))
                                    ->where('delete_status', '!=', 'deleted')
                                    ->where('company_code', (int)$company_code)
                                    ->where('bunit_code', (int)$bunit_code)
                                    ->where('dept_code', (int)$dept_code)
                                    ->whereIn('section_code', [(int)$section_code,2,3,5,6,7,8,9])
                                    ->where('date_shrt', $request->sales_date)
                                    ->first();
                            } elseif (strtolower($login_user->businessunit->bname) === 'island city mall') {
                                $cash = Denomination::select(DB::raw('ROUND(SUM(den_total),2) as amount'))
                                    ->where('com_code', (int)$company_code)
                                    ->where('bu_code', (int)$bunit_code)
                                    ->where('dept_code', (int)$dept_code)
                                    ->whereIn('sec_code', [(int)$section_code,2,3,5,6,7,8,9])
                                    ->where('date_shrt', $request->sales_date)
                                    ->first();
                            } else {
                                $cash = Denomination::select(DB::raw('ROUND(SUM(den_total),2) as amount'))
                                    ->where('com_code', (int)$company_code)
                                    ->where('bu_code', (int)$bunit_code)
                                    ->where('dept_code', (int)$dept_code)
                                    ->whereIn('sec_code', [(int)$section_code,2,3,5,6,7,8,9])
                                    ->where('date_shrt', $request->sales_date)
                                    ->first();
                            }

                            $detaildesc = 'SUPERMARKET';
                        } else {

                            if (strtolower($detail->section_name) === 'fmd- island city mall') {
                                $detaildesc = 'MARCELA FARMS';
                            } else {
                                $detaildesc = $detail->section_name;
                            }

                            if (strtolower($login_user->businessunit->bname) === 'plaza marcela') {
                                $cash = Denominationcs::select(DB::raw('ROUND(SUM(total_denomination),2) as amount'))
                                    ->where('delete_status', '!=', 'deleted')
                                    ->where('company_code', (int)$company_code)
                                    ->where('bunit_code', (int)$bunit_code)
                                    ->where('dept_code', (int)$dept_code)
                                    ->where('section_code', (int)$section_code)
                                    ->where('date_shrt', $request->sales_date)
                                    ->first();
                            } else {
                                $cash = Denomination::select(DB::raw('ROUND(SUM(den_total),2) as amount'))
                                    ->where('com_code', (int)$company_code)
                                    ->where('bu_code', (int)$bunit_code)
                                    ->where('dept_code', (int)$dept_code)
                                    ->where('sec_code', (int)$section_code)
                                    ->where('date_shrt', $request->sales_date)
                                    ->first();
                            }

                        }
                    }
                } elseif (strtolower($login_user->businessunit->bname) === 'plaza marcela') {

                    $detail = Sectionpis::where('company_code', (int)$company_code)
                        ->where('bunit_code', (int)$bunit_code)
                        ->where('dept_code', (int)$dept_code)
                        ->where('section_code', (int)$section_code)
                        ->first();

                    if ($detail->section_name) {
                        if (strtolower($detail->section_name) === 'check-out counter') {

                            if (strtolower($login_user->businessunit->bname) === 'plaza marcela') {
                                $cash = Denominationcs::select(DB::raw('ROUND(SUM(total_denomination),2) as amount'))
                                    ->where('delete_status', '!=', 'deleted')
                                    ->where('company_code', (int)$company_code)
                                    ->where('bunit_code', (int)$bunit_code)
                                    ->where('dept_code', (int)$dept_code)
                                    ->whereIn('section_code', [(int)$section_code,2,3,5,6])
                                    ->where('date_shrt', $request->sales_date)
                                    ->first();
                            } else {
                                $cash = Denomination::select(DB::raw('ROUND(SUM(den_total),2) as amount'))
                                    ->where('com_code', (int)$company_code)
                                    ->where('bu_code', (int)$bunit_code)
                                    ->where('dept_code', (int)$dept_code)
                                    ->whereIn('sec_code', [(int)$section_code,2,3,5,6])
                                    ->where('date_shrt', $request->sales_date)
                                    ->first();
                            }

                            $detaildesc = 'SUPERMARKET';
                        } else {
                            $detaildesc = $detail->section_name;
                            if (strtolower($login_user->businessunit->bname) === 'plaza marcela') {
                                $cash = Denominationcs::select(DB::raw('ROUND(SUM(total_denomination),2) as amount'))
                                    ->where('delete_status', '!=', 'deleted')
                                    ->where('company_code', (int)$company_code)
                                    ->where('bunit_code', (int)$bunit_code)
                                    ->where('dept_code', (int)$dept_code)
                                    ->where('section_code', (int)$section_code)
                                    ->where('date_shrt', $request->sales_date)
                                    ->first();
                            } else {
                                $cash = Denomination::select(DB::raw('ROUND(SUM(den_total),2) as amount'))
                                    ->where('com_code', (int)$company_code)
                                    ->where('bu_code', (int)$bunit_code)
                                    ->where('dept_code', (int)$dept_code)
                                    ->where('sec_code', (int)$section_code)
                                    ->where('date_shrt', $request->sales_date)
                                    ->first();
                            }

                        }
                    }

                } else {

                    $detail = Sectionpis::where('company_code', (int)$company_code)
                        ->where('bunit_code', (int)$bunit_code)
                        ->where('dept_code', (int)$dept_code)
                        ->where('section_code', (int)$section_code)
                        ->first();
                    if (strtolower($login_user->businessunit->bname) === 'plaza marcela') {
                        $cash = Denominationcs::select(DB::raw('ROUND(SUM(total_denomination),2) as amount'))
                            ->where('delete_status', '!=', 'deleted')
                            ->where('company_code', (int)$company_code)
                            ->where('bunit_code', (int)$bunit_code)
                            ->where('dept_code', (int)$dept_code)
                            ->where('section_code', (int)$section_code)
                            ->where('date_shrt', $request->sales_date)
                            ->first();
                    } else {
                        $cash = Denomination::select(DB::raw('ROUND(SUM(den_total),2) as amount'))
                            ->where('com_code', (int)$company_code)
                            ->where('bu_code', (int)$bunit_code)
                            ->where('dept_code', (int)$dept_code)
                            ->where('sec_code', (int)$section_code)
                            ->where('date_shrt', $request->sales_date)
                            ->first();
                    }

                    $detaildesc = $detail->section_name;

                }

                $test[$key] = [
                    'amount' => $cash->amount,
                    'name' => $detaildesc,
                    'hrmscode' => $codes
                ];
            } elseif ($company_code!=0 && $bunit_code!=0 && $dept_code!=0) {
                if (strtolower($login_user->businessunit->bname) === 'plaza marcela') {
                    $cash = Denominationcs::select(DB::raw('ROUND(SUM(total_denomination),2) as amount'))
                        ->where('delete_status', '!=', 'deleted')
                        ->where('company_code', (int)$company_code)
                        ->where('bunit_code', (int)$bunit_code)
                        ->where('dept_code', (int)$dept_code)
                        ->where('date_shrt', $request->sales_date)
                        ->first();
                } else {
                    $cash = Denomination::select(DB::raw('ROUND(SUM(den_total),2) as amount'))
                        ->where('com_code', $company_code)
                        ->where('bu_code', $bunit_code)
                        ->where('dept_code', $dept_code)
                        ->where('date_shrt', $request->sales_date)
                        ->first();
                }

                $detail = Departmentpis::where('company_code', (int)$company_code)
                    ->where('bunit_code', (int)$bunit_code)
                    ->where('dept_code', (int)$dept_code)
                    ->first();

                if ($detail->dept_name) {
                    if (trim(strtolower($detail->dept_name)) === 'bohol exchange') {
                        $detaildesc = 'CHEQUE EXCHANGE';
                    } else {
                        $detaildesc = $detail->dept_name;
                    }
                }

                $test[$key] = [
                    'amount' => $cash->amount,
                    'name' => $detaildesc,
                    'hrmscode' => $codes
                ];
            } elseif ($company_code!=0 && $bunit_code!=0) {
                if (strtolower($login_user->businessunit->bname) === 'plaza marcela') {
                    $cash = Denominationcs::select(DB::raw('ROUND(SUM(total_denomination),2) as amount'))
                        ->where('delete_status', '!=', 'deleted')
                        ->where('company_code', (int)$company_code)
                        ->where('bunit_code', (int)$bunit_code)
                        ->where('date_shrt', $request->sales_date)
                        ->first();
                } else {
                    $cash = Denomination::select(DB::raw('ROUND(SUM(den_total),2) as amount'), 'emp_dept')
                        ->where('com_code', $company_code)
                        ->where('bu_code', $bunit_code)
                        ->where('date_shrt', $request->sales_date)
                        ->first();
                }

                $detail = Businessunitpis::where('company_code', (int)$company_code)
                    ->where('bunit_code', (int)$bunit_code)
                    ->first();
                $test[$key] = [
                    'amount' => $cash->amount,
                    'name' => $detail->business_unit,
                    'hrmscode' => $codes
                ];
            }

            if (!is_null($test[$key]['amount'])) {

                $liq_amount = $test[$key]['amount'];

                $liq_amount_net = $liq_amount;

                if (strtolower($test[$key]['name']) === 'supermarket') {
                    $liq_less_checks_total = $liq_amount - $checks;

                    $liq_amount_net = $liq_amount - $cpo;
                    $liq_amount_net = $liq_amount_net - $checks;

                }

                $cashCon[] = [
                    'id' => $key,
                    'cash_id' => $key,
                    'bu' => $test[$key]['name'],
                    'liq_amount' => $liq_amount,
                    'less_amount' => $liq_amount_net,
                    'input_status' => 'liq',
                    'hrmscode' => $test[$key]['hrmscode']
                ];
            } else {
                $cashCon[] = [
                    'id' => $key,
                    'cash_id' => $key,
                    'bu' => $test[$key]['name'],
                    'liq_amount' => 0,
                    'less_amount' => 0,
                    'input_status' => 'tre',
                    'hrmscode' => $test[$key]['hrmscode']
                ];
            }
        }

        return [
            'cashCon' => $cashCon,
            'liq_less_checks_total' => $liq_less_checks_total
        ];
    }

    private function cash_bu($request, $cpo) {
        $user_access = Useraccess::with('cash_bu')->where('bank_id',$request->id)
            ->get();

        $checks = (float)$request->pdcTotal + (float)$request->dueChecks;

        $totalcash = [];

        foreach ($user_access as $u) {

            $total = Denominationcs::select(DB::raw('SUM(total_denomination) as amount'))
                ->where('date_shrt', $request->sales_date)
                ->where('delete_status', '!=', 'deleted')
                ->whereIn('company_code', [(int)$u->company_code])
                ->whereIn('bunit_code', [(int)$u->bunit_code])
                ->whereIn('dept_code', [(int)$u->dept_code])
                ->whereIn('section_code', [(int)$u->section_code])
                ->whereIn('sub_sec_code', [(int)$u->sub_section_code])
                ->first();
            if (strtolower($request->user()->businessunit->bname) === 'island city mall') {
                $total = Denomination::select(DB::raw('SUM(den_total) as amount'))
                    ->where('date_shrt', $request->sales_date)
                    ->where('com_code', [(int)$u->company_code])
                    ->where('bu_code', [(int)$u->bunit_code])
                    ->where('dept_code', [(int)$u->dept_code])
                    ->whereIn('sec_code', [(int)$u->section_code])
                    ->whereIn('sub_sec_code', [(int)$u->sub_section_code])
                    ->first();
            }
            $totalcash[] = [
                'total' => ($total->amount)?$total->amount:0,
                'cash_bu' => ($u->cash_bu)?$u->cash_bu->description:'',
                'code' => [
                    $u->company_code,
                    $u->bunit_code,
                    $u->dept_code,
                    $u->section_code,
                    $u->sub_section_code,
                ],
                'cash_bu_id' => $u->cash_bu_id
            ];
        }

        $unique_bu = collect($totalcash)->unique('cash_bu');

        $new_total = [];
        $less_check_sum = 0;

        foreach ($unique_bu as $key => $bu) {
            $group_collection = collect($totalcash)->where('cash_bu', $bu['cash_bu']);
            $codes = $group_collection->pluck('code');
            $total = $group_collection->pluck('total');
            $sum_group = $group_collection->sum('total');

            $less_sum = $sum_group;

            if (strtolower($bu['cash_bu']) === 'supermarket') {
                $less_check_sum = $sum_group - $checks;

                $less_sum = $sum_group - $cpo;
                $less_sum = $less_sum - $checks;
            }

            $new_total[] = [
                'bu' => $bu['cash_bu'],
                'cash_id' => $bu['cash_bu_id'],
                'hrmscode' => $codes,
                'total' => $total,
                'id' => $key,
                'input_status' => ($sum_group > 0)?'liq':'tre',
                'less_amount' => $less_sum,
                'liq_amount' => $sum_group,
            ];
        }

        return [
            'cashCon' => $new_total,
            'liq_less_checks_total' => $less_check_sum,
        ];
    }

    public function getunits(Request $request) {

        $login_user = Auth::user();
        $login_user_bu = $login_user->bunitid;
        $sales_date = Carbon::parse($request->sales_date);

        $cpo = Cashpullout::where('pull_out_date', $request->sales_date)
            ->where('bu_id', $login_user->bunitid)
            ->sum('amount');

        $pdcTotal = $request->pdcTotal;
        $dueChecks = $request->dueChecks;

//        return $this->cash_bu($request, $cpo);

        $cashdesc = $this->cash_bu($request, $cpo);



//        $cashdesc = $this->cashdesc($request, $login_user, $cpo);

//        return $cashdesc;

        $cashCon = $cashdesc['cashCon'];

        $liq_less_checks_total = $cashdesc['liq_less_checks_total'];

        //supermarket marcela calculation
        if (strtolower($login_user->businessunit->bname) == 'plaza marcela') {
            $sm_details = [];
            foreach ($cashCon as $key => $c) {
                if (strtolower($c['bu']) == 'supermarket') {
                    $sm_details = [
                        'key' => $key,
                        'amount' => $c['liq_amount']
                    ];
                }
            }
//            $dueChecks = Check::where('check_received', $request->sales_date)
//                ->where('check_status', 'CLEARED')
//                ->where('businessunit_id', $login_user_bu)
//                ->whereIn('department_from', [15,13])
//                ->sum('check_amount');

            $due_pdc_total = $dueChecks+$pdcTotal;

            if (!empty($sm_details)) {
                $totalcash_sm = $sm_details['amount'];
                $liq_less_checks_total = $totalcash_sm - $due_pdc_total;
                $cashCon[$sm_details['key']]['less_amount'] = $liq_less_checks_total;
            }

        }

        $cashtotal = collect($cashCon)->sum('less_amount');

        //ids for js validation
        $buCount = count($cashCon);

        //cash categories
        $cash_categories = Cashcategoryaccess::with(['cash_category'=>function ($query) {
            $query->select('id', 'description');
        }])
            ->where('bank_id', $request->id)
            ->orderBy('id', 'DESC')
            ->get(['id', 'bank_id', 'cash_category_id', 'deleted_at']);

        $cashConCount = count($cashCon);

        $view = view('treasury.logbook.selected_bu', compact('sales_date', 'pdcTotal', 'dueChecks', 'cpo', 'liq_less_checks_total', 'login_user_bu', 'cashCon', 'login_user'))->render();

        $view2 = view('treasury.logbook.select_category', compact('cash_categories', 'sales_date', 'login_user', 'buCount', 'cashConCount'))->render();

        $view = $view.$view2;

        return response()->json([
            'view' => $view,
            'cashtotal' => number_format($cashtotal, 2),
            'cashden' => $cashCon,
//            'total_no_sm' => $total_no_sm

        ]);

    }

    public function add_manual_cash_adj(Request $request) {

        $data = $request->validate([
            'sales_date' => 'required|date',
            'bu_sb' => 'required|not_in:0',
            'ds_number' => 'required',
            'amount' => 'required',
            'status_adj' => 'required'
        ]);

        $cashdetails = explode('+',$request->bu_sb);

        $code = $cashdetails[0];
        $hrmscode = $cashdetails[1];
        $desc = $cashdetails[2];

//        $cashlog = Cashlog::find($request->bu_sb);

        $date = Carbon::parse($request->sales_date);

        $amount = preg_replace('/\,/','', $request->amount);

        $view = view('treasury.logbook.cash_manual_table', compact('request', 'cashlog', 'date', 'amount', 'code', 'hrmscode', 'desc'))->render();

        return response()->json([
            'view' => $view,
            'id' => $code
        ]);
    }

    public function add_cash_pull_out(Request $request) {

        $data = $request->validate([
            'amount' => 'required|not_in:0',
            'department' => 'required|not_in:0'
        ]);

        $login_user = Auth::user();

        $amount = preg_replace('/\,/','', $request->amount);

        $createcpo = Cashpullout::updateOrCreate([
            'pull_out_date' => $request->sales_date,
            'department' => $request->department,
        ],
        [
            'department' => $request->department,
            'amount' => $amount,
            'purpose' => $request->purpose,
            'bu_id' => $login_user->bunitid,
            'company_id' => $login_user->company_id,
            'approved_by' => 0,
            'date_approved' => $request->sales_date,
            'release_by' => $login_user->user_id,
            'release_date' => $request->sales_date,
            'pull_out_date' => $request->sales_date,
            'created_by' => $login_user->user_id,
            'updated_by' => $login_user->user_id,
            'deleted_by' => $login_user->user_id,
        ]);

        $cpoTotal = Cashpullout::where('pull_out_date', $request->sales_date)
            ->where('bu_id', $login_user->bunitid)
            ->sum('amount');

        $view = view('treasury.logbook.cpotable', compact('createcpo'))->render();

        return response()->json([
            'view' => $view,
            'id' => $createcpo->id,
            'cpototal' => $cpoTotal,
            'cpototaltext' => number_format($cpoTotal,2)

        ]);

    }

    public function logbooksubmit(Request $request) {

//        return $login_user->bunitid;
        $data = $request->validate([
            'ds' => 'required|array',
            'ds.*' => 'required',
            'datedeposit' => 'required',
            'sales_date' => 'required',
            'currency' => 'required|not_in:0',
            'bankaccounts' => 'required|not_in:0',
            'cashids' => 'required|array',
            'cashids.*' => 'required|string',
            'autoids' => 'required|array',
            'autoids.*' => 'required|string',
            'cs_amount' => 'required|array',
            'cs_amount.*' => 'required|string',
            'pdc_details' => 'required',
            'pdc_other_details' => 'required',
            'dc_details' => 'required',
            'dc_other_details' => 'required',
            'duecheck_details' => 'required',
            'arFrom' => 'required_if:logbookDesc.*,ADMIN: AR OTHERS'
        ], [
            'required_if' => 'The :attribute is required'
        ]);

//        return $request;
        $login_user = Auth::user();

        $bankaccid = Bankaccount::find($request->bankaccounts);

        $bankcode = Bankcode::find($bankaccid->bankno);

        if ($request->ajax()) {

            $createsmbreakdown = null;

            $url_sales_date = strtotime($request->url_sales_date);
            $deposit_date = strtotime($request->datedeposit);

            if ($url_sales_date>=$deposit_date) {
                return response()->json(['message' => 'Looks like sales date is greater than or equal to deposit date not valid!', 'type' => 'error']);
            }

            $smds = collect($request->smds);
            $ds = collect($request->ds);
            $overallds = [];

            if($smds->count() === 1) {
                $overallds[] = $smds->merge($ds);
            } else {

            }

//            if ($smds->count)

//            $cashcount = Cashlog::count();
            $fieldcount = count($request->cashids);

//            return $overallds;

//            if ($cashcount === $fieldcount) {
//                return $request->autoids;
            foreach ($request->cashids as $key => $cash_id) {
//                    $autoamount = $request->autoamounts[$key];
//                    $amount = $request->cs_amount[$key];
//                    $autods = $overallds[0][$key];
                $ds = $request->ds[$key];
                $arFrom = $request->arFrom[$key];
                $arTo = $request->arTo[$key];
                $input_status = $request->inputStatus[$key];
                $sales_date = $request->sales_date[$key];
                $status_adj = $request->status_adj[$key];
                $logbookDesc = $request->logbookDesc[$key];
                $hrmsCode = $request->hrmsCode[$key];
                $csAmount = $request->csAmount[$key];

                $amount = preg_replace('/\,/','', $request->cs_amount[$key]);

                $createcs = Cashlogbook::updateOrCreate(
                    [
                        'sales_date' => $sales_date,
                        'deposit_date' => $request->datedeposit,
//                        'logbook_desc' => $request->logbookDesc,
//                        'cash_id' => $cash_id,
                        'trans_code' => $key+1,
                        'bu_unit' => $login_user->bunitid,
//                            'user_input_status' => 'auto',
                        'bank_id' => $request->bankaccounts,
                    ],
                    [
                        'sales_date' => $sales_date,
                        'deposit_date' => $request->datedeposit,
//                            'ds_no' => $ds,
                        'logbook_desc' => $logbookDesc,
                        'amount' => $amount,
                        'ar_from' => $arFrom,
                        'ar_to' => $arTo,
                        'bank_code' => $bankcode->bankno,
                        'status_clerk' => 'posted',
                        'status_is_cleared' => 1,
                        'status_adj' => $status_adj,
                        'user_input_status' => $input_status,
                        'cash_id' => $cash_id,
                        'bank_id' => $request->bankaccounts,
                        'currency_id' => $request->currency,
                        'company' => $login_user->company_id,
                        'bu_unit' => $login_user->bunitid,
                        'hrms_code' => $hrmsCode,
                        'cs_amount' => $csAmount,
                        'created_by' => $login_user->user_id,
                        'updated_by' => $login_user->user_id,
                        'trans_code'
                    ]
                );

                if ($createcs->logbook_desc) {
                    if (strtolower($createcs->logbook_desc) == 'supermarket') {
                        $createsmbreakdown = Smbreakdown::updateOrCreate([
                            'cash_logbook_id' => $createcs->id,
                        ],[
                            'liq_input_amount' => $request->inputed_liq_amount,
                            'pdc_total' => $request->pdcTotal,
                            'due_checks_total' => $request->due_checks,
                            'cash_pullout_total' => $request->cpo_total_in,
                            'cash_logbook_id' => $createcs->id,
                        ]);
                    }
                }

                if (is_array($ds)) {
                    $ds_amount = $ds[1];
                    foreach ($ds[0] as $keyd => $d) {
                        $createds = Depositslip::updateOrCreate(
                            [
                                'trans_code' => $keyd+1,
                                'sales_date' => $sales_date,
                                'cash_logbook_id' => $createcs->id
                            ],
                            [
                                'ds_number' => $d,
                                'amount' => $ds_amount[$keyd],
                                'cash_logbook_id' => $createcs->id,
                                'sales_date' => $sales_date,
                                'created_by' => $login_user->user_id,
                                'updated_by' => $login_user->user_id,
                            ]
                        );
                    }
                } else {
                    $createds2 = Depositslip::updateOrCreate(
                        [
                            'trans_code' => 1,
                            'sales_date' => $sales_date,
                            'cash_logbook_id' => $createcs->id
                        ],
                        [
                            'ds_number' => $ds,
                            'cash_logbook_id' => $createcs->id,
                            'sales_date' => $sales_date,
                            'created_by' => $login_user->user_id,
                            'updated_by' => $login_user->user_id,
                        ]
                    );
                }
            }

            $totals = Totaldetails::updateOrCreate([
                'sales_date' => $request->sales_date[0]
            ],[
                'pdc_total' => $request->pdcTotal,
                'dated_check_total' => $request->due_checks,
                'cash_pullout_total' => $request->cpo_total_in,
                'sales_date' => $request->sales_date[0],
                'deposit_date' => $request->datedeposit,
                'bu_id' => $login_user->bunitid,


            ]);

            /*
             * check exchange and atp
             */
            $pdcchecks = json_decode($request->pdc_details, true);

            $pdc_others = json_decode($request->pdc_other_details, true);

            $dc = json_decode($request->dc_details, true);

            $dc_others = json_decode($request->dc_other_details, true);

            $due = json_decode($request->duecheck_details, true);
            /*
             * ------------
             */
            if (count($pdcchecks) > 0) {
                foreach ($pdcchecks as $key => $pdc) {
                    Check2::updateOrCreate([
                        'check_id' => $pdc['checks_id'],
                    ], [
                        'check_id' => $pdc['checks_id'],
                        'check_amount' => $pdc['check_amount'],
                        'sm_id' => $createsmbreakdown->id,
                        'deposit_id' => $totals->id,
                        'trans_code' => $key + 1,
                        'type' => 'pdc',
                        'created_by' => $login_user->user_id,
                        'updated_by' => $login_user->user_id,
                    ]);
                }
            }

            if (count($pdc_others) > 0) {
                foreach ($pdc_others as $key => $pdc) {
                    Check2::updateOrCreate([
                        'check_id' => $pdc['checks_id'],
                    ], [
                        'check_id' => $pdc['checks_id'],
                        'check_amount' => $pdc['check_amount'],
                        'deposit_id' => $totals->id,
                        'trans_code' => $key + 1,
                        'type' => 'pdc',
                        'created_by' => $login_user->user_id,
                        'updated_by' => $login_user->user_id,
                    ]);
                }
            }

            foreach ($dc as $key => $d) {
                Check2::updateOrCreate([
                    'check_id' => $d['checks_id'],
                ],[
                    'check_id' => $d['checks_id'],
                    'check_amount' => $d['check_amount'],
                    'sm_id' => $createsmbreakdown->id,
                    'deposit_id' => $totals->id,
                    'trans_code' => $key+1,
                    'type' => 'dc',
                    'created_by' => $login_user->user_id,
                    'updated_by' => $login_user->user_id,
                ]);
            }

            foreach ($dc_others as $key => $dc) {
                Check2::updateOrCreate([
                    'check_id' => $dc['checks_id'],
                ],[
                    'check_id' => $dc['checks_id'],
                    'check_amount' => $dc['check_amount'],
                    'deposit_id' => $totals->id,
                    'trans_code' => $key+1,
                    'type' => 'dc',
                    'created_by' => $login_user->user_id,
                    'updated_by' => $login_user->user_id,
                ]);
            }

            if (count($due) > 0) {
                foreach ($due as $key => $d) {
                    Check2::updateOrCreate([
                        'check_id' => $d['checks_id'],
                    ],[
                        'check_id' => $d['checks_id'],
                        'check_amount' => $d['check_amount'],
                        'deposit_id' => $totals->id,
                        'trans_code' => $key+1,
                        'type' => 'due',
                        'created_by' => $login_user->user_id,
                        'updated_by' => $login_user->user_id,
                    ]);
                }
            }

            return response()->json(['message' => 'Logbook data has been saved!', 'type' => 'success']);
        }
        return redirect()->route('trlogbook')->with('success', "Logbook data has been saved!");
    }

    public function logbook(Request $request) {

        $validate = $request->validate([
            'date' => 'required|date'
        ]);

        $login_user = Auth::user();
        $login_user_bu = $login_user->businessunit->bname;

        $sales_date = $request->date;
        $date_now = date("Y-m-d");

        $blocker = 'none';

        if (strtotime($sales_date) >= strtotime($date_now)) {
            $blocker = 'flex';
        }

        $carbon_sales_date = Carbon::parse($sales_date);

        $department = Departmentccm::whereIn('department', ['ATP', 'Check Exchange'])->pluck('department_id');

        $datedCheck = Check::select('checks_id', 'check_amount', 'check_class')
            ->atpDept($department)
            ->where('check_received', $sales_date)
            ->where('check_date', '<=', DB::raw('check_received'))
            ->where('check_status', 'CLEARED')
            ->where('businessunit_id', $login_user->bunitid)
            ->get();

//        $datedCheckNow = Check::where('check_received', $date_now)
//            ->where('check_status', 'CLEARED')
//            ->where('businessunit_id', $login_user->bunitid)
//            ->get();

        $merge_pdc_dated_check = [];
        foreach ($datedCheck as $d) {
            $merge_pdc_dated_check[] = $d;
        }

        $pdc_received_on_sales_date = Check::select('checks_id', 'check_date', 'check_amount', 'check_class')
            ->atpDept($department)
            ->where('check_received', '<', DB::raw('check_date'))
            ->where('check_received', $sales_date)
            ->where('businessunit_id', $login_user->bunitid)
            ->get();

        $post_sales_date = Carbon::parse($sales_date)->addDay();

        if (strtolower($login_user_bu) == 'island city mall') {
            $pdc_received_on_sales_date = $pdc_received_on_sales_date->filter(function ($item) use ($post_sales_date) {
                return (data_get($item, 'check_date') > $post_sales_date);
            });
        }

        foreach ($pdc_received_on_sales_date as $p) {
            $merge_pdc_dated_check[] = $p;
        }

        $merge_pdc_dated_check = collect($merge_pdc_dated_check);

        $checks_sum = $merge_pdc_dated_check->sum('check_amount');

        $check_get = $merge_pdc_dated_check;

        $check_classes = $merge_pdc_dated_check->unique('check_class');

        $checks_base_class_final = [];

        foreach ($check_classes as $check_class) {
            $check_class_total = round($check_get->where('check_class', $check_class->check_class)->sum('check_amount'),2);

            $checks_base_class_final[] = [
                'check_class' => $check_class->check_class,
                'check_class_total' => $check_class_total
            ];
        }

        $due_check_total = $datedCheck->sum('check_amount');

        $cpos = Cashpullout::select('id','amount', 'department')
            ->where('pull_out_date', $sales_date)
            ->where('bu_id', $login_user->bunitid)
            ->get();

        $currencies = Currency::get(['currency_id', 'currency_name']);

        $bankaccess = Bubankaccess::with(['bank'=>function($query){
            $query->select('id', 'bank', 'accountno', 'accountname');
        }])
            ->where('buid', $login_user->businessunit->unitid)->get();

        /**
         * Dated checks
         */
        $dated_checks = Check::where('check_received', $sales_date)
            ->where('check_date', '<=', DB::raw('check_received'))
            ->where('check_status', 'CLEARED')
            ->whereNotIn('department_from', $department)
            ->where('businessunit_id', $login_user->businessunit->unitid)
            ->get();

        /**
         * Due Checks
         */
        $carbon_sales_date2 = Carbon::parse($sales_date);
        $sales_date_add = $carbon_sales_date2->addDay();
        $due_checks = Check::where('check_received', '<', $sales_date_add)
            ->where('check_date', $sales_date_add)
            ->where('businessunit_id', $login_user->businessunit->unitid)
            ->where('check_status', 'CLEARED')
            ->get();

        /**
         * PDC
         */
        $pdc_checks = Check::where('check_received', $sales_date)
            ->where('check_received', '<', DB::raw('check_date'))
            ->whereNotIn('department_from', [13,15])
            ->where('businessunit_id', $login_user->businessunit->unitid)
            ->get();

        return view('treasury.logbook', compact('sales_date', 'currencies', 'check_classes', 'checks_sum', 'checks_base_class_final', 'due_check_total', 'cpos', 'login_user_bu', 'carbon_sales_date', 'login_user', 'blocker', 'pdc_received_on_sales_date', 'bankaccess', 'datedCheck', 'dated_checks', 'due_checks', 'pdc_checks'));
    }

    public function logbookViewCheckDetail(Request $request) {
        $login_user = Auth::user();

        $carbon_date = Carbon::parse($request->dateR);

        $datedchecks = Check::select('check_no', 'check_class', 'check_category', 'check_date', 'account_no', 'account_name', 'customer_id', 'check_amount', 'department_from', 'check_type')
            ->where('check_received', $request->dateR)
            ->where('check_status', 'CLEARED')
            ->where('check_class', $request->checkClass)
            ->where('businessunit_id', $login_user->bunitid)
            ->get();

        $pdc_received = Check::where('check_type', 'POST DATED')
            ->where('check_received', $request->dateR)
            ->where('check_class', $request->checkClass)
            ->where('businessunit_id', $login_user->bunitid)
            ->get();

        $pdc_due = Check::where('check_type', 'POST DATED')
            ->where('check_date', $request->dateR)
            ->where('check_class', $request->checkClass)
            ->where('businessunit_id', $login_user->bunitid)
            ->get();

        $result = [];
        $title = 'All check received on '.$carbon_date->format('F d, Y');

        switch ($request->action) {
            case 'all':
                foreach ($pdc_received as $key => $value) {
                    $result[] = [
                        $value->check_no,
                        $value->check_class,
                        $value->customerccm->fullname,
                        $value->account_name,
                        $value->account_no,
                        $value->check_category,
                        $value->check_date->format('F d, Y'),
                        number_format($value->check_amount,2),
                        ($value->departmentccm)?$value->departmentccm->department:'',
                        $value->check_type
                    ];
                }

                foreach ($pdc_due as $key => $value) {
                    $result[] = [
                        $value->check_no,
                        $value->check_class,
                        $value->customerccm->fullname,
                        $value->account_name,
                        $value->account_no,
                        $value->check_category,
                        $value->check_date->format('F d, Y'),
                        number_format($value->check_amount,2),
                        ($value->departmentccm)?$value->departmentccm->department:'',
                        $value->check_type
                    ];
                }

                foreach ($datedchecks as $key => $value) {
                    $result[] = [
                        $value->check_no,
                        $value->check_class,
                        $value->customerccm->fullname,
                        $value->account_name,
                        $value->account_no,
                        $value->check_category,
                        $value->check_date->format('F d, Y'),
                        number_format($value->check_amount,2),
                        ($value->departmentccm)?$value->departmentccm->department:'',
                        $value->check_type
                    ];
                }
                break;
            case 'dated':
                foreach ($datedchecks as $key => $value) {
                    $result[] = [
                        $value->check_no,
                        $value->check_class,
                        $value->customerccm->fullname,
                        $value->account_name,
                        $value->account_no,
                        $value->check_category,
                        $value->check_date->format('F d, Y'),
                        number_format($value->check_amount,2),
                        ($value->departmentccm)?$value->departmentccm->department:'',
                        $value->check_type
                    ];
                }
                $title = 'Dated check received on '.$carbon_date->format('F d, Y');
                break;
            case 'pdc':
                foreach ($pdc_received as $key => $value) {
                    $result[] = [
                        $value->check_no,
                        $value->check_class,
                        $value->customerccm->fullname,
                        $value->account_name,
                        $value->account_no,
                        $value->check_category,
                        $value->check_date->format('F d, Y'),
                        number_format($value->check_amount,2),
                        ($value->departmentccm)?$value->departmentccm->department:'',
                        $value->check_type
                    ];
                }
                $title = 'Post dated check received on '.$carbon_date->format('F d, Y');
                break;
            case 'due':
                foreach ($pdc_due as $key => $value) {
                    $result[] = [
                        $value->check_no,
                        $value->check_class,
                        $value->customerccm->fullname,
                        $value->account_name,
                        $value->account_no,
                        $value->check_category,
                        $value->check_date->format('F d, Y'),
                        number_format($value->check_amount,2),
                        ($value->departmentccm)?$value->departmentccm->department:'',
                        $value->check_type
                    ];
                }
                $title = 'Post dated check due on '.$carbon_date->format('F d, Y');
                break;
            default:
                break;
        }

        $total = $datedchecks->sum('check_amount') + $pdc_received->sum('check_amount') + $pdc_due->sum('check_amount');

        return response()->json([
            'title' => $title,
            'tabledata' => $result,
            'total' => $total
        ]);
    }

    public function add_cash(Request $request) {

        $login_user = Auth::user();

        $data = $request->validate([
            'department' => 'required',
            'sales_date' => 'required|date',
            'ds' => 'required',
            'cashamount' => 'required',
            'arFrom' => 'required_if:department,21'
        ], [
            'required_if' => 'The :attribute is required'
        ]);

        $amount = preg_replace('/\,/','', $request->cashamount);

        $create = Cashlogbook::updateOrCreate(
            [
                'sales_date' => $request->sales_date,
                'cash_id' => $request->department,
                'company' => $login_user->company_id,
                'bu_unit' => $login_user->bunitid,
                'user_input_status' => 'auto'
            ],
            [
                'sales_date' => $request->sales_date,
                'ds_no' => trim($request->ds),
                'amount' => $amount,
                'amount_edited' => $amount,
                'ar_from' => $request->arFrom,
                'ar_to' => $request->arTo,
                'cash_id' => $request->department,
                'company' => $login_user->company_id,
                'bu_unit' => $login_user->bunitid,
                'status_clerk' => 'posted',
                'user_input_status' => 'auto',
                'bu_status' => 'manual',
                'created_by' => $login_user->user_id,
                'updated_by' => $login_user->user_id
            ]
        );

        $view = view('treasury.cashlogcreate', compact('create'))->render();

//        $cash = Cashlog::where('cash_status', 'automatic')->get();
//
//        $totalc = [];
//
//        foreach ($cash as $key => $c) {
//            $cash = Denomination::where('emp_dept', $c->description)
//                ->where('date_shrt', $request->sales_date)
//                ->where('emp_bu', $login_user->businessunit->bname);
//
//            $totalc[] = [
//                'total' => $cash->sum('den_total')
//            ];
//        }

        $denominations = Denomination::select(DB::raw('ROUND(SUM(den_total),2) as amount'), 'emp_dept')
            ->where('emp_bu_code', $login_user->businessunit->bu_code)
            ->where('date_shrt', $request->sales_date)
            ->groupBy('emp_dept_code');

        $denom = $denominations->get();

        $cashlogs = [];

        foreach ($denom as $key => $d) {
            $denom_dept[] = $d->emp_dept;
            if ($d->emp_dept === 'SUPERMARKET') {
                $smcashlogs[] = [
                    'total' => $d->amount,
                ];
            } else {
                $cashlogs[] = [
                    'total' => $d->amount,
                ];
            }
        }

//        $totalc = collect($totalc);

        $totalCashLog = Cashlogbook::where('sales_date', $request->sales_date)
            ->where('company', $login_user->company_id)
            ->where('bu_unit', $login_user->bunitid)
            ->where('status_clerk', 'posted')
            ->where('status_is_cleared', 0)
            ->where('user_input_status', 'auto')
            ->sum('amount_edited');

        $cashlogs = collect($cashlogs);

//        $cashlogs = $cashlogs->sum('total');

        $smcashlogs = collect($smcashlogs);

        $overalltotal = $smcashlogs->sum('total') + $cashlogs->sum('total') + $totalCashLog;

//        $view = [
//            'id' => $create->cash_id,
//            'description' => $create->cashLog->description,
//            'sales_date' => $create->sales_date->format('F d, Y'),
//            'ds_number' => $create->ds_no,
//            'amount_edited' => $create->amount_edited
//        ];

        //return redirect()->route('routename')->with('success', "Your question has been submitted!");
        
        return response()->json(['message' => 'Data has been successfully saved!',
            'view' => $view,
            'id' => $create->cash_id,
            'amount' => number_format($create->amount_edited,2),
            'ds' => $create->ds_no,
            'ardata' => ($create->ar_to == '')?$create->ar_from:$create->ar_from.' to '.$create->ar_to,
            'total' => number_format($overalltotal, 2)
        ]);
    }

    public function logbookMonthList(Request $request) {
        $login_user = Auth::user();

        $code = explode('-', $login_user->businessunit->bu_code);

        if (strtolower($login_user->businessunit->bname) == 'island city mall') {
            $months = Denomination::select('date_shrt as sales_date')
                ->where('com_code', $code[0])
                ->where('bu_code', $code[1])
                ->where('dep_status', '')
                ->groupBy(DB::raw('MONTH(sales_date)'))
                ->orderBy('sales_date', 'DESC')
                ->get();
        } else {
            $months = Denominationcs::select('date_shrt as sales_date')
                ->where('company_code', $code[0])
                ->where('bunit_code', $code[1])
//                ->where('dep_status', '')
                ->groupBy(DB::raw('MONTH(sales_date)'))
                ->orderBy('sales_date', 'DESC')
                ->get();
        }

        return view('treasury.logbook.monthlist', compact('login_user', 'months'));
    }

    public function daylist(Request $request) {
        $login_user = Auth::user();

        $date = explode('-', $request->date);

        $code = explode('-', $login_user->businessunit->bu_code);

        if (strtolower($login_user->businessunit->bname) == 'island city mall') {
            $days = Denomination::select('date_shrt', DB::raw('DATE_FORMAT(date_shrt, "%M %d, %Y") as sales_date_text'))
                ->whereYear('date_shrt', $date[0])
                ->whereMonth('date_shrt', $date[1])
                ->where('dep_status', '')
                ->where('com_code',$code[0])
                ->where('bu_code',$code[1])
                ->groupBy('date_shrt')
                ->orderBy('date_shrt', 'DESC')
                ->get();
        } else {
            $days = Denominationcs::select('date_shrt', DB::raw('DATE_FORMAT(date_shrt, "%M %d, %Y") as sales_date_text'))
                ->whereYear('date_shrt', $date[0])
                ->whereMonth('date_shrt', $date[1])
//            ->where('dep_status', '')
                ->where('company_code',$code[0])
                ->where('bunit_code',$code[1])
                ->groupBy('date_shrt')
                ->orderBy('date_shrt', 'DESC')
                ->get();
        }

        return view('treasury.day', compact('days'));
    }
    //
    public function index() {
        $cashlogs = Cashlog::all();

        return view('treasury/treasury-form',compact('cashlogs'));
    }

    public function savecash(Request $request) {

        $data = $request->validate([
            'sales_date' => 'required|date',
//            'sales_date' => new SalesDate,
            'deposit_date' => 'required|date',
            'company' => 'required',
            'businessunit' => 'required',
            'cashlog' => 'required|array',
            'cashlog.*' => 'required|string',
            'ds_number' => 'required|array',
            'ds_number.*' => 'required|string',
            'amount' => 'required|array',
            'amount.*' => 'required|string',
            'arfrom' => 'nullable',
            'arto' => 'nullable',
        ]);

        $login_user = Auth::user();
        $salesDate = $request->sales_date;
        $depositDate = $request->deposit_date;
        $company = $login_user->businessunit->company->company_code;
        $businessunit = $login_user->businessunit->unitid;

        $validateSalesDate = Cashlogbook::where('company', $company)
            ->where('bu_unit', $businessunit)
            ->groupBy('sales_date')->count();

        if ($validateSalesDate > 0) {
            return '<span style="color: red">Sales date already been taken</span> <a href="'.route('home').'">Go back home</a>';
        }

        foreach ($request->cashlog as $key => $logs) {
            $dsnumber = $request->ds_number[$key];
            $amount = preg_replace('/\,/','', $request->amount[$key]);
            $arfrom = $request->arfrom[$key];
            $arto = $request->arto[$key];

            $validate = Cashlogbook::where('sales_date', $salesDate)
                ->where('deposit_date', $depositDate)
                ->where('cash_id', $logs)
                ->where('company', $company)
                ->where('bu_unit', $businessunit);
//            return $validate->count();
            if ($validate->count() === 0) {
                $cashlogs = Cashlogbook::create([
                    'sales_date' => $salesDate,
                    'deposit_date' => $depositDate,
                    'ds_no' => $dsnumber,
                    'amount' => $amount,
                    'amount_edited' => $amount,
                    'ar_from' => $arfrom,
                    'ar_to' => $arto,
                    'cash_id' => $logs,
                    'company' => $company,
                    'bu_unit' => $businessunit,
                    'status_clerk' => 'posted'
                ]);
            }


//            return response($cashlogs, 201);

        }

        return 'successfully saved!  <a href="'.route('home').'">Go back home</a>';

    }
}
