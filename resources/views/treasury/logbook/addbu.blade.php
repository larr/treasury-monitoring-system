@foreach($cashCon as $key => $c)
    <option class="new-added-option" value="{{ $key-100 }}+{{ $c['hrmscode'] }}+{{ $c['bu'] }}">{{ $c['bu'] }}</option>
@endforeach