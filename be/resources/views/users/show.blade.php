@extends('master')

@section('content')
    <div class="container">
        <h2>Thông tin người dùng</h2>
        <table class="table table-bordered">
            <tr>
                <th>Tên</th>
                <td>{{ $user->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Vai trò</th>
                <td>{{ $user->roles->first()?->name }}</td>
            </tr>
            <tr>
                <th>Giới tính</th>
                <td>{{ $user->gender ?? 'Chưa có thông tin' }}</td>  <!-- Thêm thông tin giới tính -->
            </tr>
            <tr>
                <th>Địa chỉ</th>
                <td>{{ $user->address ?? 'Chưa có thông tin' }}</td>  <!-- Thêm thông tin địa chỉ -->
            </tr>
            <tr>
                <th>Số điện thoại</th>
                <td>{{ $user->phone_number ?? 'Chưa có thông tin' }}</td>  <!-- Thêm thông tin số điện thoại -->
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td>{{ $user->created_at }}</td>
            </tr>
            <!-- Thêm thông tin khác nếu cần -->
        </table>

        <a href="{{ route('users.index') }}" class="btn btn-primary">Quay lại danh sách</a>
    </div>
@endsection
