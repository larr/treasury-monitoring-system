@foreach($company as $c)
    <option class="newly-added" value="{{ $c->company_code }}">{{ $c->company_code.'. '.$c->company }}</option>
@endforeach