@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @foreach($cpo as $c)
                <div class="card mb-4">
                    <div class="card-header">
                        Cash pullout
                    </div>
                    <div class="card-body">

                        <h5 class="text-center text-uppercase">treasury</h5>
                        <h5 class="text-center text-uppercase">temporary cash pullout form</h5>
                        <div class="wrapper-container">
                            <div class="wrapper text-left">
                                <div class="row">
                                    <div class="col-sm-8">
                                        Name:_________________________
                                    </div>
                                    <div class="col-sm-4">
                                        TCPOF: 234234
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-8">
                                        Dept./Section: {{ strtoupper($c->department) }}
                                    </div>
                                    <div class="col-sm-4">
                                        Date: {{ $c->pull_out_date->format('F d, Y') }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="hidden" class="amount" value=" {{ $c->amount }}">
                                        Amount in words: <span class="amount-words"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        Purpose: {{ $c->purpose }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>Requested by:_____________________________________________</p>
                                        <p>Printed Name & Signature</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>Approved by:_____________________________________________</p>
                                        <p>Printed Name & Signature</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>Release by:_____________________________________________</p>
                                        <p>Printed Name & Signature</p>
                                    </div>
                                </div>
                                <p>Note: The amount borrowed must be refunded five (5) days, failure to do so would mean insubordination and accountable for it.</p>
                            </div>
                        </div>

                        {{--<table class="table table-hover">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th>Type</th>--}}
                                {{--<th>Amount</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                        {{--</table>--}}

                    </div>
                </div>
                @endforeach
                    <button class="btn btn-outline-secondary" id="printData">Print</button>
            </div>
        </div>
    </div>

    {{-- Report Printing --}}
    <div style="display: none">
    {{--<div>--}}
        <div id="dataCashlog">
            @foreach($cpo as $c)
            <table class="cpo-table mb-5">
                <thead>
                    <tr>
                        <td colspan="2">{{ $user->businessunit->company->company }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">treasury</th>
                    </tr>
                    <tr>
                        <th colspan="2">treasury cash pullout form</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name:</td>
                        <td>TCPOF:</td>
                    </tr>
                    <tr>
                        <td>Dept./Section: {{ strtoupper($c->department) }}</td>
                        <td>Date: {{ $c->pull_out_date->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div>
                                <input type="hidden" class="amount" value=" {{ $c->amount }}">
                                Amount in words: <span class="amount-words"></span> (Php {{ $c->amount }})
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Purpose: {{ $c->purpose }}</td>
                    </tr>
                    <tr>
                        <td>Requested by:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="pl-85">Printed Name & Signature</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Approved by:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="pl-85">Printed Name & Signature</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Released by:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class="pl-85">Printed Name & Signature</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            Note: The amount borrowed must be refunded five (5) days, failure to do so would mean insubordination and accountable for it.
                        </td>
                    </tr>
                </tbody>
            </table>
            @endforeach
            {{--<div style="width:100%;text-align:center;padding:10px">--}}
                {{--{{\Illuminate\Support\Facades\Auth::user()->businessunit->company->company}}--}}
            {{--</div>--}}
            {{--<div style="width:100%;text-align:center;padding:10px">--}}
                {{--{{\Illuminate\Support\Facades\Auth::user()->businessunit->bname}}--}}
            {{--</div>--}}
            {{--<div style="width:100%;text-align:center;padding:10px">--}}
                {{--Bank: {{$bank->bank.'-'.$bank->accountno.'-'.$bank->accountname}}--}}
            {{--</div>--}}
            {{--<div style="width:100%;text-align:center;padding:10px">--}}
                {{--Currency: {{ $currency->currency_name }}--}}
            {{--</div>--}}
            {{--<div style="width:100%;padding:10px">--}}
                {{--Deposit Date: {{ $depositDate->format('F d, Y') }}--}}
            {{--</div>--}}
        </div>

    </div>
@endsection

@push('styles')
    <style>

    </style>
@endpush

@push('scripts')
    <script src="{{ asset('plugins/num2words/jquery.num2words.js') }}" ></script>
    <script src="{{ asset('js/jQuery.print.js') }}" ></script>
    <script>
        $("#printData").click(function(){
            $('#dataCashlog').print();
        });

        $('.amount-words').text($('.amount').AmountInWords());

        $(document).ready(function () {

            $('.amount-words').each(function(i, obj) {
                var amount = $(this).closest('div').find('.amount').AmountInWords();
                $(this).text(amount);
//                console.log(amount);
            });

        });

    </script>
@endpush