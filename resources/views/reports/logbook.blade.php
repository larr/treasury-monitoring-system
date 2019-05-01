@extends('admin.layout.app')
@section('crumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">TMS</a></li>
    <li class="breadcrumb-item active">Reports</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card-box table-responsive">
                <table id="datatable" class="table table-hover">
                    <thead>
                    <tr>
                        <th>Banks</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banks as $bank)
                        <tr>
                            <td>
                                {{ $bank['bank'].'-'.$bank['accountno'].'-'.$bank['accountname'] }}
                            </td>
                            <td>
                                <a href="{{ route('tr.reports.logbook.month', [
                                        'report_type' => 'sales',
                                        'bankId' => $bank['id']
                                        ]) }}" class="btn btn-outline-primary">
                                    View report by sales date
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