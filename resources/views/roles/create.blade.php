@extends('layouts.app')

@section('title', 'New Role')

@section('content')
    <div class="card">
        <h1>New Role</h1>

        <form method="POST" action="{{ route('roles.store') }}">
            @csrf

            <label for="name">Role Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required>
            @error('name') <div class="alert">{{ $message }}</div> @enderror

            <fieldset>
                <legend>Permissions</legend>
                @foreach ($permissions as $permission)
                    <label class="checkbox-row">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                               {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                        {{ $permission->name }} <span class="user-label">({{ $permission->slug }})</span>
                    </label>
                @endforeach
            </fieldset>

            <button type="submit" class="btn">Create Role</button>
            <a href="{{ route('roles.index') }}" class="btn btn-outline">Cancel</a>
        </form>
    </div>
@endsection