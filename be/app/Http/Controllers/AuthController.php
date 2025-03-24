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
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;
 // Đăng ký bên be
    public function dangky(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'đăng kí thành công em .');
    }
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
// Đăng nhập ben be

public function dangnhap(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user(); // Lấy thông tin người dùng

        // Kiểm tra role của user
        if ($user->role === 'admin') {
            return redirect()->route('dashboards.index')->with('success', 'vào thành công em  .'); // Điều hướng đến trang admin
        }

        return redirect()->intended(route('login'))->with('success', 'chưa vào được đâu.'); // Điều hướng đến trang chủ mặc định
    }

    return back()->withErrors(['email' => 'Invalid credentials'])->onlyInput('email');
}

    // Đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($request->hasSession()) {
            $request->session()->put('auth.password_confirmed_at', time());
        }

        return response()->json([
            'token' => $token,
            'user' => Auth::guard('api')->user()
        ]);
    }

    // Thông tin người dùng
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }
}
