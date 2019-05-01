@extends('admin.layout.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <button data-action="{{ route('tr.admin.create.bubank') }}" id="add-bu-bank" class="btn btn-primary mb-4" data-toggle="modal" data-target="#exampleModal">Add</button>
                <div class="table-responsive">
                    <table id="datatable" class="table">
                        <thead>
                            <tr>
                                <th>Business units</th>
                                <th>Bank accounts</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form action="{{ route('tr.admin.bubank.store') }}" method="post">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Add bank access</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <select data-url="{{ route('tr.admin.selectbank') }}" name="bu" id="bu" class="form-control">
                                        <option value="0">( Select business unit )</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select name="bank" id="bank" class="form-control bank">
                                        <option value="0">( Select bank account )</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
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

    <script src="{{ asset('admin/assets/js/jquery.core.js') }}"></script>
    <script src="{{ asset('admin/assets/js/jquery.app.js') }}"></script>

    <script type="text/javascript">
        var dt = $('#datatable').DataTable({
            'ajax' : "{{route('tr.admin.addbank')}}",
            'ordering':false,
//            "lengthMenu": [[8, 10, 50, -1], [8, 10, 50, "All"]],
//            "pageLength": 8,
        });
        $('#setting').addClass('active');
        $('#addbank').addClass('active');

        $('#add-bu-bank').on('click', function (e) {
            e.preventDefault();

            var url = $(this).attr('data-action');

            $.ajax({
                url:url,
                success: function (res) {
                    $('#bu').append(res);
                },
                error: function (error) {
                    console.log('error');
                }
            });

        });

        $('#bu').on('change', function (e) {
            var url = $(this).attr('data-url'),
                id = $(this).val();
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                url:url,
                method:'POST',
                data: {id:id},
                success: function (res) {
                    $('.bank').find('option').remove('.newly-added');
                    $('.bank').append(res)
                },
                error: function (error) {
                    console.log('error');
                }
            });
        });

        $('form').submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                data:data,
                method:'POST',
                success: function (res) {
                    dt.ajax.reload(null, false);
                }
            });
        });

        $('table').on('click', '.remove',function (e) {
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                url: $(this).attr('data-url'),
                method:'DELETE',
                success: function (res) {
                    dt.ajax.reload(null, false);
                }
            });
        });

    </script>
@endpush