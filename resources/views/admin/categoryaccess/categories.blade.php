@foreach($categories as $category)
    <option class="newly-added" value="{{ $category->id }}">{{ $category->description }}</option>
@endforeach