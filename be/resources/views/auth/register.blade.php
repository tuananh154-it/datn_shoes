<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Register</title>
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

        .register-container {
            max-width: 450px;
            margin: 0 auto;
            padding: 40px 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 50px;
        }

        .form-register-heading {
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

        .btn-register {
            background-color: #007bff;
            color: #fff;
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .btn-register:hover {
            background-color: #0056b3;
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
    <div class="container">
        <div class="register-container">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <h2 class="form-register-heading">Create an Account</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" required>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation">Confirm Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" required>
                    </div>

                    <!-- Gender -->
                    {{-- <div>
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" required>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label for="date_of_birth">Date of Birth</label>
                        <input id="date_of_birth" type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address">Address</label>
                        <input id="address" type="text" name="address" value="{{ old('address') }}" required>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone_number">Phone Number</label>
                        <input id="phone_number" type="text" name="phone_number" value="{{ old('phone_number') }}" required>
                    </div> --}}

                    <div>
                        <button type="submit">Register</button>
                    </div>
                </form>
                <!-- Register Button -->
                <button class="btn btn-register" type="submit">Register</button>

                <!-- Error Message for Email -->
                @error('email')
                <div class="text-danger mt-3">{{ $message }}</div>
                @enderror

                <!-- Login Link -->
                <div class="registration">
                    Already have an account?
                    <a href="{{ route('login') }}">Login here</a>
                </div>
            </form>
        </div>
    </div>

    <!-- JS libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
