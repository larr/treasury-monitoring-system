<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@welcome')->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('invaliduserlogout', 'Auth\LoginController@invalidUserLogout')->name('invaliduserlogout');

Route::prefix('treasury')->group(function ($e) {
    Route::get('daylist', 'TreasuryController@daylist')->name('trdaylist');
    Route::put('add-cash', 'TreasuryController@add_cash')->name('traddcash');
    Route::post('add-bu', 'TreasuryController@add_bu')->name('traddbu');
    Route::post('add-cash-pull-out', 'TreasuryController@add_cash_pull_out')->name('traddcpo');

    Route::post('add-manual-cash-adj', 'TreasuryController@add_manual_cash_adj')->name('tr_add_manual_cash_adj');

    Route::get('reports/banks','Reports@listbanks')->name('trreportlistbanks');
    Route::get('reports/monthly','Reports@monthly')->name('trreportmonthly');
    Route::get('reports/daily','Reports@daily')->name('trreportdaily');
    Route::get('reports/viewing','Reports@cashViewing')->name('trreportviewreport');
    Route::get('reports/cpo/{id}/{date}','Reports@cpoReport')->name('trreportviewcporeport');

    Route::get('logbook/cashier/details', 'TreasuryController@logbookCashierDetails')->name('tr.logbook.cashier.details');
    Route::get('logbook/cashier/list', 'TreasuryController@logbookCashierList')->name('tr.logbook.cashier.list');

    Route::get('logbook/monthlist', [
        'uses' => 'TreasuryController@logbookMonthList',
        'as' => 'trlogbookmonthlist',
        'middleware' => 'roles',
        'roles' => ['Treasury']
    ]);

    Route::put('logbooksubmit', 'TreasuryController@logbooksubmit')->name('trlogbooksubmit');
    Route::post('logbook/view-check-detail', 'TreasuryController@logbookViewCheckDetail')->name('trlogbookcheckdetail');
    Route::post('logbook/units', 'TreasuryController@getunits')->name('trlogbookgetunits');
    Route::post('logbook/addunits', 'TreasuryController@addunits')->name('trlogbookaddunits');
    Route::post('logbook/addunittolist', 'TreasuryController@addunittolist')->name('trlogbookaddunittolist');
    Route::get('logbook/edit', 'TreasuryController@logbookedit')->name('trlogbookedit');
    Route::get('logbook', 'TreasuryController@logbook')->name('trlogbook');

});

Route::prefix('admin')->group(function ($e) {
    Route::get('dashboard', 'AdminController@index')->name('tr.admin.dashboard')->middleware('auth', 'roles');
    Route::get('user-access-banks', 'UseraccessController@useraccessbanks')->name('tr.admin.useraccessbanks');
    Route::get('user-access-company', 'UseraccessController@useraccesscompany')->name('tr.admin.useraccesscompany');
    Route::get('user-access-bu', 'UseraccessController@useraccessbu')->name('tr.admin.useraccessbu');
    Route::get('user-access-department', 'UseraccessController@useraccessdept')->name('tr.admin.useraccessdept');
    Route::get('user-access-section', 'UseraccessController@useraccesssec')->name('tr.admin.useraccesssec');
    Route::get('user-access-subsection', 'UseraccessController@useraccesssub')->name('tr.admin.useraccesssub');

    Route::get('add-bank', 'AdminController@addBank')->name('tr.admin.addbank');
    Route::get('create-bu-bank', 'AdminController@createBuBank')->name('tr.admin.create.bubank');
    Route::post('select-bank', 'AdminController@selectBank')->name('tr.admin.selectbank');
    Route::post('store-bu-bank', 'AdminController@storeBuBank')->name('tr.admin.bubank.store');
    Route::delete('delete-bu-bank', 'AdminController@deleteBuBank')->name('tr.admin.bubank.delete');

    Route::get('category-access-category-list', 'CategoryaccessController@categoryList')->name('tr.admin.category-list');
    Route::resource('category-access', 'CategoryaccessController');

    Route::resource('categories', 'CategoriesController');

    Route::resource('section-access', 'UseraccessController');

});

//admin routes
Route::prefix('users')->group(function ($e) {
    Route::get('/', ['uses' => 'AdminController@users', 'as' => 'tr.users', 'middleware' => 'roles', 'roles' => ['Admin']]);
    Route::get('{id}', ['uses' => 'AdminController@userRoles', 'as' => 'tr.user.roles']);
    Route::post('assignRole', ['uses' => 'AdminController@assignRole', 'as' => 'tr.user.assign']);
});

Route::get('cash', ['uses' => 'AdminController@cash','as' => 'tr.cash','middleware' => 'roles','roles' => ['Admin']]);

Route::get('logbook', ['uses' => 'AdminController@logbook','as' => 'tr.logbook','middleware' => 'roles','roles' => ['Admin']]);
Route::post('logbook/list', ['uses' => 'AdminController@logbookList','as' => 'tr.logbook.list','middleware' => 'roles','roles' => ['Admin']]);
Route::post('logbook/fillcodes', ['uses' => 'AdminController@logbookFillCodes','as' => 'tr.logbook.fill.codes','middleware' => 'roles','roles' => ['Admin']]);
Route::post('logbook/fill-ds-codes', ['uses' => 'AdminController@logbookFillDSCodes','as' => 'tr.logbook.fill.ds.codes','middleware' => 'roles','roles' => ['Admin']]);
Route::get('logbook/view/{id}', ['uses' => 'AdminController@logbookView','as' => 'tr.logbook.view','middleware' => 'roles','roles' => ['Admin']]);
Route::post('logbook/viewcodes', ['uses' => 'AdminController@logbookViewCodes','as' => 'tr.logbook.view.codes','middleware' => 'roles','roles' => ['Admin']]);

Route::prefix('reports')->group(function ($e) {
    //accounting routes
    Route::get('/', ['uses' => 'AccountingController@index','as' => 'tr.reports','middleware' => 'roles','roles' => ['Accounting']]);
    Route::get('logbook', ['uses' => 'AccountingController@logbook','as' => 'tr.reports.logbook','middleware' => 'roles','roles' => ['Accounting']]);
    Route::get('logbook-month', ['uses' => 'AccountingController@logbookReportMonth', 'as' => 'tr.reports.logbook.month', 'middleware' => 'roles', 'roles' => ['Accounting']]);
    Route::get('logbook-day', ['uses' => 'AccountingController@logbookReportDay', 'as' => 'tr.reports.logbook.day', 'middleware' => 'roles', 'roles' => ['Accounting']]);
    Route::get('logbook-view', ['uses' => 'AccountingController@logbookReportView', 'as' => 'tr.reports.logbook.view', 'middleware' => 'roles', 'roles' => ['Accounting']]);
});