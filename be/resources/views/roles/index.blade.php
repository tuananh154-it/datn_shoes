@extends('master')

@section('content')

    <style>
        .row {
            padding-top: 60px;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table th {
            white-space: nowrap;
            background-color: #f8f9fa;
        }

        .table td {
            word-wrap: break-word;
            max-width: 300px; /* Giới hạn chiều rộng của ô nội dung */
        }

        .table-responsive {
            overflow-x: auto;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 250px;
        }
        th,
        td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .table td,
        .table th {
            vertical-align: middle;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">
                    Danh sách phân quyền
                </header>
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

                {{-- <div class="mb-3">
                    <a href="{{ route('roles.create') }}" class="btn btn-light" style="margin-top:12px;background-color:#78CD51;color:white;border:#78CD51">
                        <i class="fa fa-plus"></i> Thêm quyền hạn
                    </a>
                </div> --}}
                {{-- Bảng hiển thị bình luận --}}
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
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
                                            <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-pencil"></i>
                                            </a>

                                            <!-- <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa?');" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
<button type="submit" class="btn btn-danger btn-sm">
<i class="fa fa-trash"></i> Xóa
                                                </button>
                                            </form> -->
                                        @else
                                            <span class="badge bg-secondary">Super Admin</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- PHÂN TRANG --}}
                {{-- <div class="row">
                    <div class="col-12">
                        {{ $roles->links() }}
                    </div>
                </div> --}}

            </section>
        </div>
    </div>

@endsection