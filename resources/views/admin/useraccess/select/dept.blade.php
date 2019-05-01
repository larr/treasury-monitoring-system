@foreach($dept as $d)
    <option class="newly-added" value="{{ $d->dept_code }}">{{ $d->dept_code.'. '.$d->dept_name }}</option>
@endforeach