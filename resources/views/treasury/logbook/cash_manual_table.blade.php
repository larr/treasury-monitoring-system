<tr class="manual-cash-adj-{{ $code }}">
    <td>
        <input type="hidden" name="arFrom[]">
        <input type="hidden" name="arTo[]">

        <input type="hidden" name="autoids[]" value="1">
        <input type="hidden" name="autoamounts[]" value="0">

        <input type="hidden" name="cashids[]" value="{{ $code }}">

        <input type="hidden" name="inputStatus[]" value="tre">

        <input type="hidden" name="logbookDesc[]" value="{{ $desc }}">

        <input type="hidden" name="hrmsCode[]" value="{{ $hrmscode }}">

        {{ $desc }}
    </td>
    <td>
        <input type="hidden" name="sales_date[]" value="{{ $date->format('Y-m-d') }}">
        {{ $date->format('F d, Y') }}
    </td>
    <td>
        <input type="hidden" name="ds[]" value="{{ $request->ds_number }}">

        <input type="hidden" name="status_adj[]" value="{{ $request->status_adj }}">
        <span class="float-right">{{ $request->ds_number }}</span>
    </td>
    <td>
        {{--<input type="hidden" name="cs_amount[]" value="{{ $amount }}">--}}
        {{--<span class="float-right">{{ $request->amount }}</span>--}}
        <input data-inputmask="'alias': 'numeric', 'groupSeparator': ',', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'placeholder': '0'" im-insert="true" name="cs_amount[]" type="text" placeholder="Input amount" autocomplete="off" id="" class="form-control amount-change" style="text-align: right;" value="{{ $request->amount }}" readonly>
    </td>
    <td>
        <button type="button" class="btn remove-cash-selected"><i class="fa fa-times"></i></button>
    </td>
</tr>