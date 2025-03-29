@extends('master')

@section('content')
<div class="container">
    <h1>Edit Role</h1>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Tên vai trò -->
        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" required>
        </div>

        <!-- Danh sách quyền -->
        <div class="mb-3">
            <label for="permissions" class="form-label">Permissions</label>
            <div>
                @foreach ($permissions as $permission)
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="permissions[]"
                            id="permission-{{ $permission->id }}"
                            value="{{ $permission->id }}"
                            {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Role</button>
    </form>
</div>
@endsection
