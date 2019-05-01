@foreach($bu as $b)
    <option class="newly-added" value="{{ $b->bunit_code }}">{{ $b->bunit_code.'. '.$b->business_unit }}</option>
@endforeach