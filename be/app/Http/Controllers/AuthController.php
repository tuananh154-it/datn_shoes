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
            // 'password' => 'required|string|min:6|confirmed',
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công.');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // 'gender' => 'nullable|string',
            // 'date_of_birth' => 'nullable|date',
            // 'address' => 'nullable|string|max:255',
            // 'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            // 'gender'        => $request->gender,
            // 'date_of_birth' => $request->date_of_birth,
            // 'address'       => $request->address,
            // 'phone_number'  => $request->phone_number,
        ]);

        $user->syncRoles(RoleEnum::USER);

        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function dangnhap(Request $request)
    {
        // Xác thực đầu vào
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Lấy thông tin đăng nhập từ request
        $credentials = $request->only('email', 'password');

        // Kiểm tra nếu tài khoản tồn tại
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Nếu không có tài khoản với email này
            return back()->withErrors(['email' => 'Tài khoản không tồn tại.'])->onlyInput('email');
        }

        // Kiểm tra thông tin đăng nhập
        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công, tái tạo session
            $request->session()->regenerate();

            $user = Auth::user();
            // Kiểm tra vai trò của người dùng và chuyển hướng
            if (in_array($user->role, ['admin', 'superadmin'])) {
                return redirect()->route('dashboards.index')->with('success', 'Vào thành công!');
            }

            return redirect()->route('login')->withErrors(['email' => 'Bạn không có quyền truy cập!'])->onlyInput('email');
        }

        // Đăng nhập thất bại, quay lại với thông báo lỗi
        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác'])->onlyInput('email');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'message' => 'Đăng nhập thành công!',
            'token' => $token,
            'user' => Auth::user(),
        ]);
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
