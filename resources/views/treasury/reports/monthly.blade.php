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
                                <th>List of {{ $request->report_type }} / months</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($monthly as $key  => $month)
                                <tr>
                                    <td>
                                        {{ $month->dateT->format('F Y') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('trreportdaily', [
                                        'bankId' => $bankid,
                                        'dateT' => $month->dateT->format('Y-m-d'),
                                        'report_type' => 'sales'
                                        ]) }}" class="btn btn-outline-primary">
                                            View
                                        </a>
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