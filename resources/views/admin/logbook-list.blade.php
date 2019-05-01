@extends('admin.layout.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <form action="{{ route('tr.logbook.fill.codes') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Description</th>
                                    <th>Sales Date</th>
                                    <th>Deposit Date</th>
                                    <th>DS</th>
                                    <th>Amount</th>
                                    <th>Code</th>
                                    <th>Controls</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logbooks as $key => $log)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="id[]" value="{{$log->id}}">
                                            <input type="hidden" name="trans_code[]" value="{{$key+1}}">
                                            {{ $log->id }}
                                        </td>
                                        <td>{{ $log->logbook_desc }}</td>
                                        <td>{{ $log->sales_date->format('M d, Y') }}</td>
                                        <td>{{ $log->deposit_date->format('M d, Y') }}</td>
                                        <td>
                                            @foreach($log->ds as $d)
                                                {{ $d->id }} |
                                                {{ $d->trans_code }} |
                                                {{ $d->ds_number }} |
                                                {{ $d->amount }}
                                            @endforeach
                                        </td>
                                        <td>{{ $log->amount }}</td>
                                        <td>{{ $log->trans_code }}</td>
                                        <td>
                                            <a class="fill-ds-codes" href="{{ route('tr.logbook.fill.ds.codes', ['id'=> $log->id]) }}" title="add ds transaction codes"><i class="fa fa-key"></i></a>&nbsp;&nbsp;
                                            <a class="view" href="{{ route('tr.logbook.view',[$log->id]) }}" title="view details" data-toggle="modal" data-target="#view-logbook"><i class="fa fa-list"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Populate codes</button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <!-- The Modal -->
            <div class="modal fade" id="view-logbook">
                <div class="modal-dialog modal-full">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Logbook details</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="sales_date">Sales date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-calendar"></i>
                                                </span>
                                            </div>
                                            <input id="sales_date" name="sales_date" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="deposit_date">Deposit date</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-calendar"></i>
                                                </span>
                                            </div>
                                            <input id="deposit_date" name="deposit_date" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="desc">Description</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-ios7-paper-outline"></i>
                                                </span>
                                            </div>
                                            <input id="desc" name="desc" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="amount">Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    &#8369;
                                                </span>
                                            </div>
                                            <input id="amount" name="amount" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="ar_from">AR From</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-chevron-left"></i>
                                                </span>
                                            </div>
                                            <input id="ar_from" name="ar_from" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="ar_to">AR To</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-chevron-right"></i>
                                                </span>
                                            </div>
                                            <input id="ar_to" name="ar_to" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="bank_code">Bank Code</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-briefcase"></i>
                                                </span>
                                            </div>
                                            <input id="bank_code" name="bank_code" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="status_clerk">Status Clerk</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-key"></i>
                                                </span>
                                            </div>
                                            <input id="status_clerk" name="status_clerk" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="input_status">User input status</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-key"></i>
                                                </span>
                                            </div>
                                            <input id="input_status" name="input_status" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="deleted_by">Deleted By</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-android-trash"></i>
                                                </span>
                                            </div>
                                            <input id="deleted_by" name="deleted_by" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="created_by">Created by</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-android-add-contact"></i>
                                                </span>
                                            </div>
                                            <input id="created_by" name="created_by" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="updated_by">Updated By</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="ion-checkmark-round"></i>
                                                </span>
                                            </div>
                                            <input id="updated_by" name="updated_by" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="bank">Bank</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-bank"></i>
                                                </span>
                                            </div>
                                            <input id="bank" name="bank" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="currency">Currency</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-money"></i>
                                                </span>
                                            </div>
                                            <input id="currency" name="currency" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="company">Company</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-cubes"></i>
                                                </span>
                                            </div>
                                            <input id="company" name="company" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="bu">Business unit</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-cubes"></i>
                                                </span>
                                            </div>
                                            <input id="bu" name="bu" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="hrms_code">Hrms Code</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <a id="view_codes" class="input-group-text" href="{{ route('tr.logbook.view.codes') }}" data-id="" data-toggle="modal" data-target="#hrms_code_modal">
                                                    <i class="fa fa-key"></i>
                                                </a>
                                            </div>
                                            <input id="hrms_code" name="hrms_code" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="cs_amount">CS amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="fa fa-money"></i>
                                                </span>
                                            </div>
                                            <input id="cs_amount" name="cs_amount" class="form-control" type="text" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- -->
            <div class="modal fade" id="hrms_code_modal">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Business units</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <table id="hrms-code-table" class="table">
                                <thead>
                                <tr>
                                    <th>BU</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        $('#logbook').addClass('active');

        $('form').on('submit', function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $.ajax({
                url:$(this).attr('action'),
                data:data,
                type:"POST",
                success: function (res) {
                    alert('success');
                },
                error: function (error) {

                }
            });
        });

        $('table').on('click', '.fill-ds-codes', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            var a = $(this);

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            $.ajax({
                url:url,
                method: 'POST',
                success: function (res) {
                    a.css('color','#009886');
                },
                error: function (error) {

                }
            });
        });

        $('table').on('click', '.view', function (e) {
            e.preventDefault();

            var url = $(this).attr('href');

            $('#view-logbook').find('.modal-body').append('<div class="div-disabled">' +
                '                                    <div class="loader-1"></div>' +
                '                                </div>');

            $.ajax({
                url:url,
                method: 'GET',
                success: function (res) {
                    $('#sales_date').val(res.sales_date);
                    $('#deposit_date').val(res.deposit_date);
                    $('#desc').val(res.logbook_desc);
                    $('#amount').val(res.amount);
                    $('#ar_from').val(res.ar_from);
                    $('#ar_to').val(res.ar_to);
                    $('#bank_code').val(res.bank_code);
                    $('#status_clerk').val(res.status_clerk);
                    $('#input_status').val(res.user_input_status);
                    $('#deleted_by').val(res.deleted_by);
                    $('#created_by').val(res.created_by);
                    $('#updated_by').val(res.updated_by);
                    $('#bank').val(res.bank);
                    $('#currency').val(res.currency);
                    $('#company').val(res.company);
                    $('#bu').val(res.bu);
                    $('#hrms_code').val(res.hrms_code);
                    $('#cs_amount').val(res.cs_amount);
                    $('#view_codes').attr('data-id', res.id);

                    $('.div-disabled').remove();
                },
                error: function (error) {
                    console.log(error);
                }
            });

        });

        $('#view_codes').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href'),
                id = $(this).attr('data-id');

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            $.ajax({
                url:url,
                data: {
                    id:id
                },
                method: 'POST',
                success: function (res) {
                    $('#hrms-code-table tbody').html(res);
                },
                error: function (error) {

                }
            });
        });

    </script>
@endpush