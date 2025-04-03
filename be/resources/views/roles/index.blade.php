@extends('master')

@section('content')

    <style>
        .container {
            padding-top: 60px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #41CAC0;
            color: white;
        }


        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-group .btn {
            margin-right: 5px;
        }

        .action-btns {
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Danh sách quyền hạn</h4>
                <a href="{{ route('roles.create') }}" class="btn btn-light">
                    <i class="fa fa-plus"></i> Thêm quyền hạn
                </a>
            </div>

            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên Vai Trò</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $index => $role)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ ucfirst($role->name) }}</td>
                                <td class="action-btns">
                                    @if (strtolower($role->name) !== 'super-admin')
                                        <div class="btn-group">
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning">
                                                <i class="fa fa-pencil"></i> Sửa
                                            </a>

                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Super Admin</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
