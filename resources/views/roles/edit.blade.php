@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
    <div class="card">
        <h1>Edit Role: {{ $role->name }}</h1>

        <form method="POST" action="{{ route('roles.update', $role) }}">
            @csrf
            @method('PATCH')

            <label for="name">Role Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required>
            @error('name') <div class="alert">{{ $message }}</div> @enderror

            <fieldset>
                <legend>Permissions</legend>
                @php $current = $role->permissions->pluck('id')->all(); @endphp
                @foreach ($permissions as $permission)
                    <label class="checkbox-row">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                               {{ in_array($permission->id, old('permissions', $current)) ? 'checked' : '' }}>
                        {{ $permission->name }} <span class="user-label">({{ $permission->slug }})</span>
                    </label>
                @endforeach
            </fieldset>

            <button type="submit" class="btn">Save Changes</button>
            <a href="{{ route('roles.index') }}" class="btn btn-outline">Cancel</a>
        </form>
    </div>
@endsection