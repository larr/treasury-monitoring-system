@foreach($employees as $e)
    <tr>
        <td>{{ $e->emp_id }}</td>
        <td>{{ $e->date_shrt->format('F d, Y') }}</td>
        <td>{{ $e->den_total }}</td>
    </tr>
@endforeach