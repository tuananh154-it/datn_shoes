<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    // Hiển thị form yêu cầu reset mật khẩu
    public function showForgetPasswordForm()
    {
        return view('auth.forgetPassword');  // Hiển thị view quên mật khẩu
    }

    public function sendResetToken(Request $request)
    {
        // Kiểm tra email hợp lệ + tồn tại trong DB 
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email này chưa được đăng ký trong hệ thống.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first('email')
            ], 422);
        }

        // Random mã token nè
        $token = Str::random(64);

        // Lưu token vào bảng password_resets_tokens
        DB::table('password_resets_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // Gửi email chứa mã reset tới gmail của người dùng
        Mail::to($request->email)->send(new ForgotPasswordMail($token));

        return response()->json([
            'message' => 'Mã đặt lại mật khẩu đã được gửi qua email!',
        ], 200);
    }
}
