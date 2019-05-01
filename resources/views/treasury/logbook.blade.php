@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-blocker" style="display: {{ $blocker }};">
            <div class="cut-off-message">
                <span class="page-blocker-head">Cannot access this page please wait until tomorrow</span>
                <div class="button-wrap">
                    <a class="btn btn-outline-light btn-lg" href="{{ route('trlogbookmonthlist') }}">Go back home</a>
                </div>
                {{--<p class="page-blocker-content">00:00:00</p>--}}
            </div>
        </div>
        <div class="content-header">
            <h4>Sales date of {{ $carbon_sales_date->format('F d, Y') }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('trlogbookmonthlist') }}">Sales month</a></li>
                <li class="breadcrumb-item"><a href="{{ route('trdaylist', ['date'=>$sales_date]) }}">Sales day</a></li>
                <li class="breadcrumb-item active" aria-current="page">Logbook</li>
            </ol>
        </div>

        <form id="logbook-form-submit" action="{{ route('trlogbooksubmit') }}" method="post">
            @csrf
            <div class="row mb-2">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header card-vcenter">
                            <span class="vcenter">Logbook details</span>
                            <button type="button" data-toggle="collapse" data-target="#collapse-top-card" aria-expanded="false" aria-controls="collapse-top-card" class="btn btn-outline-secondary btn-sm float-right minimize-btn btn-collapse"><i class="fa fa-minus"></i></button>
                        </div>
                        <div class="collapse show" id="collapse-top-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">Deposit Date </label>
                                            <input name="datedeposit" id="datedeposit" type="text" class="form-control{{ $errors->has('datedeposit') ? ' is-invalid' : '' }}" readonly>
                                            <input name="sales_date" type="hidden" value="{{ $sales_date }}">
                                            @if ($errors->has('datedeposit'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first() }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="currency">Currency</label>
                                            <select id="currency" name="currency" class="form-control{{ $errors->has('currency') ? ' is-invalid' : '' }}">
                                                <option value="0">( Select currency )</option>
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency->currency_id }}">{{ $currency->currency_name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('currency'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first() }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="bankaccounts">Select Bank</label>
                                            <select name="bankaccounts" id="bankaccounts" class="form-control{{ $errors->has('bankaccounts') ? ' is-invalid' : '' }}" data-url="{{ route('trlogbookgetunits') }}" data-sales="{{ $sales_date }}">
                                                <option value="0">( Select bank account )</option>
{{--                                                @foreach($login_user->bank as $b)--}}
                                                @foreach($bankaccess as $b )
                                                <option value="{{ $b->bank->id }}">{{ $b->bank->bank.'-'.$b->bank->accountno.'-'.$b->bank->accountname }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('bankaccounts'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first() }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-2">
                @include('treasury.logbook.logbook_check')
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header card-vcenter">
                            <span class="vcenter">Cash Pullout</span>
                            <button type="button" class="btn btn-primary btn-oblong btn-blue" data-toggle="modal" data-target=".add-cash-pull-out-modal">Add cash pull out</button> <button type="button" data-toggle="collapse" data-target="#collapse-pullout" aria-expanded="false" aria-controls="collapse-pullout" class="btn btn-outline-secondary btn-sm float-right minimize-btn btn-collapse"><i class="fa fa-minus"></i></button></div>
                        <div class="collapse show" id="collapse-pullout">
                            <div class="card-body">
                                <span class="total-no-sm" style="display: none;">0</span>
                                <table id="cpo-table" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($cpos as $cpo)
                                            <tr id="cpo-data-{{ $cpo->id }}" class="cpo-data-class-{{ $cpo->id }}">
                                                <td>{{ $cpo->department }}</td>
                                                <td>{{ number_format($cpo->amount,2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td></td>
                                            <td>Total: <span class="cpo-total">{{ number_format($cpos->sum('amount'),2) }}</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('treasury.logbook.logbook_cash')
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Operation</div>

                        <div class="card-body">
                            <input type="hidden" name="url_sales_date" value="{{ $sales_date }}">
                            <button type="submit" class="btn btn-primary btn-oblong btn-blue">Submit data fields</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>


        <div class="row last-row">
            <div class="col-md-12">

                @include('treasury.logbook.modal.check_detail')
                @include('treasury.logbook.modal.cashier_list')
                @include('treasury.logbook.modal.cashier_details')

                <div class="modal fade add-cash-pull-out-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form id="cash-pull-out-form" method="POST" action="{{ route('traddcpo') }}">
                                @csrf
                                <input type="hidden" name="sales_date" value="{{ $sales_date }}">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Cash pullout form</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        {{--<div class="col-sm-6">--}}
                                            {{--<div class="form-group">--}}

                                                {{--<label for="name-sb" class="col-form-label">Name:</label>--}}
                                                {{--<input type="text" placeholder="Full name" class="form-control" id="name-sb">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-6">--}}
                                            {{--<div class="form-group">--}}

                                                {{--<label for="tcpof-sb" class="col-form-label">TCPOF:</label>--}}
                                                {{--<input type="text" placeholder="Full name" class="form-control" id="tcpof-sb" readonly>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="department-sb" class="col-form-label">Dept./Section:</label>
                                                <select name="department" type="number" min="0" class="form-control" id="department-sb">
                                                    <option value="0">( Select department )</option>
                                                    <option value="forex">Forex</option>
                                                    <option value="western-union">Western Union</option>
                                                    <option value="salary-pullout">Salary Pull Out</option>
                                                    <option value="palay">Palay</option>
                                                </select>
                                            </div>
                                        </div>
                                        {{--<div class="col-sm-6">--}}
                                            {{--<div class="form-group">--}}

                                                {{--<label for="department-sb" class="col-form-label">Date:</label>--}}
                                                {{--<input type="text" placeholder="Full name" class="form-control" readonly>--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="amount-sb" class="col-form-label">Amount</label>
                                                <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" im-insert="true" name="amount" type="text" min="0" class="form-control amount-change" id="amount-sb">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label for="purpose-sb" class="col-form-label">Purpose</label>
                                                <textarea class="form-control" name="purpose" id="purpose-sb" cols="20" rows="5"></textarea>
                                            </div>
                                        </div>
                                        {{--<div class="col-sm-12">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="purpose-sb" class="col-form-label">Requested by:</label>--}}
                                                {{--<input type="text" class="form-control">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-12">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="purpose-sb" class="col-form-label">Approved by:</label>--}}
                                                {{--<input type="text" class="form-control">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                        {{--<div class="col-sm-12">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label for="purpose-sb" class="col-form-label">Released by:</label>--}}
                                                {{--<input type="text" class="form-control">--}}
                                            {{--</div>--}}
                                        {{--</div>--}}
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-oblong btn-blue">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{--<div class="modal fade bu-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">--}}
                    {{--<div class="modal-dialog modal-lg">--}}
                        {{--<div class="modal-content">--}}
                            {{--<form action="{{ route('traddbu') }}" id="add_bu_form" method="POST">--}}
                                {{--@csrf--}}
                                {{--<div class="modal-header">--}}
                                    {{--<h5 class="modal-title" id="exampleModalLabel">Select department</h5>--}}
                                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                                        {{--<span aria-hidden="true">&times;</span>--}}
                                    {{--</button>--}}
                                {{--</div>--}}
                                {{--<div class="modal-body">--}}
                                    {{--<input name="sales_date" type="hidden" value="{{ $sales_date }}">--}}
                                    {{--<table id="busitem_cb" class="table table-bordered table-hover table-select-bu">--}}
                                        {{--<thead>--}}
                                            {{--<tr>--}}
                                                {{--<td>--}}
                                                    {{--<input type="checkbox" id="checkallbus" />--}}
                                                {{--</td>--}}
                                                {{--<td>BUSINESS UNIT</td>--}}
                                            {{--</tr>--}}
                                        {{--</thead>--}}
                                        {{--<tbody>--}}

                                        {{--@foreach($deptBUSection as $d)--}}
                                            {{--<tr>--}}
                                                {{--<td>--}}
                                                    {{--<input type="checkbox" name="bus[]" value="{{ $d->company_code.'|'.$d->bunit_code.'|'.$d->dept_code.'|'.$d->section_code.'|'.$d->sub_section_code }}">--}}
                                                {{--</td>--}}
                                                {{--<td>--}}
                                                    {{--@if($d->sub_section_name)--}}
                                                        {{--{{ $d->sub_section_name }}--}}
                                                        {{--<input type="hidden" name="buname[]" value="{{ $d->sub_section_name }}">--}}
                                                    {{--@elseif($d->section_name)--}}

                                                            {{--{{ $d->section_name }}--}}
                                                        {{--<input type="hidden" name="buname[]" value="{{ $d->section_name }}">--}}

                                                    {{--@elseif($d->dept_name)--}}
                                                        {{--{{ $d->dept_name }}--}}
                                                        {{--<input type="hidden" name="buname[]" value="{{ $d->dept_name }}">--}}
                                                    {{--@elseif($d->business_unit)--}}
                                                        {{--{{ $d->business_unit }}--}}
                                                        {{--<input type="hidden" name="buname[]" value="{{ $d->business_unit }}">--}}
                                                    {{--@endif--}}
                                                {{--</td>--}}
                                            {{--</tr>--}}
                                        {{--@endforeach--}}
                                        {{--</tbody>--}}
                                    {{--</table>--}}

                                {{--</div>--}}
                                {{--<div class="modal-footer">--}}
                                    {{--<button type="submit" class="btn btn-light" id="add_bu">Add</button>--}}
                                {{--</div>--}}
                            {{--</form>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}

                <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add DS #</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form onsubmit="event.preventDefault();">
                                    <div class="form-group">
                                        <label for="ds_number" class="col-form-label">Input DS #:</label>
                                        <input name="ds_number" type="number" min="0" class="form-control" id="ds_number">
                                    </div>
                                    <div class="form-group">
                                        <label for="multiple-amount" class="col-form-label">Amount:</label>
                                        <input name="multiple-amount" type="number" min="0" class="form-control" id="multiple-amount">
                                        <input type="hidden" name="trId">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light btn-oblong" id="add_sm_ds">Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade add-adj-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md">
                        <div class="modal-content">
                            <form id="add-manual-cash-adj" method="POST" action="{{ route('tr_add_manual_cash_adj') }}" onsubmit="event.preventDefault();">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Add</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                        <div class="form-group">
                                            <label for="manual_sales_date" class="col-form-label">Type</label>
                                            <select name="status_adj" id="" class="form-control">
                                                <option value="0">( Select Type )</option>
                                                {{--<option value="check-to-cash">Check to cash</option>--}}
                                                <option value="short">Short</option>
                                                <option value="over">Over</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="manual_sales_date" class="col-form-label">Sales date:</label>
                                            <input name="sales_date" type="text" class="form-control" id="manual_sales_date" autocomplete="off" readonly>
                                        </div>
                                        <div class="form-group bu-sb">
                                            <label for="bu_sb" class="col-form-label">Select bu:</label>
                                            <select name="bu_sb" id="bu_sb" class="form-control">
                                                <option value="0">( Select business unit )</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ds_number" class="col-form-label">Input DS #:</label>
                                            <input name="ds_number" type="text" min="0" class="form-control" id="ds_number">
                                        </div>
                                        <div class="form-group">
                                            <label for="amount" class="col-form-label">Amount</label>
                                            <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" im-insert="true" name="amount" type="text" min="0" class="form-control amount-change" id="amount">
                                        </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-oblong btn-blue" id="add_sm_ds">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datepicker3.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/inputmask.css') }}" />
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/css/responsive.bootstrap4.min.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/bootstrapValidator.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.inputmask.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/datepicker.limit.selection.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/logbook.js') }}" type="text/javascript"></script>
    <script>
        $(".amount-change").inputmask();

        $('#amount-change-23421231').on('keyup', function () {
            var total = 0;
            var amount = $("input[name='amount[]']")
                .map(function(){return $(this).val();}).get();

            var i;
            for (i = 0; i < amount.length; i++) {

                if (amount[i].length>0) {
                    amount[i] = amount[i].replace(/\,/g,'');
                    if (!isNaN(amount[i])) {
                        total = parseFloat(total) + parseFloat(amount[i]);
                    }
                }

            }

            $('#totalCash').text(String(total.toFixed(2)).replace(/\B(?=(\d{3})+(?!\d))/g,','));

        });
    </script>
@endpush