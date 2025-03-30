@extends('master')

@section('content')

    <style>
        .row {
            padding-top: 60px;
        }

        .table th, .table td {
            text-align: center;
        }

        .table .btn {
            margin: 0 5px;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">
                    Danh sách quyền hạn
                </header>

                <div class="mb-3">
                    <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Thêm quyền hạn
                    </a>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên Vai Trò</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @if (strtolower($role->name) === 'super-admin')
                                    <span class="badge bg-secondary"></span>
                                @else
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-pencil"></i> Sửa
                                    </a>

                                    <!-- Delete form -->
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-o"></i> Xóa
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </div>
    </div>

@endsection
