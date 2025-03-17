@extends('layouts.app')

@section('content')
<style>
    .row {
    padding-top: 60px;
}

.container {
    width: 100%;
    max-width: 1200px; /* Chiều rộng tối đa */
    margin: 0 auto;
    padding: 30px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #f1f1f1;
    font-weight: bold;
    color: #333;
}

table tr:hover {
    background-color: #f9f9f9;
}

/* Button styles */
.btn {
    display: inline-block;
    padding: 10px 20px;
    font-size: 1em;
    text-align: center;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-warning {
    background-color: #ffc107;
    color: white;
    border: none;
}

.btn-warning:hover {
    background-color: #e0a800;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
}
.btn-success {
    background-color: #28a745;
    color: white;
    border: none;
}

.btn-danger:hover {
    background-color: #c82333;
}

/* Form button (Delete) */
form button {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
}

form button:hover {
    opacity: 0.9;
}

/* Success message */
.alert-success {
    margin-top: 20px;
    padding: 10px;
    background-color: #d4edda;
    color: #155724;
    border-radius: 5px;
    text-align: center;
}

</style>

<div class="container">
    <h1>Quản lý Người Dùng</h1>

    <!-- Display success message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Button to create new user -->
    <div class="mb-3">
        <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
            <i class="fa fa-plus"></i> Thêm người dùng
        </a>
    </div>

    <!-- Users table -->
    <table>
        <thead>
            <tr>
                <th><i class=""></i> Tên</th>
                <th><i class=""></i> Email</th>
                <th><i class=""></i> Vai trò</th>
                <th><i class=""></i> Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->first()?->name }}</td>
                    <td>
                        <!-- Edit Button -->
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa fa-pencil"></i> Sửa
                        </a>

                        <!-- Delete Form -->
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fa fa-trash-o"></i> Xóa
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
