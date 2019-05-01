@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">List of companies</div>

                    <div class="card-body">
                        <button id="add-user-access" data-url="{{ route('tr.admin.useraccesscompany') }}" class="btn btn-primary mb-3" data-toggle="modal" data-target="#exampleModal">Add Access</button>
                        <div class="table-responsive">
                        <table id="datatable" class="table table-hover datatable dt-responsive nowrap">
                            <thead>
                            <tr>
                                <th>Description</th>
                                <th>Code</th>
                                <th>Action</th>
                                <th>Cash BU</th>
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
                    <form action="{{ route('section-access.store') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add department access</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="cashbu">Cash bu</label>
                                <select id="cashbu" name="cashbu" type="text" class="form-control">
                                    <option value="0">( Select cash bu )</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="company">Company</label>
                                <input type="hidden" value="{{ $bankid }}" name="bankid">
                                <select data-url="{{ route('tr.admin.useraccessbu') }}" id="company" name="company" type="text" class="form-control">
                                    <option value="0">( Select company )</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="bu">Business unit</label>
                                <select data-url="{{ route('tr.admin.useraccessdept') }}" id="bu" name="bu" type="text" class="form-control">
                                    <option value="0">( Select businessunit )</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dept">Department</label>
                                <select data-url="{{ route('tr.admin.useraccesssec') }}" id="dept" name="dept" type="text" class="form-control">
                                    <option value="0">( Select department )</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="section">Section</label>
                                <select data-url="{{ route('tr.admin.useraccesssub') }}" id="section" name="section" type="text" class="form-control">
                                    <option value="0">( Select section )</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ssection">Sub section</label>
                                <select id="ssection" name="ssection" type="text" class="form-control">
                                    <option value="0">( Select sub section )</option>
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

            var companySB = $('#company'),
                buSB = $('#bu'),
                deptSB = $('#dept'),
                sectionSB = $('#section'),
                ssectionSB = $('#ssection'),
                cashBUSB = $('#cashbu');

            var dt = $('#datatable').DataTable({
                'ajax' : "{{route('section-access.index',['bankid'=>$bankid])}}",
                'ordering': false,
                "lengthMenu": [[5, 10, 50, -1], [5, 10, 50, "All"]],
                "pageLength": 5,
                "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull )
                {
                    $('td', nRow).find('.fa-recycle').closest('tr').css('color', 'Red');
                }
            });

            $('#datatable').on('click', '.delete', function (e) {
                e.preventDefault();

                $(this).prop('disabled', true);
                $(this).find('i').addClass('fa-circle-o-notch fa-spin').removeClass('fa-times');

                $.ajaxSetup({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
                });

                $.ajax({
                    method: 'DELETE',
                    url: $(this).attr('data-url'),
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
                    url: $(this).attr('data-url'),
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

            $('#add-user-access').on('click', function (e) {

                companySB.val('0');
                buSB.val('0');
                deptSB.val('0');
                sectionSB.val('0');
                ssectionSB.val('0');

                buSB.find('option').remove('.newly-added');
                deptSB.find('option').remove('.newly-added');
                sectionSB.find('option').remove('.newly-added');
                ssectionSB.find('option').remove('.newly-added');

                $.ajax({
                    method: 'GET',
                    url: $(this).attr('data-url'),
                    success: function (res) {
                        console.log(res);
                        companySB.find('option').remove('.newly-added');
                        cashBUSB.find('option').remove('.newly-added');
                        cashBUSB.append(res.cash_bu_view);
                        companySB.append(res.company_view);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            companySB.on('change', function (e) {

                var company_code = $(this).val();

                $.ajax({
                    method: 'GET',
                    url: $(this).attr('data-url'),
                    data: {company_code:company_code},
                    success: function (res) {
                        buSB.find('option').remove('.newly-added');
                        deptSB.find('option').remove('.newly-added');
                        sectionSB.find('option').remove('.newly-added');
                        ssectionSB.find('option').remove('.newly-added');
                        buSB.append(res);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            buSB.on('change', function (e) {

                var company_code = companySB.val(),
                    bunit_code = $(this).val();

                $.ajax({
                    method: 'GET',
                    url: $(this).attr('data-url'),
                    data: {
                        company_code:company_code,
                        bunit_code:bunit_code
                    },
                    success: function (res) {
                        deptSB.find('option').remove('.newly-added');
                        sectionSB.find('option').remove('.newly-added');
                        ssectionSB.find('option').remove('.newly-added');
                        deptSB.append(res);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });

            });

            deptSB.on('change', function (e) {
                var company_code = companySB.val(),
                    bunit_code = buSB.val(),
                    dept_code = $(this).val();

                $.ajax({
                    method: 'GET',
                    url: $(this).attr('data-url'),
                    data: {
                        company_code:company_code,
                        bunit_code:bunit_code,
                        dept_code:dept_code
                    },
                    success: function (res) {
                        sectionSB.find('option').remove('.newly-added');
                        ssectionSB.find('option').remove('.newly-added');
                        sectionSB.append(res);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            sectionSB.on('change', function (e) {
                var company_code = companySB.val(),
                    bunit_code = buSB.val(),
                    dept_code = deptSB.val();
                    section_code = $(this).val();

                $.ajax({
                    method: 'GET',
                    url: $(this).attr('data-url'),
                    data: {
                        company_code:company_code,
                        bunit_code:bunit_code,
                        dept_code:dept_code,
                        section_code:section_code
                    },
                    success: function (res) {
                        ssectionSB.find('option').remove('.newly-added');
                        ssectionSB.append(res);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });
            });

            $('form').on('submit', function (e) {
                e.preventDefault();
                var data = $(this).serialize();
                $.ajax({
                    method: 'POST',
                    url: $(this).attr('action'),
                    data: data,
                    success: function (res) {
                        dt.ajax.reload(null, false);
                    },
                    error: function (error) {
                        console.log(error);
                    }
                });

            });
        });
    </script>
@endpush