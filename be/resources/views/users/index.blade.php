
@extends('master')

@section('content')
<style>

    .row{
        padding-top: 60px;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Bảng sản người dùng
            </header>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="">
                <a href="{{ route('users.create') }}" class="btn btn-success btn-sm">

                    <i class="fa fa-plus"></i> Thêm sản người dùng
                </a>
            </div>
            <div class="mb-3">
                <div class="mb-3">
                    <form action="{{ route('users.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="email_search" class="form-control" placeholder="Tìm kiếm theo email" value="{{ request()->email_search }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" name="name_search" class="form-control" placeholder="Tìm kiếm theo tên người dùng" value="{{ request()->name_search }}">
                            </div>
                            <div class="col-md-3">
                                <select name="role_search" class="form-control">
                                    <option value="">Chọn vai trò</option>
                                    <option value="superadmin" {{ request()->role_search == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                                    <option value="admin" {{ request()->role_search == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ request()->role_search == 'user' ? 'selected' : '' }}>Người dùng</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>




            <table class="table table-striped table-advance table-hover">

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
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash-o"></i> Xóa
                                    </button>
                                </form>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-pencil"></i> Sửa
                                </a>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            </div>
        </section>
    </div>
</div>

@endsection
