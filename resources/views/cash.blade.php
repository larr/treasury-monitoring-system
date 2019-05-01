@extends('admin.layout.app')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box">
                <table id="datatable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bank</th>
                            <th>Units</th>
                            <th>Codes</th>
                            <th>BU</th>
                            <th><i class="fa fa-list"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cash_bus as $cash_bu)
                        <tr>
                            <td>{{ $cash_bu->banks->bank . ' ' . $cash_bu->banks->accountno . ' ' . $cash_bu->banks->accountname }}</td>
                            <td>
                                [{{ $cash_bu->getBUPIS() }}
                                <span class="badge badge-primary">bu</span>]
                                [{{ $cash_bu->getDepartmentPIS() }}
                                <span class="badge badge-primary">de</span>]
                                [{{  $cash_bu->getSectionPIS() }}
                                <span class="badge badge-primary">se</span>]
                                [{{ $cash_bu->getSubSectionPIS() }}
                                <span class="badge badge-primary">su</span>]
                            </td>
                            <td>[{{ $cash_bu->company_code . ' ' . $cash_bu->bunit_code . ' ' . $cash_bu->dept_code . ' ' . $cash_bu->section_code . ' ' . $cash_bu->sub_section_code }}]</td>
                            <td>{{ ($cash_bu->cash_bu)?$cash_bu->cash_bu->description:'' }}</td>
                            <td>
                                <a href="javascript:void(0)"><i class="fa fa-gear fa-spin"></i></a>
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
    {{--<script src="{{ asset('admin/assets/js/jquery.core.js') }}"></script>--}}
    {{--<script src="{{ asset('admin/assets/js/jquery.app.js') }}"></script>--}}
    <script type="text/javascript">
        $('#datatable').DataTable({
            ordering:false,
            "lengthMenu": [[8, 10, 50, -1], [8, 10, 50, "All"]],
            "pageLength": 8,
        });
        $('#cash').addClass('active');
    </script>
@endpush