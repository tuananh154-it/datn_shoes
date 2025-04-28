@extends('master')

@section('content')
    <style>
        /* public/css/user-form.css */

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .container {
            padding: 60px 20px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .form-wrapper {
            background-color: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            padding: 20px;
        }

        .card-header h4 {
            margin: 0;
        }

        .card-body {
            padding: 20px;
        }

        .alert {
            background-color: #f8d7da;
            border: 1px solid #f5c2c7;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            color: #842029;
        }

        .form-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .form-column {
            flex: 1;
            min-width: 300px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .radio-group label {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .submit-wrapper {
            text-align: center;
            margin-top: 30px;
        }

        .submit-wrapper button {
            padding: 10px 30px;
            font-size: 16px;
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-wrapper button:hover {
            background-color: #218838;
        }

        .back-button-wrapper {
            margin-bottom: 20px;
        }

        .back-button {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 8px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .back-button:hover {
            background-color: rgb(149, 154, 158);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .back-button-wrapper {
            padding-top: 20px;
            margin-left: auto;
        }

        .back-button {
            display: inline-block;
            background-color: #6c757d;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .back-button:hover {
            background-color: #5a6268;
        }
    </style>

    <div class="container">
        <div class="form-wrapper">
            <div class="card">
                <div class="card-header">
                    <h4>Thêm Người Dùng Mới</h4>
                    <div class="back-button-wrapper">
                        <a href="{{ url()->previous() }}" class="back-button">← Quay lại</a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        @if ($errors->any())
                            <div class="alert">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="form-grid">
                            <!-- Cột trái -->
                            <div class="form-column">
                                <div class="form-group">
                                    <label for="name">Tên</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="password">Mật khẩu</label>
                                    <input type="password" name="password" required>
                                </div>

                                <div class="form-group">
                                    <label for="gender">Giới tính</label>
                                    <select name="gender" required>
                                        <option value="" disabled selected>Gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Cột phải -->
                            <div class="form-column">
                                <div class="form-group">
                                    <label for="date_of_birth">Ngày sinh</label>
                                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="address">Địa chỉ</label>
                                    <input type="text" name="address" value="{{ old('address') }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="phone_number">Số điện thoại</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Vai trò</label>
                                    <div class="radio-group">
                                        @php
                                            $fixedRoles = ['user', 'staff', 'admin', 'superadmin'];
                                        @endphp
                                        @foreach ($fixedRoles as $role)
                                            <label>
                                                <input type="radio" name="role" value="{{ $role }}" {{ old('role') == $role ? 'checked' : '' }}>
                                                {{ ucfirst($role) }}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="submit-wrapper">
                            <button type="submit">Thêm Người Dùng</button>

                        </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection