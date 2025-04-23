@extends('master')

@section('content')
    <style>
        .row {
            padding-top: 60px;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 5px;
        }

        /* Cải tiến bảng */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #41CAC0;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        /* Cải tiến nút và form */
        .btn-primary {
            background-color: #41CAC0;
            /* border-color: #007bff; */
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        /* Cải tiến form tìm kiếm */
        .form-control {
            border-radius: 5px;
            height: 40px;
        }

        .select2-container {
            width: 100% !important;
        }

        .alert {
            border-radius: 5px;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header text-center">
                    <h4>Bảng người dùng</h4>
                </header>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form tìm kiếm -->
                <div class="mb-3">
                    <form action="{{ route('users.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="email_search" class="form-control"
                                    placeholder="Tìm kiếm theo email" value="{{ request()->email_search }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="name_search" class="form-control"
                                    placeholder="Tìm kiếm theo tên người dùng" value="{{ request()->name_search }}">
                            </div>
                            <div class="col-md-3">
                                <select name="role_search" class="form-control">
                                    <option value="">Chọn vai trò</option>
                                    <option value="superadmin" {{ request()->role_search == 'superadmin' ? 'selected' : '' }}>
                                        Superadmin</option>
                                    <option value="admin" {{ request()->role_search == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="user" {{ request()->role_search == 'user' ? 'selected' : '' }}>Người dùng
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Thêm người dùng -->
                <div class="mb-3 text-right">
                    <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Thêm người dùng
                    </a>
                </div>

                <!-- Bảng người dùng -->
                <table class="table table-striped table-advance table-hover">
                    <thead>
                        <tr>
                            <th><i class="fa fa-user"></i> Tên</th>
                            <th><i class="fa fa-envelope"></i> Email</th>
                            <th><i class="fa fa-cogs"></i> Vai trò</th>
                            <th><i class="fa fa-gear"></i> Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fa fa-pencil"></i> Sửa
                                    </a>

                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-danger btn-sm">
                                        <i class="fa fa-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            {{ $users->links() }}
        </div>
    </div>

@endsection
