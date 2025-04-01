@extends('master')

@section('content')
<div class="container">
    <h1>Create Role</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Role Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Enter role name" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Permissions</label>
            @foreach ($groupedPermissions as $group => $permissions)
                <h4>{{ ucwords(str_replace('_', ' ', $group)) }}</h4> <!-- Tiêu đề nhóm quyền -->

                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="permissions[]"
                                    id="permission-{{ $permission->id }}"
                                    value="{{ $permission->id }}">
                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>  <!-- Tách các nhóm quyền -->
            @endforeach
        </div>

        <button type="submit" class="btn btn-success "> Thêm mới </button>


        
    </form>
</div>
@endsection
