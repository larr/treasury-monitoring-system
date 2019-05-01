<?php

namespace App\Http\Controllers;

use App\Bankaccount;
use App\Bubankaccess;
use App\Businessunit;
use App\Cashbu;
use App\Cashcategory;
use App\Cashlogbook;
use App\Denominationcs;
use App\Departmentpis;
use App\Depositslip;
use App\Role;
use App\Sectionpis;
use App\Subsectionpis;
use App\User;
use App\Useraccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function index() {

        $datenow = date('Y-m-d');
        $split_date_now = explode('-', $datenow);
        list($yearnow, $monthnow, $daynow) = $split_date_now;
        $max_deposit_date = Cashlogbook::max('deposit_date');

        $total_sales_today = Denominationcs::where('date_shrt', $datenow)
            ->where('delete_status', '!=', 'deleted')
            ->sum('total_denomination');

        $latest_total_deposited = Cashlogbook::where('deposit_date', $max_deposit_date)
            ->sum('amount');

        $month_total_sales = Denominationcs::whereMonth('date_shrt', $monthnow)
            ->sum('total_denomination');

        $year_total_sales = Denominationcs::whereYear('date_shrt', $yearnow)
            ->sum('total_denomination');

        $cs_month = Denominationcs::whereYear('date_shrt', $yearnow)
            ->groupBy(DB::raw('MONTH(date_shrt)'))
            ->get();

        $cs_day = Denominationcs::whereMonth('date_shrt', $monthnow)
            ->groupBy(DB::raw('DAY(date_shrt)'))
            ->get(['date_shrt']);

        $total_days_in_month = cal_days_in_month(CAL_GREGORIAN,$monthnow,$yearnow);

        $year_percent = ($cs_month->count()/12)*100;
        $month_percent = ($cs_day->count()/$total_days_in_month)*100;

        $login_user = Auth::user();

        $page_title="Welcome ".$login_user->firstname."!";

        return view('admin.index', compact('total_sales_today','latest_total_deposited', 'month_total_sales', 'year_total_sales', 'year_percent', 'month_percent', 'login_user', 'page_title'));
    }

    public function categories() {
        $categories = Cashcategory::all();

        return view('admin.categories.index', compact('categories'));
    }

    public function users(Request $request) {
        $login_user = Auth::user();
        $page_title = 'Users';
        if ($request->ajax()) {
            $data = [];
            $users = User::orderBy('user_id', 'DESC')
            ->get();
            foreach ($users as $user) {
                $data['data'][] = [
                    $user->firstname . ' ' .  $user->lastname,
                    $user->username,
                    $user->usertype->user_type_name,
                    ($user->businessunit)?$user->businessunit->bname:'',
                    '<a href="javascript:void(0)" data-toggle="modal" data-target=".role-modal" class="user-roles" data-url="' . route("tr.user.roles", ['id' => $user->user_id]) . '"><i class="fa fa-gear fa-spin"></i></a>'
                ];
            }

            return response()->json($data);
        }
        return view('users.index', compact('login_user', 'page_title'));
    }

    public function userRoles(Request $request, $id) {

        $user = User::find($id);

        $roles = Role::all();

        $view = view('users.roles', compact('user', 'roles'))->render();

        return response()->json([
            'view' => $view,
            'user' => strtoupper($user->firstname) . ' ' . strtoupper($user->lastname)
        ]);

    }

    public function assignRole(Request $request) {
        $user = User::find($request->user_id);
        $user->roles()->detach();
        $roles = Role::all();

        foreach ($roles as $role) {
            if ($request['role_'.strtolower($role->name)]) {
                $user->roles()->attach(Role::where('name', $role->name)->first());
            }
        }
    }

    public function cash() {
        $login_user = Auth::user();
        $page_title = 'Treasury cash business unit access';
//        $cash_bus = Cashbu::get();
        $cash_bus = Useraccess::with('cash_bu')
            ->get();
        return view('cash', compact('login_user', 'page_title', 'cash_bus'));
    }

    public function logbook() {
        $login_user  = Auth::user();
        $page_title = 'Logbook';
        $bulogs = Cashlogbook::groupBy('bu_unit')
            ->get();
        return view('admin.logbook', compact('login_user', 'page_title', 'bulogs'));
    }

    public function logbookList(Request $request) {

        $data = $request->validate([
            'bu' => 'required|not_in:0',
            'sales_date' => 'required|date'
        ]);

        $login_user = Auth::user();
        $page_title = "Logbook list";

        $logbooks = Cashlogbook::with('ds')
            ->where('sales_date', $request->sales_date)
            ->where('bu_unit', $request->bu)
            ->get();
        return view('admin.logbook-list', compact('login_user', 'page_title', 'logbooks'));
    }

    public function logbookFillCodes(Request $request) {
//        return $request->row;
//        $update = Cashlogbook::whereIn('id', $request->row['id'])->update(['trans_code' => $request->row['trans_code']]);
        foreach ($request->id as $key => $id) {
            $codes = $request->trans_code[$key];
            $update = Cashlogbook::where('id', $id)->update(['trans_code' => $codes]);
        }
    }

    public function logbookFillDSCodes(Request $request) {
        $id = $request->id;
        $ds = Depositslip::where('cash_logbook_id', $id)
            ->get();

        foreach ($ds as $key => $d) {
            $update = Depositslip::where('id', $d->id)->update([
                'trans_code' => $key+1
            ]);
        }
    }

    public function addBank(Request $request) {
        $login_user = Auth::user();
        $page_title = 'List of units with designated banks';

        $bu_bank_access = Bubankaccess::pluck('buid');

        if ($request->ajax()) {
            $data = ['data'=>[]];
            $bus = Businessunit::with('bankaccess')
                ->whereIn('unitid', $bu_bank_access)
                ->get();

            foreach ($bus as $bu) {
                $banks = '<ul class="list-group">';
                foreach ($bu->bankaccess as $b) {
                    $banks .= '<li class="list-group-item d-flex justify-content-between align-items-center">'.$b->id .'-'. $b->bank .'-'. $b->accountno .'-'. $b->accountname.'<span data-id="'.$bu->unitid.'" data-url="'.route('tr.admin.bubank.delete',['bu'=>$bu->unitid,'bank'=>$b->id]).'" class="badge badge-primary badge-pill remove"><i class="fa fa-times"></i></span></li>';
                }
                $banks .= '</ul>';
                $data['data'][] = [
                    $bu->unitid.'-'.$bu->bname,
                    $banks
                ];
            }

            return $data;
        }

        return view('bu_bank_listBu', compact('login_user', 'page_title'));
    }

    public function createBuBank() {
        $bus = Businessunit::all();

        $budata='';

        foreach ($bus as $bu) {
            $budata.='<option value="'.$bu->unitid.'">'.$bu->unitid.'. '.$bu->bname.'</option>';
        }

        return response()->json($budata);
    }

    public function selectBank(Request $request) {

        $banks = Bankaccount::where('buid', $request->id)
            ->get();

        $bankdata = '';

        foreach ($banks as $bank) {
            $bankdata .= '<option class="newly-added" value="'.$bank->id.'">'.$bank->id.'. '.$bank->bank.'-'.$bank->accountno.'-'.$bank->accountname.'</option>';
        }

        return response()->json($bankdata);

    }

    public function storeBuBank(Request $request) {
        $data = $request->validate([
            'bu' => 'required|not_in:0',
            'bank' => 'required|not_in:0',
        ]);

        $create = Bubankaccess::updateOrCreate([
            'buid' => $request->bu,
            'bank_id' => $request->bank
        ], [
            'buid' => $request->bu,
            'bank_id' => $request->bank
        ]);


    }

    public function deleteBuBank(Request $request) {

        $delete = Bubankaccess::where('buid', $request->bu)
            ->where('bank_id', $request->bank)
            ->delete();
        return $delete;
    }

    public function logbookView($id) {
        $logbook = Cashlogbook::with(['bank','bu', 'del_by', 'cre_by', 'upd_by', 'cur'])->find($id);

        $data = [
            'id' => $logbook->id,
            'sales_date' => $logbook->sales_date->format('Y-m-d'),
            'deposit_date' => $logbook->deposit_date->format('Y-m-d'),
            'logbook_desc' => $logbook->logbook_desc,
            'amount' => $logbook->amount,
            'ar_from' => $logbook->ar_from,
            'ar_to' => $logbook->ar_to,
            'bank_code' => $logbook->bank_code,
            'status_clerk' => $logbook->status_clerk,
            'user_input_status' => $logbook->user_input_status,
            'deleted_by' => ($logbook->del_by)?$logbook->del_by->firstname:'',
            'created_by' => ($logbook->cre_by)?$logbook->cre_by->firstname:'',
            'updated_by' => ($logbook->upd_by)?$logbook->upd_by->firstname:'',
            'bank' => ($logbook->bank)?$logbook->bank->accountno:'',
            'currency' => ($logbook->cur)?$logbook->cur->currency_name:'',
            'company' => ($logbook->bu)?$logbook->bu->company->company:'',
            'bu' => ($logbook->bu)?$logbook->bu->bname:'',
            'hrms_code' => $logbook->hrms_code,
            'cs_amount' => $logbook->cs_amount,
        ];

        return $data;
    }

    public function logbookViewCodes(Request $request) {
        $bus = Cashlogbook::find($request->id);
        $cs_bu = json_decode($bus->hrms_code, true);
        $cs_amount = json_decode($bus->cs_amount, true);
        $cs_details = '';
        if (is_array($cs_amount)) {
            foreach ($cs_amount as $key => $b) {

                $department = Departmentpis::whereIn('company_code', [$cs_bu[$key][0]])
                    ->whereIn('bunit_code', [$cs_bu[$key][1]])
                    ->whereIn('dept_code', [$cs_bu[$key][2]])
                    ->first();

                $section = Sectionpis::whereIn('company_code', [$cs_bu[$key][0]])
                    ->whereIn('bunit_code', [$cs_bu[$key][1]])
                    ->whereIn('dept_code', [$cs_bu[$key][2]])
                    ->whereIn('section_code', [$cs_bu[$key][3]])
                    ->first();

                $subsection = Subsectionpis::whereIn('company_code', [$cs_bu[$key][0]])
                    ->whereIn('bunit_code', [$cs_bu[$key][1]])
                    ->whereIn('dept_code', [$cs_bu[$key][2]])
                    ->whereIn('section_code', [$cs_bu[$key][3]])
                    ->whereIn('sub_section_code', [$cs_bu[$key][4]])
                    ->first();

                $ssection = '';
                $s = '';
                $d = '';
                if ($department) {
                    $d = $department->dept_name;
                }
                if ($section) {
                    $s = $section->section_name;
                }
                if ($subsection) {
                    $ssection = $subsection->sub_section_name;
                }

                $cs_details .= '<tr>';
                $cs_details .= '<td>';
                $cs_details .= '['.$d.']';
                $cs_details .= '['.$s.']';
                $cs_details .= '['.$ssection.']';
                $cs_details .= '</td>';
                $cs_details .= '<td>';
                $cs_details .= $b;
                $cs_details .= '</td>';
                $cs_details .= '</tr>';
            }
        }

        return $cs_details;
    }

}
