<div style="display: none;">
{{--<div>--}}
    <div id="dataCashlog">
        <div class="report-block">
            {{ $login_user->businessunit->company->company}}
        </div>
        <div class="report-block">
            {{ $login_user->businessunit->bname}}
        </div>
        <div class="report-block">
            Bank: {{$bank->bank.'-'.$bank->accountno.'-'.$bank->accountname}}
        </div>
        <div class="report-block">
            Currency: {{ $currency->currency_name }}
        </div>
        <div class="block-wrap">
            Sales Date: {{ date('F d Y', strtotime($salesDate)) }}
        </div>
        <div class="block-wrap mb-2">
            Deposit Date: {{ $depositDate->format('F d, Y') }}
        </div>
        <div style="float: left;" class="wrap-50">
                @if($sm_br)
                    <table class="sm-tb mb-3">
                        <thead>
                        <tr>
                            <th colspan="2">
                                SUPERMARKET AMOUNT DEDUCTION SUMMARY
                            </th>
                        </tr>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>LIQUIDATION INPUTED AMOUNT</td>
                            <td>{{ number_format($sm_br->sm_br->liq_input_amount,2) }}</td>
                        </tr>
                        <tr>
                            <td>PDC TOTAL</td>
                            <td>{{ number_format($sm_br->sm_br->pdc_total,2) }}</td>
                        </tr>
                        <tr>
                            <td>DATED CHECKS TOTAL</td>
                            <td>{{ number_format($sm_br->sm_br->due_checks_total,2) }}</td>
                        </tr>
                        <tr>
                            <td>CASH PULLOUT TOTAL</td>
                            <td>{{ number_format($sm_br->sm_br->cash_pullout_total,2) }}</td>
                        </tr>
                        <tr>
                            <td>SUPERMARKET NET AMOUNT</td>
                            <td>{{ number_format($sm_br->amount,2) }}</td>
                        </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        <div style="float: right;" class="wrap-50">
                @if($sm_br)
                    <table class="sm-tb mb-3">
                        <thead>
                        <tr>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                CASH FOR DEPOSIT
                            </td>
                            <td>
                                {{ number_format($totalCash,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                DATED CHECKS
                            </td>
                            <td>
                                {{ number_format($totalDatedCheck,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                PDC
                            </td>
                            <td>
                                {{ number_format($pdc_total,2) }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                DUE
                            </td>
                            <td>
                                {{ number_format($due_checks,2) }}
                            </td>
                        </tr>
                        @foreach($cpo as $c)
                            <tr>
                                <td>{{ strtoupper($c->department) }}</td>
                                <td>{{ number_format($c->amount,2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>TOTAL DEPOSIT:</td>
                            <td>{{ number_format($totalDeposit,2) }}</td>
                        </tr>
                        </tbody>
                    </table>
                @endif
            </div>
        <div class="clearfix"></div>
        <table class="" id="dataCashlog">
            <thead>
            <tr>
                <th>Business Units</th>
                <th>Sales date</th>
                <th>Deposit date</th>
                <th>DS Number</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cashLogs as $cashlog)
                <tr>
                    <td>
                            <span style="font-size: 20px;">
                                @if($cashlog->logbook_desc)
                                    {{ $cashlog->logbook_desc }}
                                    @if(strtolower($cashlog->logbook_desc) === 'admin: ar others')
                                        {{$cashlog->ar_from ." to ". $cashlog->ar_to}}
                                    @else

                                    @endif
                                    @if($cashlog->status_adj == 'post-deposit')
                                        amount from other sales date
                                    @endif
                                @else
                                    {{ $cashlog->logbook_desc }}
                                    @if($cashlog->status_adj == 'check-to-cash')
                                        - Check converted to cash
                                    @endif
                                @endif
                            </span>

                    </td>
                    <td style="font-size: 20px;">
                        {{ $cashlog->sales_date->format('M d, Y') }}
                    </td>
                    <td style="font-size: 20px;">
                        {{ $cashlog->deposit_date->format('F d, Y') }}
                    </td>
                    <td>
                            <span style="font-size: 20px;">
                                {{--@foreach($cashlog->ds as $ds)--}}
                                {{--{{ $ds->ds_number }}&nbsp;--}}
                                {{--@endforeach--}}

                                @if(count($cashlog->ds)>1)
                                    <table>
                                            <thead>
                                                <tr>
                                                    <th>DS #</th>
                                                    <th>AMOUNT</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($cashlog->ds as $ds)
                                                <tr>
                                                    <td>{{ $ds->ds_number }}</td>
                                                    <td>{{ number_format($ds->amount,2) }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                @else
                                    @foreach($cashlog->ds as $ds)
                                        {{ $ds->ds_number }}&nbsp
                                    @endforeach
                                @endif
                            </span>
                    </td>
                    <td style="text-align:right">
                            <span style="font-size: 25px;">
                            {{number_format($cashlog->amount,2)}}
                            </span>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="5" style="font-size: 1rem;">
                    <span class="float-right" style="font-size: 20px;">TOTAL CASH: {{ number_format($totalCash,2) }}</span>
                </td>
            </tr>
            </tbody>
        </table>


        <table style="width:100%">
            <tr>
                <th style="padding:10px;width:50%">
                    PREPARED & DEPOSITED BY:
                </th>
                <th style="padding:10px;width:40%">
                    <p style="margin-left:253px;">
                        DEPOSIT VERIFIED BY:
                    </p>

                </th>
            </tr>
            <tr>
                <td>
                    <p style="text-decoration: underline">
                        {{ \Illuminate\Support\Facades\Auth::user()->firstname ." ". \Illuminate\Support\Facades\Auth::user()->lastname }}
                    </p>
                    <p>
                        {{\Illuminate\Support\Facades\Auth::user()->businessunit->bname}} TREASURY
                    </p>
                </td>
                <td style="padding:10px;width:40%">
                    <p style="margin-left:243px">
                        ______________________________
                    </p>
                    <p style="margin-left:253px">
                        BANK REPRESENTATIVE
                    </p>
                </td>
            </tr>

        </table>

    </div>

</div>