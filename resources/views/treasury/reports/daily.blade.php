@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Logbook reports</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Lists of deposit / day</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($daily as $key  => $day)
                                <tr>
                                    <td>
                                        {{ $day->dateT->format('F d, Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('trreportviewreport',[
                                        'bankId' => $bankid,
                                        'dateT' => $day->dateT->format('Y-m-d'),
                                        'report_type' => 'sales'
                                        ]) }}" class="btn btn-outline-primary">
                                            View Logbook report
                                        </a>
                                        {{--<a href="{{ route('trreportviewcporeport',[$bankid, $day->deposit_date]) }}" class="btn btn-outline-primary">--}}
                                            {{--View Cash pullout report--}}
                                        {{--</a>--}}
                                    </td>
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