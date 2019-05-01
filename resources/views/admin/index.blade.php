@extends('admin.layout.app')
@section('crumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">TMS</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="widget-simple-chart text-right card-box">
                <h3 class="text-success counter m-t-10">{{ number_format($total_sales_today,2) }}</h3>
                <p class="text-muted text-nowrap m-b-10">Total Sales today</p>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="widget-simple-chart text-right card-box">
                <h3 class="text-primary counter m-t-10">{{ number_format($latest_total_deposited, 2) }}</h3>
                <p class="text-muted text-nowrap m-b-10">Latest total deposit</p>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="widget-simple-chart text-right card-box">
                <div class="circliful-chart" data-dimension="90" data-text="{{ round($month_percent) }}%" data-width="5" data-fontsize="14" data-percent="{{ round($month_percent) }}" data-fgcolor="#f76397" data-bgcolor="#ebeff2"></div>
                <h3 class="text-pink m-t-10">{{ number_format($month_total_sales, 2) }}</h3>
                <p class="text-muted text-nowrap m-b-10">Total sales this month</p>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="widget-simple-chart text-right card-box">
                <div class="circliful-chart" data-dimension="90" data-text="{{ round($year_percent) }}%" data-width="5" data-fontsize="14" data-percent="{{ round($year_percent) }}" data-fgcolor="#98a6ad" data-bgcolor="#ebeff2"></div>
                <h3 class="text-inverse counter m-t-10">{{ number_format($year_total_sales, 2) }}</h3>
                <p class="text-muted text-nowrap m-b-10">Total sales this year</p>
            </div>
        </div>
    </div>
    <!-- end row -->

    {{--<div class="row">--}}
        {{--<div class="col-xl-4">--}}
            {{--<div class="card-box">--}}
                {{--<h4 class="text-dark  header-title m-t-0 m-b-30">Total Revenue</h4>--}}

                {{--<div class="widget-chart text-center">--}}
                    {{--<div id="sparkline1"></div>--}}
                    {{--<ul class="list-inline m-t-15 mb-0">--}}
                        {{--<li>--}}
                            {{--<h5 class="text-muted m-t-20">Target</h5>--}}
                            {{--<h4 class="m-b-0">$56,214</h4>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<h5 class="text-muted m-t-20">Last week</h5>--}}
                            {{--<h4 class="m-b-0">$98,251</h4>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<h5 class="text-muted m-t-20">Last Month</h5>--}}
                            {{--<h4 class="m-b-0">$10,025</h4>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
            {{--</div>--}}

        {{--</div>--}}

        {{--<div class="col-xl-4">--}}
            {{--<div class="card-box">--}}
                {{--<h4 class="text-dark  header-title m-t-0 m-b-30">Yearly Sales Report</h4>--}}

                {{--<div class="widget-chart text-center">--}}
                    {{--<div id="sparkline2"></div>--}}
                    {{--<ul class="list-inline m-t-15 mb-0">--}}
                        {{--<li>--}}
                            {{--<h5 class="text-muted m-t-20">Target</h5>--}}
                            {{--<h4 class="m-b-0">$1000</h4>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<h5 class="text-muted m-t-20">Last week</h5>--}}
                            {{--<h4 class="m-b-0">$523</h4>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<h5 class="text-muted m-t-20">Last Month</h5>--}}
                            {{--<h4 class="m-b-0">$965</h4>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
            {{--</div>--}}

        {{--</div>--}}

        {{--<div class="col-xl-4">--}}
            {{--<div class="card-box">--}}
                {{--<h4 class="text-dark header-title m-t-0 m-b-30">Weekly Sales Report</h4>--}}

                {{--<div class="widget-chart text-center">--}}
                    {{--<div id="sparkline3"></div>--}}
                    {{--<ul class="list-inline m-t-15 mb-0">--}}
                        {{--<li>--}}
                            {{--<h5 class="text-muted m-t-20">Target</h5>--}}
                            {{--<h4 class="m-b-0">$1,84,125</h4>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<h5 class="text-muted m-t-20">Last week</h5>--}}
                            {{--<h4 class="m-b-0">$50,230</h4>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<h5 class="text-muted m-t-20">Last Month</h5>--}}
                            {{--<h4 class="m-b-0">$87,451</h4>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</div>--}}
            {{--</div>--}}

        {{--</div>--}}

    {{--</div>--}}
    <!-- end row -->
@endsection

@push('styles')
<link href="{{ asset('admin/plugins/jquery-circliful/css/jquery.circliful.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
@endpush

@push('scripts')
<!-- Counter Up  -->
<script src="{{ asset('admin/plugins/waypoints/lib/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('admin/plugins/counterup/jquery.counterup.min.js') }}"></script>
<!-- circliful Chart -->
<script src="{{ asset('admin/plugins/jquery-circliful/js/jquery.circliful.min.js') }}"></script>
<!-- Custom main Js -->
<script src="{{ asset('admin/assets/js/jquery.core.js') }}"></script>
<script src="{{ asset('admin/assets/js/jquery.app.js') }}"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('.counter').counterUp({
            delay: 100,
            time: 1200
        });
        $('.circliful-chart').circliful();
    });
</script>
@endpush