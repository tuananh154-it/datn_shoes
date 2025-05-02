@extends('master')

@section('content')
<style>
    .row{padding-top:60px}
    .table th,.table td{vertical-align:middle;text-align:center}
    .table th{white-space:nowrap;background:#f8f9fa}
    .table td{word-wrap:break-word;max-width:300px}
</style>

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">Chi tiết bình luận</header>

            <table class="table table-striped table-advance table-hover">
                <thead>

                    <tr>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Giới tính</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role ?? 'Chưa có vai trò' }}</td>
                        <td>{{ $user->gender ?? 'Chưa có thông tin' }}</td>
                        <td>{{ $user->address ?? 'Chưa có thông tin' }}</td>
                        <td>{{ $user->phone_number ?? 'Chưa có thông tin' }}</td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td> <!-- Định dạng ngày giờ -->
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-start">
                <a href="{{ route('users.index') }}" class="btn btn-primary">
                    Quay lại
                </a>
            </div>
        </section>
    </div>
</div>
@endsection
