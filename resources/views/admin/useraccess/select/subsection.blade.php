@foreach($sub as $s)
    <option class="newly-added" value="{{ $s->sub_section_code }}">{{ $s->sub_section_code.'. '.$s->sub_section_name }}</option>
@endforeach