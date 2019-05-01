@foreach($args as $a)
    <tr>
        <td>
            {{ $a[1] }}
        </td>
        <td>
            <button class="btn btn-primary btn-sm">View</button>
        </td>
    </tr>
@endforeach
