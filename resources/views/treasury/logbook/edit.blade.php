@extends('layouts.app')

@section('content')

    <div class="container">
        <h4>Deposit date: {{ $deposit_date->format('F d, Y') }}</h4>
        <h6>Bank account: {{ $bank->bank.'-'.$bank->accountno.'-'.$bank->accountname }}</h6>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Log book history</div>

                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Business units / Others</th>
                                <th>Sales Date</th>
                                <th>DS Number</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($logbook_items as $logbook_item)
                                    <tr>
                                        <td>{{ $logbook_item->logbook_desc }}</td>
                                        <td>{{ $logbook_item->sales_date->format('M d, Y') }}</td>
                                        <td>
                                            @foreach($logbook_item->ds as $ds)
                                                {{ $ds->ds_number }}
                                            @endforeach
                                        </td>
                                        <td>{{ $logbook_item->amount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush