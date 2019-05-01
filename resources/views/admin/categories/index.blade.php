@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of categories</div>

                    <div class="card-body">
                        <button class="btn btn-primary mb-4" data-toggle="modal" data-target="#exampleModal">Add new category</button>
                        <table id="datatable" class="table table-hover datatable dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th><input type="checkbox"></th>
                                <th>Month</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('categories.store') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input name="category_name" type="text" class="form-control" placeholder="Input category">
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

            $('#datatable').on('click', '.delete', function (e) {
                var url = $(this).attr('href');

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                $.ajax({
                    method: 'DELETE',
                    url: url,
                    success: function (res) {
                        dt.ajax.reload(null, false);
                    }
                });

            });

            var dt = $('#datatable').DataTable({
                'ajax' : "{{route('categories.index')}}",
                'ordering': false,
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "pageLength": 5,
                {{--data:"{{route('categories.index')}}",--}}
            });

            $('form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    data: $(this).serialize(),
                    success: function (res) {
                        dt.ajax.reload(null, false);
                    }
                });

            });
        });
    </script>
@endpush