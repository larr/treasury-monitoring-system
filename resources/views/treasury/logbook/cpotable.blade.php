<tr id="cpo-data-{{ $createcpo->id }}" class="cpo-data-class-{{ $createcpo->id }}">
    <td>
        {{ $createcpo->department }}
    </td>
    <td>
        {{ number_format($createcpo->amount,2) }}
    </td>
</tr>