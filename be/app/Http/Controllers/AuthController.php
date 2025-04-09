<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Enums\RoleEnum;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    public function dangky(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công.');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.unique' => 'Email này đã được sử dụng.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->syncRoles(RoleEnum::USER);
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function dangnhap(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Không cần regenerate session mỗi lần đăng nhập để giảm overhead
            $user = Auth::user();

            // Kiểm tra vai trò bằng middleware sẽ tốt hơn, nhưng giữ logic này nếu cần
            if (in_array($user->role, ['admin', 'superadmin'])) {
                return redirect()->route('dashboards.index')->with('success', 'Đăng nhập thành công!');
            }

            Auth::logout(); // Đăng xuất ngay nếu không có quyền
            return back()->withErrors(['email' => 'Bạn không có quyền truy cập!'])->onlyInput('email');
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng.'])->onlyInput('email');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Email hoặc mật khẩu không đúng.'], 401);
        }

        return response()->json([
            'message' => 'Đăng nhập thành công!',
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            if ($token = JWTAuth::getToken()) {
                JWTAuth::invalidate($token);
            }

            return response()->json(['message' => 'Đăng xuất thành công!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Lỗi đăng xuất: ' . $e->getMessage()], 500);
        }
    }

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