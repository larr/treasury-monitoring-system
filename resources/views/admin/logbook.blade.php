@extends('admin.layout.app')

@section('content')
    <div class="row">
        <div class="col-sm-6">
            <div class="card-box">
                <div class="p-20">
                    <form action="{{ route('tr.logbook.list') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="bu">Business unit</label>
                            <select name="bu" id="bu" class="form-control {{ $errors->has('bu') ? ' is-invalid' : '' }}">
                                {{ old("bu") }}
                                <option value="0" {{ (old("bu") == 0 ? "selected":"") }}>( Select Business unit )</option>
                                @foreach($bulogs as $key => $bu)
                                    <option value="{{ $bu->bu_unit }}" {{ (old("bu") == $bu->bu_unit ? "selected":"") }}>{{ $bu->bu->bname }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('bu'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('bu') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="datepicker">Select logbook sales date</label>
                            <input value="{{ old('sales_date') }}" type="text" id="datepicker" class="form-control {{ $errors->has('sales_date') ? ' is-invalid' : '' }}" name="sales_date" autocomplete="off">
                            @if ($errors->has('sales_date'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('sales_date') }}</strong>
                                </span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datepicker3.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/css/main.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script type="text/javascript">
        $('#logbook').addClass('active');

        $('#datepicker').datetimepicker({
            pickTime: false,
            format: "YYYY-MM-DD",
            useCurrent: false,
//            minDate: new Date(splitdate[0], splitdate[1]-1, splitdate[2]),
//            startDate: new Date(splitdate[0], splitdate[1]-1, splitdate[2])
        });

//        $('form').on('submit', function (e) {
//            e.preventDefault();
//
//        });
    </script>
@endpush