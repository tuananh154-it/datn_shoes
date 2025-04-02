@extends('master')

@section('content')
<style>
    /* Container của form */
    .container {
        max-width: 800px;
        margin-top: 40px;
        background-color: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Tiêu đề chính của form */
    h1 {
        font-size: 2rem;
        font-weight: 600;
        color: #333;
        text-align: center;
        margin-bottom: 20px;
    }

    /* Nhãn (label) */
    .form-label {
        font-weight: bold;
        color: #333;
    }

    /* Input text */
    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 10px;
        font-size: 1rem;
        width: 100%;
        box-sizing: border-box;
    }

    /* Các nhóm quyền (header) */
    h4 {
        font-size: 1.2rem;
        font-weight: 500;
        margin-top: 20px;
        color: #333;
    }

    /* Các checkbox */
    .form-check {
        margin-bottom: 10px;
    }

    .form-check-input {
        margin-right: 10px;
    }

    /* Nút submit */
    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 12px 20px;
        font-size: 1rem;
        font-weight: bold;
        border-radius: 8px;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Các dòng phân cách giữa các nhóm quyền */
    hr {
        border: 1px solid #ddd;
        margin: 20px 0;
    }

    /* Chiều rộng của từng cột checkbox */
    .col-md-3 {
        padding: 10px;
    }

    /* Các checkbox trong một dòng */
    .row {
        margin-bottom: 20px;
    }

    /* Hiệu ứng hover cho checkbox */
    .form-check:hover {
        background-color: #f8f9fa;
    }

    /* Kiểm tra nếu có checkbox được chọn */
    .form-check-input:checked {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>

<div class="container">
    <h1>Chỉnh sửa vai trò</h1>
    <form action="{{ route('roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Tên vai trò -->
        <div class="mb-3">
            <label for="name" class="form-label">Tên vai trò</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" required>
        </div>

        <!-- Danh sách quyền -->
        <div class="mb-3">
            <label for="permissions" class="form-label">Quyền hạn</label>

            @foreach ($groupedPermissions as $group => $permissions)
                <!-- Hiển thị tên nhóm quyền -->
                @if ($group) <!-- Kiểm tra nếu nhóm không rỗng -->
                    <h4>{{ ucwords(str_replace('_', ' ', $group)) }}</h4>  <!-- Tiêu đề nhóm quyền -->
                @endif

                <div class="row">
                    @foreach ($permissions as $permission)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="permissions[]"
                                    id="permission-{{ $permission->id }}"
                                    value="{{ $permission->id }}"
                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>  <!-- Tách các nhóm quyền -->
            @endforeach
        </div>


        <button type="submit" class="btn btn-warning ">Cập nhập  </button>

</div>
@endsection
