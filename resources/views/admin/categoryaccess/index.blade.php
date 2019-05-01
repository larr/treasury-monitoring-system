@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of cash categories <span class="badge badge-primary">{{ $bank->bank.' - '.$bank->accountno.' - '.$bank->accountname }}</span></div>

                    <div class="card-body">
                        <button id="add-category-access" data-url="{{ route('tr.admin.category-list') }}" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">Add Access</button>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-hover datatable dt-responsive nowrap">
                                <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('category-access.store') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="hidden" name="bankid" value="{{ $bankid }}">
                                <select name="category_name" type="text" class="form-control" placeholder="Input category">
                                    <option value="0">( Select category )</option>
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
@endsection
@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/datatables/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables/css/responsive.bootstrap4.min.css') }}">
    <style>
        input {
            text-transform: uppercase;
        }
    </style>
@endpush
@push('scripts')
    <script type="text/javascript" src="{{ asset('plugins/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datatables/js/dataTables.bootstrap4.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datatables/js/dataTables.responsive.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/datatables/js/responsive.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function (e) {

            var dt = $('#datatable').DataTable({
                'ajax' : "{{route('category-access.index',['bankid'=>$bankid])}}",
                'ordering': false,
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "pageLength": 5,
                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
                {
                    $('td', nRow).find('.fa-recycle').closest('tr').css('color', 'Red');
                }
            });

            $('#add-category-access').on('click', function (e) {
                e.preventDefault();
                var url = $(this).attr('data-url'),
                    catName = $('select[name="category_name"]');
                $.ajax({
                    method: 'GET',
                    url: url,
                    success: function (res) {
                        catName.find('option').remove('.newly-added');
                        catName.append(res);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            $('form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (res) {
                        dt.ajax.reload(null, false);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            $('#datatable').on('click', '.delete', function (e) {
                e.preventDefault();
                alert('test');
                $(this).prop('disabled', true);
                $(this).find('i').addClass('fa-circle-o-notch fa-spin').removeClass('fa-times');

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                $.ajax({
                    method: 'DELETE',
                    url: $(this).attr('href'),
                    success: function (res) {
                        $(this).prop('disabled', false);
                        dt.ajax.reload(null, false);
                    },
                    error: function (error) {
                        $(this).prop('disabled', false);
                        console.log(error);
                    }
                });
            });

            $('#datatable').on('click', '.restore', function (e) {
                e.preventDefault();

                $(this).prop('disabled', true);
                $(this).find('i').addClass('fa-circle-o-notch fa-spin').removeClass('fa-recycle');

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                $.ajax({
                    method: 'PUT',
                    url: $(this).attr('href'),
                    success: function (res) {
                        $(this).prop('disabled', false);
                        dt.ajax.reload(null, false);
                    },
                    error: function (error) {
                        $(this).prop('disabled', false);
                        console.log(error);
                    }
                });

            });

        });
    </script>
@endpush