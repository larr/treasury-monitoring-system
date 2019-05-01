@extends('admin.layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card-box table-responsive">
                <table id="datatable" class="table table-hover">
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
                                <a href="{{ route('tr.reports.logbook.day', [
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
            "lengthMenu": [[8, 10, 50, -1], [8, 10, 50, "All"]],
            "pageLength": 8,
        });
        $('#reports').addClass('active');
        $('#logbook').addClass('active');
    </script>
@endpush