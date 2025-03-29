@extends('master')

@section('content')
    <style>
        .row {
            padding-top: 60px;
        }
    </style>
    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">
                    Thêm Người Dùng mới
                </header>
                <div class="card-body">
                    <!-- Form gửi đến route products.store với phương thức POST -->
                    <form action="{{ route('users.store') }}" method="POST">

                        @csrf

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

                        <!-- Tên người dùng -->
                        <div class="form-group">
                            <label for="name">Tên</label>
                            <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required  class="form-control form-control-lg mb-2">
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"  class="form-control form-control-lg mb-2" required>
                        </div>

                        <!-- Mật khẩu -->
                        <div class="form-group">
                            <label for="password">Mật Khẩu</label>
                            <input type="password" name="password" placeholder="Password"  class="form-control form-control-lg mb-2" required>
                        </div>

                        <!-- Giới tính -->
                        <div class="form-group">
                            <label for="gender">Giới Tính</label>
                            <select name="gender" required class="form-control">
                                <option value="" disabled selected>Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Ngày sinh -->
                        <div class="form-group">
                            <label for="date_of_birth">Ngày Sinh</label>
                            <input type="date" name="date_of_birth" placeholder="Date of Birth"  class="form-control form-control-lg mb-2" value="{{ old('date_of_birth') }}" required>
                        </div>

                        <!-- Địa chỉ -->
                        <div class="form-group">
                            <label for="address">Địa chỉ</label>
                            <input type="text" name="address" placeholder="Address"  class="form-control form-control-lg mb-2" value="{{ old('address') }}" required>
                        </div>

                        <!-- Số điện thoại -->
                        <div class="form-group">
                            <label for="phone_number">Số điện thoại</label>
                            <input type="text" name="phone_number" placeholder="Phone Number"  class="form-control form-control-lg mb-2" value="{{ old('phone_number') }}" required>
                        </div>
    
                        <div class="form-group">
                            <label for="roles">Vai Trò</label>
                            <div>
                                @foreach ($roles as $role)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" id="role-{{ $role->id }}" value="{{ $role->name }}">
                                        <label class="form-check-label" for="role-{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <!-- Nút Thêm -->
                        <button type="submit" class="btn btn-success">Thêm Người Dùng</button>
                    </form>
                </div>
            </section>

            <script>
                ClassicEditor
                    .create(document.querySelector('#description'), {
                        ckfinder: {
                            uploadUrl: '{{ route('ckeditor.upload') }}',
                        },
                        debug: 'all',
                        toolbar: [
                            'undo', 'redo', '|', 'bold', 'italic', '|', 'link', 'imageUpload', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable'
                        ]
                    })
                    .catch(error => {
                        console.error(error);
                    });
            </script>
        </div>
    </div>
@endsection
