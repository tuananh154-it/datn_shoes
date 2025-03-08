<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <style>
    body {
        background-color: #f0f2f5;
        font-family: 'Arial', sans-serif;
    }

    .login-container {
        max-width: 400px;
        margin: 0 auto;
        padding: 40px 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        margin-top: 100px;
    }

    .form-signin-heading {
        font-size: 24px;
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
        background-color: #007bff;
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

    .login-social-link a {
        display: inline-block;
        width: 45%;
        margin: 10px 2%;
        padding: 10px 0;
        text-align: center;
        border-radius: 30px;
        font-size: 16px;
    }

    .facebook {
        background-color: #3b5998;
        color: #fff;
    }

    .twitter {
        background-color: #00acee;
        color: #fff;
    }

    .login-social-link a:hover {
        opacity: 0.8;
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

    <div class="container">
        <div class="login-container">
            <form method="POST" action="{{ route('login') }}" class="form-signin">
                @csrf
                <h2 class="form-signin-heading">Sign in now</h2>

                <!-- Email Field -->
                <input type="email" class="form-control" name="email" placeholder="Email" required autofocus>

                <!-- Password Field -->
                <input type="password" class="form-control" name="password" placeholder="Password" required>

                <!-- Remember Me and Forgot Password -->
                <div class="checkbox">
                    <input type="checkbox" value="remember-me"> Remember me
                    <span class="pull-right">
                        <a data-toggle="modal" href="#myModal">Forgot Password?</a>
                    </span>
                </div>

                <!-- Sign-in Button -->
                <button class="btn btn-login" type="submit">Sign in</button>

                <p class="text-center">or you can sign in via social network</p>
                <div class="login-social-link text-center">
                    <a href="#" class="facebook">
                        <i class="fab fa-facebook"></i> Facebook
                    </a>
                    <a href="#" class="twitter">
                        <i class="fab fa-twitter"></i> Twitter
                    </a>
                </div>

                <!-- Create an Account Link -->
                {{-- <div class="registration">
                    Don't have an account yet?
                    <a href="{{ route('register') }}">Create an account</a>
                </div> --}}
            </form>
        </div>
    </div>

    <!-- Modal for Forgot Password -->
    <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Forgot Password ?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Enter your e-mail address below to reset your password.</p>
                    <input type="text" name="email" placeholder="Email" autocomplete="off"
                        class="form-control placeholder-no-fix">
                </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                    <button class="btn btn-success" type="button">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
