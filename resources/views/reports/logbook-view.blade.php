@extends('admin.layout.app')

@section('content')
    <div class="container">
        {{--<h3>Deposit date {{ $deposit_date->format('F d, Y') }}</h3>--}}
        {{--<h3>Deposit date</h3>--}}
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-2">
                    @if(!is_null($sm_br->sm_br))
                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    supermarket amount deduction summary
                                </div>
                                <div class="card-body">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>
                                                Liquidation inputed amount
                                            </td>
                                            <td>
                                                {{ number_format($total_liquidation_input,2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Less</th>
                                        </tr>
                                        <tr>
                                            <td>
                                                Pdc total
                                            </td>
                                            <td>
                                                {{ number_format($pdc_deducted_to_sm,2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Dated checks total
                                            </td>
                                            <td>
                                                {{ number_format($dated_check_total_deducted_to_sm,2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Cash pull out total
                                            </td>
                                            <td>
                                                {{ number_format($sm_br->sm_br->cash_pullout_total,2) }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="card">
                                <div class="card-header">
                                    Cash pull out
                                </div>
                                <div class="card-body">
                                    <h5>{{ $cashLogs[0]->sales_date->format('F d, Y') }} sales date</h5>
                                    <table class="table table-hover">
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
                                                {{ number_format($sm_br->sm_br->due_checks_total,2) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                PDC
                                            </td>
                                            <td>
                                                {{ number_format($sm_br->sm_br->pdc_total,2) }}
                                            </td>
                                        </tr>
                                        @foreach($cpo as $c)
                                            <tr>
                                                <td>{{ strtoupper($c->department) }}</td>
                                                <td>{{ number_format($c->amount,2) }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>Total:</td>
                                            <td>{{ number_format($totalPullOut,2) }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card">
                    <div class="card-header">Log book</div>

                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Business units / Manual</th>
                                <th>Sales Date</th>
                                <th>Deposit Date</th>
                                <th>DS Number</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cashLogs as $cashlog)
                                <tr>
                                    <td>
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

                                    </td>
                                    <td>
                                        {{ $cashlog->sales_date->format('F d, Y') }}
                                    </td>
                                    <td>
                                        {{ $cashlog->deposit_date->format('F d, Y') }}
                                    </td>
                                    <td>
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
                                        {{--{{ $cashlog->ds->ds_number }}--}}
                                    </td>
                                    <td>
                                        {{number_format($cashlog->amount,2)}}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <span class="font-weight-bold">TOTAL: {{ number_format($totalCash,2) }}</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <button class="btn btn-outline-default" id="printData">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('treasury.reports.logbook-print')
    {{-- Report Printing --}}
@endsection

@push('scripts')
    <script src="{{ asset('js/jQuery.print.js') }}" ></script>
    <script>
        $("#printData").click(function(){
            $('#dataCashlog').print();
        });


    </script>

@endpush