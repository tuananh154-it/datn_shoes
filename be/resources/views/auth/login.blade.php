<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Quản Trị</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <style>
        body {
            /* background-color: #f0f2f5; */
            font-family: 'Arial', sans-serif;

            /* background: url(''); */
            background: url('/client/flatlab-4/img/ok.jpeg') no-repeat center center fixed;
background-size: cover;

            background-size: cover;  /* Làm cho ảnh nền phủ toàn bộ trang */
        }
        body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5); /* Lớp mờ đen với 50% opacity */
    z-index: -1; /* Đảm bảo lớp mờ không che phủ nội dung */
}

        .login-container {
            /* padding: 200px; */
            background-color: rgba(255, 255, 255, 0.8); /* Thêm độ trong suốt */
    box-shadow: 0 4px 15px rgba(0,.2 0.2, 0.2, 0.2); /* Thêm bóng cho form */
            max-width: 450px;
            margin: 0 auto;
            padding: 40px 20px;
            /* background-color: #fff; */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 200px;
        }

        .form-signin-heading {
            font-size: 29px;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-control {
            border-radius: 30px;
            margin-bottom: 15px;
            padding: 10px 20px;
        }

        .btn-login {
            background-color: #FF6C60;
            color: #fff;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .checkbox {
            font-size: 14px;
        }

        .registration {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .registration a {
            font-weight: bold;
            color: #007bff;
        }

        .registration a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="container">
        <div class="login-container">
            <form method="POST" action="{{ route('login') }}" class="form-signin">
                @csrf
                <h2 class="form-signin-heading">Đăng nhập ngay</h2>

                <!-- Email Field -->
                <input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>

                <!-- Password Field -->
                <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>

                <!-- Remember Me and Forgot Password -->
                <div class="checkbox">
                    <input type="checkbox" value="remember-me"> Ghi nhớ tôi
                    <span class="pull-right">
                        <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                    </span>
                </div>

                <!-- Sign-in Button -->
                <button class="btn btn-login" type="submit">Đăng nhập</button>
            </form>
        </div>
    </div>

    <!-- JS libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
