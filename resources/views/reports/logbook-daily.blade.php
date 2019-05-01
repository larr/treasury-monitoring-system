@extends('admin.layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card-box">
                <table id="datatable" class="table table-hover dt-responsive nowrap">
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
                                <a href="{{ route('tr.reports.logbook.view',[
                                'bankId' => $bankid,
                                'dateT' => $day->dateT->format('Y-m-d'),
                                'report_type' => 'sales'
                                ]) }}" class="btn btn-md btn-outline-primary">
                                    View Logbook report
                                </a>
                                <a href="javascript:void(0)" class="btn btn-outline-primary">
                                    Download Excel Report
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/datatables/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
@endpush
@push('scripts')
    <script src="{{ asset('admin/plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin/plugins/datatables/dataTables.bootstrap4.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $('#datatable').DataTable({
            ordering:false,
            "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
            "pageLength": 5,
        });
        $('#reports').addClass('active');
        $('#logbook').addClass('active');
    </script>
@endpush