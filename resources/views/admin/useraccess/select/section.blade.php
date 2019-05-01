@foreach($sec as $s)
    <option class="newly-added" value="{{ $s->section_code }}">{{ $s->section_code.'. '.$s->section_name }}</option>
@endforeach