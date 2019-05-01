<tr id="cashlog-data-{{ $create->cash_id }}">
    <td>
        <input type="hidden" name="treids[]" value="{{ $create->id }}">
        <input type="hidden" name="cashids[]" value="{{ $create->cash_id }}">
        {{ $create->cashLog->description }}
    </td>
    <td>{{ $create->sales_date->format('F d, Y') }}</td>
    <td>{{ $create->ds_no }}</td>
    <td>{{ number_format($create->amount_edited,2) }}</td>
</tr>