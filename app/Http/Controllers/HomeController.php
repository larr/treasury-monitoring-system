<?php

namespace App\Http\Controllers;

use App\Cashlog;
use App\Denomination;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $login_user = Auth::user();
        $login_user->user_id;
        $login_user->firstname;
//        $login_user->load('usertype');

//        return $login_user->roles;
        $isadmin = true;
        $page_title="Welcome ".$login_user->firstname."!";

        return view('home', compact('login_user', 'isadmin', 'page_title'));
//        $login_user = Auth::user();
//
//        $code = explode('-', $login_user->businessunit->bu_code);
//
//        if (strtolower($login_user->usertype->user_type_name) === 'treasury') {
//
//        $months = Denomination::select('date_shrt as sales_date')
//            ->where('com_code', $code[0])
//            ->where('bu_code', $code[1])
//            ->where('dep_status', '')
//            ->groupBy(DB::raw('MONTH(sales_date)'))
//            ->orderBy('sales_date', 'DESC')
//            ->get();
//
//            return view('treasury.home', compact('login_user', 'months'));
//        } else {
//            return view('invaliduser');
//        }

    }
    public function welcome() {
        return view('welcome');
    }
}
