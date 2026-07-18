@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
    <div class="card">
        <div class="page-header">
            <h1>Roles</h1>
            <a href="{{ route('roles.create') }}" class="btn">+ New Role</a>
        </div>

        @if (session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td>{{ $role->name }}</td>
                        <td>
                            @if ($role->slug === 'system-administrator')
                                <span class="user-label">All permissions (super-admin bypass)</span>
                            @else
                                {{ $role->permissions->pluck('name')->implode(', ') ?: '— none —' }}
                            @endif
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('roles.edit', $role) }}" class="btn btn-outline btn-sm">Edit</a>
                                @if ($role->slug !== 'system-administrator')
                                    <form method="POST" action="{{ route('roles.destroy', $role) }}"
                                          onsubmit="return confirm('Delete this role? Users holding it will lose it.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection