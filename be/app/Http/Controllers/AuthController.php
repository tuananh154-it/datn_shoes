<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;  // import JWTAuth nếu chưa có
use App\Http\Controllers\Controller; // import đúng lớp Controller
use App\Enums\RoleEnum;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;



class AuthController extends Controller
{
    use AuthenticatesUsers;
    // Đăng ký
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->syncRoles(RoleEnum::USER);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    // Đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            if (!$request->wantsJson()) {
                $request->session()->regenerate();

                $this->clearLoginAttempts($request);
            }

            if ($response = $this->authenticated($request, $this->guard()->user())) {
                return $response;
            }


            return $request->wantsJson()
                ? new JsonResponse(['token' => JWTAuth::attempt($credentials)])
                : redirect()->intended($this->redirectPath());
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    // Thông tin người dùng
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function showLoginForm()
    {
        return view('auth.login'); // Hoặc trả về bất kỳ view nào bạn muốn
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }
}
