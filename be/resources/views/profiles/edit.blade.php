@extends('master')

@section('content')
    <style>
        .row {
            padding-top: 60px;
        }
    </style>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    Chỉnh sửa thông tin người dùng
                </header>
                <div class="card-body">
                    <!-- Hiển thị lỗi nếu có -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
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
                        @method('PUT') <!-- Đảm bảo sử dụng phương thức PUT cho việc cập nhật dữ liệu -->

                        <!-- Tên người dùng -->
                        <div class="form-group">
                            <label for="name">Tên người dùng</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg mb-2"
                                placeholder="Tên người dùng" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg mb-2"
                                placeholder="Email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <!-- Mật khẩu mới (nếu cần thay đổi) -->
                        <div class="form-group">
                            <label for="password">Mật khẩu mới (Để trống nếu không thay đổi)</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg mb-2"
                                placeholder="Mật khẩu mới">
                        </div>

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
