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
