@extends('layouts.app')

@section('content')
<style>
    /* Tăng không gian giữa các phần tử */
.container {
    margin-top: 50px;
    margin-bottom: 50px;
}

/* Làm đẹp bảng */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    margin-bottom: 30px;
}

/* Định dạng các ô của bảng */
th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

/* Tăng độ rộng của cột tiêu đề */
th {
    background-color: #f8f9fa;
    font-size: 18px;
}

/* Nút thêm mới và nút hành động */
.btn {
    font-size: 14px;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
}

/* Nút Create Role */
.btn-primary {
    background-color: #007bff;
    color: white;
    border: none;
    transition: background-color 0.3s;
}

.btn-primary:hover {
    background-color: #0056b3;
}

/* Nút Edit */
.btn-warning {
    background-color: #ffc107;
    color: white;
    border: none;
    transition: background-color 0.3s;
}

.btn-warning:hover {
    background-color: #e0a800;
}

/* Nút Delete */
.btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
    transition: background-color 0.3s;
}

.btn-danger:hover {
    background-color: #c82333;
}

/* Cải thiện khoảng cách giữa các nút */
form {
    display: inline;
    margin-left: 10px;
}

/* Tạo hiệu ứng hover cho các hàng trong bảng */
tr:hover {
    background-color: #f1f1f1;
}

/* Định dạng cho các thông báo */
.alert {
    margin-top: 20px;
    padding: 10px;
    background-color: #f8d7da;
    color: #721c24;
    border-radius: 5px;
}

</style>
<div class="container">
    <h1 class="mb-4">Manage Roles</h1>

    <!-- Button Create Role -->
    <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Create Role</a>

    <!-- Display message after successful action -->
    @if(session('success'))
    <div class="alert">
        {{ session('success') }}
    </div>
    @endif

    <!-- Table for Roles -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Role Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>
                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">Edit</a>

                    <!-- Delete form -->
                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
