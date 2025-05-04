@extends('master')

@section('content')
<style>
    /* Sử dụng font hiện đại và nền nhẹ */
    body {
        font-family: 'Roboto', sans-serif;
        background-color: #f0f2f5;
    }

    .row {
        padding-top: 60px;
    }

    /* Card chứa form được làm nổi bật với hiệu ứng bay lên khi hover */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        background-color: #ffffff;
        margin: 0 auto;
        max-width: 600px;
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    /* Header card với gradient hiện đại */
    .card-header {
        background: linear-gradient(135deg, #007bff, #00c6ff);
        color: #fff;
        padding: 20px;
        font-size: 1.8rem;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        text-align: center;
    }

    /* Padding cho nội dung bên trong card */
    .card-body {
        padding: 30px;
    }

    /* Label đẹp, dễ đọc */
    .form-group label {
        font-weight: 600;
        color: #343a40;
        margin-bottom: 8px;
    }

    /* Input, select được thiết kế tinh tế */
    .form-control {
        border-radius: 8px;
        height: 45px;
        padding: 10px 15px;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.25);
    }

    .form-control-lg {
        font-size: 1rem;
    }

    .mb-2 {
        margin-bottom: 1rem;
    }

    /* Nút cập nhật với gradient và hiệu ứng hover mượt mà */
    .btn-primary {
        background: linear-gradient(135deg, #007bff, #00c6ff);
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 1rem;
        transition: background 0.3s ease;
        width: 100%;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #0062cc, #008fcf);
    }

    /* Thiết kế thông báo lỗi và thành công */
    .alert {
        padding: 12px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <section class="card">
            <header class="card-header">
                Chỉnh sửa thông tin người dùng
            </header>
            <div class="card-body">
                <!-- Hiển thị lỗi nếu có -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Hiển thị thông báo thành công nếu có -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form chỉnh sửa thông tin người dùng -->
                <form action="{{ route('profiles.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Tên người dùng -->
                    <div class="form-group">
                        <label for="name">Tên người dùng</label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg mb-2"
                               placeholder="Tên người dùng" value="{{ old('name', $user->name) }}" required>
                    </div>

                    <!-- Email -->
                    {{-- <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control form-control-lg mb-2"
                               placeholder="Email" value="{{ old('email', $user->email) }}" required>
                    </div> --}}

                    <!-- Mật khẩu mới (nếu cần thay đổi) -->
                    {{-- <div class="form-group">
                        <label for="password">Mật khẩu mới (Để trống nếu không thay đổi)</label>
                        <input type="password" name="password" id="password" class="form-control form-control-lg mb-2"
                               placeholder="Mật khẩu mới">
                    </div> --}}

                    <!-- Số điện thoại -->
                    <div class="form-group">
                        <label for="phone_number">Số điện thoại</label>
                        <input type="text" name="phone_number" id="phone_number" class="form-control form-control-lg mb-2"
                               placeholder="Số điện thoại" value="{{ old('phone_number', $user->phone_number) }}">
                    </div>

                    <!-- Giới tính -->
                    <div class="form-group">
                        <label for="gender">Giới tính</label>
                        <select name="gender" id="gender" class="form-control form-control-lg mb-2">
                            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                            <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                        </select>
                    </div>

                    <!-- Ngày sinh -->
                    <div class="form-group">
                        <label for="date_of_birth">Ngày sinh</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control form-control-lg mb-2"
                               value="{{ old('date_of_birth', $user->date_of_birth) }}">
                    </div>

                    <!-- Địa chỉ -->
                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <input type="text" name="address" id="address" class="form-control form-control-lg mb-2"
                               placeholder="Địa chỉ" value="{{ old('address', $user->address) }}">
                    </div>

                    <!-- Nút cập nhật -->
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
