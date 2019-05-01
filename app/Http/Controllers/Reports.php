<?php

namespace App\Http\Controllers;

use App\Bankaccount;
use App\Bubankaccess;
use App\Cashlogbook;
use App\Cashpullout;
use App\Check;
use App\Currency;
use App\Departmentccm;
use Carbon\Carbon;
use function foo\func;
use function GuzzleHttp\Promise\is_fulfilled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Reports extends Controller
{
    //
	
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function cpoReport(Request $request, $bankid, $date) {

        $user    = Auth::user();

        $cashLogs = Cashlogbook::where('deposit_date',$date)
            ->where('status_is_cleared', 1)
            ->where('bank_id', $bankid)
            ->where('company',$user->company_id)
            ->where('bu_unit',$user->bunitid)
            ->first();

        $cpo = Cashpullout::where('pull_out_date', $cashLogs->sales_date)
            ->get();

	    return view('treasury.reports.cashpullout', compact('cpo', 'user'));

    }

	public function listbanks(Request $request) {
	    $login_user = Auth::user();
//	    $banks = Bankaccount::where('buid', $login_user->bunitid)
//            ->get();
	    $accounts = Cashlogbook::where('bu_unit', $login_user->bunitid)
			->groupBy('bank_id')
            ->get();
	    $banks = [];
	    foreach ($accounts as $a) {
            $bankacc = Bankaccount::find($a->bank_id);
            $banks[] = [
                'id' => $bankacc->id,
                'bank' => $bankacc->bank,
                'accountno' => $bankacc->accountno,
                'accountname' => $bankacc->accountname,
            ];
        }
        return view('treasury.reports.banklist', compact('banks'));
    }
	
	public function monthly(Request $request)
	{
		$user    = Auth::user();
        $bankid = $request->bankId;
		
//		$monthly = Cashlogbook::select(DB::raw('DISTINCT(DATE_FORMAT(sales_date,"%Y-%m")) as sales_date'))
//			->where('bu_unit',$user->bunitid)
//			->where('company',$user->company_id)
////			->groupBy(DB::raw('MONTH(sales_date)'))
//			->orderBy('sales_date', 'DESC')
//			->get();
        $monthly = Cashlogbook::select('sales_date as dateT')
            ->where('bu_unit',$user->bunitid)
//            ->where('user_input_status', 'auto')
            ->where('status_is_cleared', 1)
            ->where('bank_id', $bankid)
            ->where('company',$user->company_id);

        if ($request->report_type == 'sales') {
            $monthly = $monthly->groupBy(DB::raw('MONTH(dateT)'))
                ->orderBy('sales_date', 'DESC')
                ->get();
        }

//        $months = Denomination::select('date_shrt as sales_date')
//            ->where('emp_bu', $login_user->businessunit->bname)
//            ->where('dep_status', '')
//            ->groupBy(DB::raw('MONTH(sales_date)'))
//            ->orderBy('sales_date', 'DESC')
//            ->get();

		//dd($monthly);
		return view('treasury.reports.monthly',compact('monthly', 'bankid', 'request'));
	}
	
	public function daily(Request $request)
	{
		$user    = Auth::user();
		$bankid = $request->bankId;
		$date = Carbon::parse($request->dateT);

		$month = $date->format('m');
		$year  = $date->format('Y');


		$daily = Cashlogbook::where('bu_unit',$user->bunitid)
//                ->where('user_input_status', 'auto')
                ->where('status_is_cleared', 1)
                ->where('bank_id', $bankid)
				->where('company',$user->company_id)
				->distinct();
		if ($request->report_type == 'sales') {
            $daily = $daily->whereMonth('sales_date',$month)
                ->whereYear('sales_date',$year)
                ->orderBy('sales_date', 'DESC')
                ->get(['sales_date as dateT']);
        }


		return view('treasury.reports.daily',compact('daily', 'bankid'));
	}
	
	public function cashViewing(Request $request)
	{
		$salesDate = $request->dateT;
		$login_user    = Auth::user();
		$bankid = $request->bankId;

        /**
         * Cash query
         */
		$cashLogs = Cashlogbook::with('sm_br')
                    ->where('status_is_cleared', 1)
					->where('company',$login_user->company_id)
					->where('bu_unit',$login_user->bunitid);

		if ($request->report_type == 'sales') {
            /**
             * Get cash
             */
		    $cashLogs = $cashLogs->where('bank_id', $bankid)
                ->where('sales_date',$salesDate)
                ->get();
        }

		if ($cashLogs->count() > 0) {

            /**
             * Total cash
             */
            $totalCash = $cashLogs->sum('amount');

            /**
             * Bank account
             */
            $bank = Bankaccount::where('id', $bankid)
                ->first();

            /**
             * Get currency
             */
            $currency = Currency::where('currency_id',$cashLogs->first()->currency_id)->first();

            /**
             * Get deposit date
             */
            $depositDate = $cashLogs[0]->deposit_date;
            $sales_date_carbon = $cashLogs[0]->sales_date;
            /*
             * Get pullout
             */
            $cpo = Cashpullout::where('pull_out_date', $salesDate)
                ->where('bu_id', $login_user->bunitid)
                ->get();

            /**
             * Get supermarket amount breakdown
             */
            $sm_br = Cashlogbook::with(['sm_br'])->where('sales_date',$salesDate)
                ->where('status_is_cleared', 1)
                ->where('bank_id', $bankid)
                ->where('company',$login_user->company_id)
                ->where('bu_unit',$login_user->bunitid)
                ->where('logbook_desc', 'SUPERMARKET')
                ->first();

            $total_liquidation_input = 0;
            $due_total = 0;
            $pdc_total = 0;
            if ($sm_br) {
                $total_liquidation_input = $sm_br->liq_input_amount;
                $due_total = $sm_br->sm_br->due_checks_total;
                $pdc_total = $sm_br->sm_br->pdc_total;
            }

            /**
             * -------------------------------------------
             * Get dated checks except atp and check exchange
             * -------------------------------------------
             */
            $dated_checks = Check::where('check_received', $salesDate)
                ->where('check_date', '<=', DB::raw('check_received'))
                ->where('check_status', 'CLEARED')
                ->whereNotIn('department_from', [13,15])
                ->where('businessunit_id', $login_user->businessunit->unitid)
                ->sum('check_amount');
//                ->get();

//            $dated_check_dept = $dated_checks->pluck('department_from');
//            $totalDatedCheck = [];
            $totalDatedCheck = 0;
//
//            if (!empty($dated_check_dept->unique())) {
//                foreach ($dated_check_dept->unique() as $c) {
//                    $department = Departmentccm::find($c);
//                    $dated_checksdept = $dated_checks->where('department_from',$department->department_id);
//                    $totalDatedCheck[strtolower($department->department)] = $dated_checksdept->sum('check_amount');
//                }
//            }
            if($sm_br) {
                $totalDatedCheck += $sm_br->sm_br->due_checks_total;
            } else {
                $dated_checks_atp = Check::where('check_received', $salesDate)
                    ->where('check_date', '<=', DB::raw('check_received'))
                    ->where('check_status', 'CLEARED')
                    ->whereIn('department_from', [13,15])
                    ->where('businessunit_id', $login_user->businessunit->unitid)
                    ->sum('check_amount');
                $totalDatedCheck += $dated_checks_atp;
            }
            $totalDatedCheck += $dated_checks;

            $pdc_total = 0;

            /**
             * get pdc received except atp and check exchange
             */
            $pdc_checks = Check::where('check_received', $salesDate)
                ->where('check_received', '<', DB::raw('check_date'))
                ->whereNotIn('department_from', [13,15])
                ->where('businessunit_id', $login_user->businessunit->unitid)
                ->sum('check_amount');
            $pdc_total += $pdc_checks;

            if ($sm_br) {
                $pdc_total += $sm_br->sm_br->pdc_total;
            } else {
                $pdc_checks_atp = Check::where('check_received', $salesDate)
                    ->where('check_received', '<', DB::raw('check_date'))
                    ->whereIn('department_from', [13,15])
                    ->where('businessunit_id', $login_user->businessunit->unitid)
                    ->sum('check_amount');
                $pdc_total += $pdc_checks_atp;
            }

//            $pdc_total += $pdc_checks;

            $sales_date_add = $sales_date_carbon->addDay();

//            return $pdc_checks->sum('check_amount');
            $due_checks = Check::where('check_received', '<', $sales_date_add)
                ->where('check_date', $sales_date_add)
                ->where('businessunit_id', $login_user->businessunit->unitid)
                ->where('check_status', 'CLEARED')
                ->sum('check_amount');

            /**
             * -------------------------------------------
             * End checks query
             * -------------------------------------------
             */

            /**
             * Total pullout
             */
            $totalPullOut = $cpo->sum('amount');

            /**
             * Total deposit
             */
            $totalDeposit = $totalCash+$totalPullOut+$totalDatedCheck+$pdc_total+$due_checks;

            return view('treasury.reports.cashViewing',compact('cashLogs','salesDate', 'totalCash', 'bank', 'currency', 'sm_br', 'cpo', 'total_liquidation_input', 'totalPullOut', 'totalDeposit', 'login_user', 'depositDate', 'pdc_total', 'due_checks', 'totalDatedCheck'));
        }
        return 'empty sales date';
	}
}
