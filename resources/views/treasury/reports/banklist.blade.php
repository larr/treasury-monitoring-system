@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Logbook reports: Select bank</div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Banks</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($banks as $key  => $bank)
                                <tr>
                                    <td>
                                        {{ $bank['bank'].'-'.$bank['accountno'].'-'.$bank['accountname'] }}
                                    </td>
                                    <td>
                                        <a href="{{ route('trreportmonthly', [
                                        'report_type' => 'sales',
                                        'bankId' => $bank['id']
                                        ]) }}" class="btn btn-outline-primary">
                                            View report by sales date
                                        </a>
                                        <button href="{{ route('trreportmonthly', [$bank['id']]) }}" class="btn btn-outline-primary" disabled>
                                            View report by deposit date
                                        </button>
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