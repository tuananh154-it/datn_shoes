@extends('master')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Danh sách thành viên</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Ngày tham gia</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    {{ $user->role == 'admin' ? 'Admin' : 'User' }}
                </td>
                <td>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'Chưa cập nhật' }}</td>

               
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Quay lại</a>
</div>
@endsection
