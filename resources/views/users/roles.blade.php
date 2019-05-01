@if($roles)
@foreach($roles as $key => $role)
    <div class="checkbox checkbox-primary">
        <input type="hidden" name="user_id" value="{{ $user->user_id }}">
        <input id="{{ $key }}" type="checkbox" name="role_{{ strtolower($role->name) }}" {{ $user->hasRole($role->name) ? 'checked' : '' }}>
        <label for="{{ $key }}">{{ $role->name }}</label>
    </div>
@endforeach
@endif