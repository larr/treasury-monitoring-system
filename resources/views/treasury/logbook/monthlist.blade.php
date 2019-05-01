@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of sales per month</div>

                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap datatable">
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
@push('styles')
    {{--<link rel="stylesheet" href="{{ asset('plugins/datatables/css/dataTables.bootstrap4.min.css') }}">--}}
    {{--<link rel="stylesheet" href="{{ asset('plugins/datatables/css/responsive.bootstrap4.min.css') }}">--}}
@endpush
@push('scripts')
    {{--<script type="text/javascript" src="{{ asset('plugins/datatables/js/jquery.dataTables.min.js') }}"></script>--}}
    {{--<script type="text/javascript" src="{{ asset('plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>--}}
    {{--<script type="text/javascript" src="{{ asset('plugins/datatables/js/dataTables.responsive.min.js') }}"></script>--}}
    {{--<script type="text/javascript" src="{{ asset('plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>--}}
    {{--<script>--}}
        {{--$('.datatable').DataTable({--}}
            {{--"lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],--}}
            {{--"pageLength": 5,--}}
            {{--"ordering":false,--}}
        {{--});--}}
    {{--</script>--}}
@endpush