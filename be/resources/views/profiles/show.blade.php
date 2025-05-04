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
    .row {
        padding-top: 10%;
        display: flex;
        justify-content: center;
        margin-left:28%;
    }

    /* Card profile */
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        width: 100%;
        max-width: 600px;
        overflow: hidden;
    }

    /* Header của card */
    .card-header {
        background-color: #007bff;
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

    /* Nút chỉnh sửa */
    .btn-warning {
        background-color: #ffc107;
        border: none;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 0.9rem;
        transition: background-color 0.3s ease;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    /* Thông báo thành công */
    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Thông tin người dùng
            </header>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-body">
                <div class="mb-3">
                    <p><strong>Tên:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Giới tính:</strong> {{ $user->gender }}</p>
                    <p><strong>Ngày sinh:</strong> {{ $user->date_of_birth }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $user->address }}</p>
                    <p><strong>Số điện thoại:</strong> {{ $user->phone_number }}</p>
                </div>

                <div class="mb-3">
                    <!-- Nút chỉnh sửa profile -->
                    <a href="{{ route('profiles.edit', $user->id) }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-pencil"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
