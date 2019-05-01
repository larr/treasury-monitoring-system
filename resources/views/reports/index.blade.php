@extends('admin.layout.app')

@section('crumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">TMS</a></li>
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="widget-bg-color-icon card-box">
                <div class="bg-icon bg-icon-success pull-left">
                    <a href="{{ route('tr.reports.logbook') }}"><i class="mdi mdi-receipt text-success"></i></a>
                </div>
                <div class="text-right">
                    <h3 class="text-dark m-t-10">Logbook</h3>
                    {{--<h3 class="text-dark m-t-10"><b class="counter">64,570</b></h3>--}}
                    <p class="text-muted mb-0">sales and deposit</p>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection