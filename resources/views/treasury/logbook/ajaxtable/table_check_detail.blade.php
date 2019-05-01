@foreach($checks as $check)
    <tr>
        <td>{{ $check->check_no }}</td>
        <td>{{ $check->check_class }}</td>
        <td>{{ $check->check_category }}</td>
        <td>{{ $check->check_date }}</td>
        <td>{{ $check->check_received }}</td>
        <td>{{ number_format($check->check_amount, 2) }}</td>
    </tr>
@endforeach
<tr>
    <td colspan="4"></td>
    <td>Total:</td>
    <td>{{ number_format($checksTotal,2) }}</td>
</tr>