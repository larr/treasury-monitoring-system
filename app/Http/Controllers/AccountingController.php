<?php

namespace App\Http\Controllers;

use App\Bankaccount;
use App\Cashlogbook;
use App\Cashpullout;
use App\Check;
use App\Currency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountingController extends Controller
{
    //
    public function index() {
        $login_user = Auth::user();
        $page_title = 'Reports';
        return view('reports.index', compact('login_user', 'page_title'));
    }

    public function logbook() {
        $login_user = Auth::user();
        $page_title = "Logbook";

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

        return view('reports.logbook', compact('login_user', 'page_title', 'banks'));
    }

    public function logbookReportMonth(Request $request) {
        $login_user    = Auth::user();
        $page_title = "Logbook Report Monthlist";
        $bankid = $request->bankId;

        $monthly = Cashlogbook::select('sales_date as dateT')
            ->where('bu_unit',$login_user->bunitid)
            ->where('status_is_cleared', 1)
            ->where('bank_id', $bankid)
            ->where('company',$login_user->company_id);

        if ($request->report_type == 'sales') {
            $monthly = $monthly->groupBy(DB::raw('MONTH(dateT)'))
                ->orderBy('sales_date', 'DESC')
                ->get();
        }
        return view('reports.logbook-monthly',compact('monthly', 'bankid', 'request', 'login_user', 'page_title'));
    }

    public function logbookReportDay(Request $request) {
        $login_user = Auth::user();
        $page_title = "Logbook Report Daylist";
        $bankid = $request->bankId;
        $date = Carbon::parse($request->dateT);

        $month = $date->format('m');
        $year  = $date->format('Y');


        $daily = Cashlogbook::where('bu_unit',$login_user->bunitid)
//                ->where('user_input_status', 'auto')
            ->where('status_is_cleared', 1)
            ->where('bank_id', $bankid)
            ->where('company',$login_user->company_id)
            ->distinct();
        if ($request->report_type == 'sales') {
            $daily = $daily->whereMonth('sales_date',$month)
                ->whereYear('sales_date',$year)
                ->orderBy('sales_date', 'DESC')
                ->get(['sales_date as dateT']);
        }


        return view('reports.logbook-daily',compact('daily', 'bankid', 'login_user', 'page_title'));
    }

    public function logbookReportView(Request $request) {
        $login_user = Auth::user();
        $page_title = 'Logbook view reports';
        $salesDate = $request->dateT;
        $bankid = $request->bankId;
//		$deposit_date = Carbon::parse($date);

        $cashLogs = Cashlogbook::with('sm_br')
//                    ->where('user_input_status', 'auto')
            ->where('status_is_cleared', 1)
            ->where('company',$login_user->company_id)
            ->where('bu_unit',$login_user->bunitid);



        if ($request->report_type == 'sales') {

            $cashLogs = $cashLogs->where('bank_id', $bankid)
                ->where('sales_date',$salesDate)
                ->get();
        }

        if ($cashLogs->count() > 0) {

            $bank = Bankaccount::where('id', $bankid)
                ->first();

            $currency = Currency::where('currency_id',$cashLogs[0]->currency_id)->first();

            $depositDate = $cashLogs[0]->deposit_date;
            $salesDate = $cashLogs[0]->sales_date;

            $cpo = Cashpullout::where('pull_out_date', $salesDate)
                ->where('bu_id', $login_user->bunitid)
                ->get();

            $sm_br = Cashlogbook::with(['sm_br'])->where('deposit_date',$depositDate)
                ->where('status_is_cleared', 1)
                ->where('bank_id', $bankid)
                ->where('company',$login_user->company_id)
                ->where('bu_unit',$login_user->bunitid)
                ->first();

            $dated_check_total = Check::where('check_received', $salesDate)
                ->where('check_status', '=', 'CLEARED')
                ->where('businessunit_id', $login_user->bunitid)
                ->sum('check_amount');

            $dated_check_total_deducted_to_sm = Check::where('check_received', $salesDate)
                ->where('check_status', 'CLEARED')
                ->where('businessunit_id', $login_user->bunitid);


            $pdc_total = Check::where('check_type', 'POST DATED')
                ->where('check_date', $salesDate)
                ->where('businessunit_id', $login_user->bunitid)
                ->sum('check_amount');

            if (strtolower($login_user->businessunit->bname) == 'plaza marcela') {
                $pdc_total = Check::where('check_type', 'POST DATED')
                    ->where('check_received', $salesDate)
                    ->where('businessunit_id', $login_user->bunitid)
                    ->sum('check_amount');



                $pdc_deducted_to_sm = Check::where('check_type', 'POST DATED')
                    ->where('check_received', $salesDate)
                    ->where('businessunit_id', $login_user->bunitid)
                    ->sum('check_amount');
                $dated_check_total_deducted_to_sm = $dated_check_total_deducted_to_sm
                    ->whereIn('department_from', [15,13])
                    ->sum('check_amount');

                $total_liquidation_input = $cashLogs[0]->amount;

                $total_liquidation_input = $total_liquidation_input+$dated_check_total_deducted_to_sm+$pdc_deducted_to_sm;

            } else {
                $pdc_deducted_to_sm = Check::where('check_type', 'POST DATED')
                    ->where('check_received', $salesDate)
                    ->where('businessunit_id', $login_user->bunitid)
                    ->sum('check_amount');
                $dated_check_total_deducted_to_sm = $dated_check_total_deducted_to_sm
                    ->sum('check_amount');

                $total_liquidation_input = $cashLogs[0]->amount;

                $total_liquidation_input = $total_liquidation_input+$dated_check_total_deducted_to_sm+$pdc_deducted_to_sm;
            }

            $totalCash = $cashLogs->sum('amount');

            $check = $dated_check_total+$pdc_total;

            $cp = $cpo->sum('amount');

            $totalPullOut = $cp+$totalCash+$check;

            return view('reports.logbook-view',compact('cashLogs','salesDate','depositDate', 'totalCash', 'bank', 'currency', 'sm_br', 'cpo', 'totalPullOut', 'dated_check_total_deducted_to_sm', 'pdc_deducted_to_sm', 'total_liquidation_input', 'total_liquidation_input', 'login_user', 'page_title'));
        }
        return 'empty';
    }
}
