@extends('master')

@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-4">Thông tin người dùng</h2>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="background-color:#78CD51;padding:20px" colspan="2" class="text-center">Chi tiết người dùng</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th>Tên</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <!-- <tr>
                    <th>Vai trò</th>
                    <td>{{ $user->roles->first()?->name ?? 'Chưa có vai trò' }}</td>
                </tr> -->
                <tr>
                        <th>Vai trò</th>
                        <td>{{ $user->role ?? 'Chưa có vai trò' }}</td>
                    </tr>
                <tr>
                    <th>Giới tính</th>
                    <td>{{ $user->gender ?? 'Chưa có thông tin' }}</td>
                </tr>
                <tr>
                    <th>Địa chỉ</th>
                    <td>{{ $user->address ?? 'Chưa có thông tin' }}</td>
                </tr>
                <tr>
                    <th>Số điện thoại</th>
                    <td>{{ $user->phone_number ?? 'Chưa có thông tin' }}</td>
                </tr>
                <tr>
                    <th>Ngày tạo</th>
                    <td>{{ $user->created_at->format('d/m/Y H:i') }}</td> <!-- Định dạng ngày giờ -->
                </tr>
            </tbody>
        </table>

        <div class="text-center mt-3">
            <a href="{{ route('users.index') }}" class="btn btn-primary btn-lg">Quay lại danh sách</a>
        </div>
    </div>

    <style>
       .container {
    max-width: 800px;
    margin: 0 auto;
}

table {
    width: 100%;
    margin-top: 20px;
}

table th, table td {
    padding: 15px 20px; /* Tăng padding cho các ô */
    vertical-align: middle;
}

table th {
    background-color: #41CAC0;
    color: white;
    text-align: center;
    border-radius: 5px;
}

table td {
    background-color: #f9f9f9; /* Thêm màu nền cho các ô dữ liệu */
}

table tr:nth-child(even) td {
    background-color: #f9f9f9;
}

table tr:hover td {
    background-color: #f1f1f1;
}

table tr {
    border-bottom: 1px solid #ddd; /* Tạo đường viền dưới các dòng để phân cách */
}

table tbody tr:last-child {
    border-bottom: none; /* Xóa đường viền dưới cùng để không bị thừa */
}

.text-center {
    text-align: center;
}

.text-center.mt-3 {
    margin-top: 20px; /* Tạo khoảng cách cho nút quay lại */
}

.btn-lg {
    padding: 12px 25px; /* Tăng kích thước của nút */
    font-size: 16px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.btn-primary {
    background-color: #41CAC0;
    border-color: #41CAC0;
}

.btn-primary:hover {
    background-color:#2a7e78;
    border-color: #2a7e78;
}

    </style>
@endsection
