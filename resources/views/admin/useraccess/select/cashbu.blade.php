@foreach($cash_bu as $c)
    <option class="newly-added" value="{{ $c->id }}">{{ $c->id.'. '.$c->description }}</option>
@endforeach