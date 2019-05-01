@extends('admin.layout.app')

@section('crumb')
    <li class="breadcrumb-item"><a href="{{ route('home') }}">TMS</a></li>
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <table id="datatable" class="table table-bordered dataTable table-hover no-footer">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Privilege</th>
                        <th>Business unit</th>
                        <th><i class="ti-list"></i></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="modal fade role-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="form" action="{{ route('tr.user.assign') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h4 class="modal-title">User access for user <span class="user-name badge badge-primary"></span></h4>

                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div id="roles"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-secondary waves-effect">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!-- /.modal -->
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
    <script src="{{ asset('js/users.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        $('#datatable').DataTable({
            'ajax' : "{{route('tr.users')}}",
            ordering:false,
            "lengthMenu": [[8, 10, 50, -1], [8, 10, 50, "All"]],
            "pageLength": 8,
        });
    </script>
@endpush