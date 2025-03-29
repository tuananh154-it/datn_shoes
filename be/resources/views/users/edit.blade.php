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
                    Chỉnh sửa người dùng
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

                    <!-- Form cập nhật người dùng -->
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

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
                            <label for="phone">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-lg mb-2"
                                placeholder="Số điện thoại" value="{{ old('phone', $user->phone) }}">
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
                            <label for="birthdate">Ngày sinh</label>
                            <input type="date" name="birthdate" id="birthdate" class="form-control form-control-lg mb-2"
                                value="{{ old('birthdate', $user->birthdate) }}">
                        </div>
                        <!-- Vai trò -->
                        <div class="form-group">
                            <label for="roles">Vai Trò</label>
                            <div>
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" id="role-{{ $role->id }}"
                                            value="{{ $role->name }}"
                                            {{ $user->roles->contains('name', $role->name) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="role-{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Nút cập nhật -->
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
