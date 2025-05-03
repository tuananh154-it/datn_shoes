@extends('master')

@section('content')
<style>
    /* Đặt font và background toàn cục */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    /* Căn giữa nội dung */
    .container {
        max-width: 80%;
        margin: 0 auto;
        padding-top: 3%;
    }

    /* Card yêu cầu hoàn trả */
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        width: 100%;
        margin-bottom: 30px;
    }

    /* Header của card */
    .card-header {
        background-color: #78CD51;
        color: #fff;
        padding: 20px;
        font-size: 1.25rem;
        font-weight: bold;
        text-align: center;
    }

    /* Nội dung card */
    .card-body {
        padding: 20px;
    }

    /* Các đoạn text thông tin */
    .card-body p {
        font-size: 1rem;
        margin-bottom: 10px;
        line-height: 1.5;
        color: #333;
    }

    /* Table yêu cầu hoàn trả */
    table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    table th, table td {
        padding: 15px 20px;
        text-align: center;
        vertical-align: middle;
    }

    table th {
        background-color: #41CAC0;
        color: white;
        text-align: center;
        border-radius: 5px;
    }

    table td {
        background-color: #f9f9f9;
    }

    table tr:nth-child(even) td {
        background-color: #f9f9f9;
    }

    table tr:hover td {
        background-color: #f1f1f1;
    }

    table tr {
        border-bottom: 1px solid #ddd;
    }

    table tbody tr:last-child {
        border-bottom: none;
    }

    .text-center {
        text-align: center;
    }

    .text-center.mt-3 {
        margin-top: 20px;
    }

    .btn-primary {
        background-color: #41CAC0;
        border-color: #41CAC0;
        padding: 12px 25px;
        font-size: 16px;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #2a7e78;
        border-color: #2a7e78;
    }

</style>

<div class="container">
    <h2 class="text-center mb-4">Thông tin người dùng</h2>
    <section class="card">
        <header class="card-header">
            Chi tiết người dùng
        </header>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
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
                        <td>{{ $user->roles->first()?->name ?? 'Chưa có vai trò' }}</td>
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
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center mt-3">
                <a href="{{ route('users.index') }}" class="btn btn-primary btn-lg">Quay lại danh sách</a>
            </div>
        </div>
    </section>
</div>

@endsection
