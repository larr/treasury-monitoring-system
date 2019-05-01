@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of sales per month</div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Month</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($months as $month)
                            <tr>
                                <td>{{ $month->sales_date->format('F Y') }}</td>
                                <td>
                                    <a href="{{ route('trdaylist', ['date'=>$month->sales_date->format('Y-m-d')]) }}" class="btn btn-primary btn-oblong btn-blue">View</a>
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