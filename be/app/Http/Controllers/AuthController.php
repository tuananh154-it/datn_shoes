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
            'password' => 'required|string|min:8|confirmed',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'gender'        => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address'       => $request->address,
            'phone_number'  => $request->phone_number,
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

            $user = Auth::user(); 

            if (in_array($user->role, ['admin', 'superadmin'])) {
                return redirect()->route('articles.index')->with('success', 'Vào thành công!');
            }

            return redirect()->intended(route('login'))->with('error', 'Bạn không có quyền truy cập!');
        }

        return back()->withErrors(['email' => 'Thông tin đăng nhập không chính xác'])->onlyInput('email');
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
            if ($request->expectsJson()) {
                return response()->json(['token' => JWTAuth::attempt($credentials)]);
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
        return view('auth.login');
    }
    public function showRegisterForm()
    {
        return view('auth.register');
    }
}
