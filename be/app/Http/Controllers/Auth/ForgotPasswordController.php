<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\ForgotPasswordMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    // Hiển thị form yêu cầu reset mật khẩu
    public function showForgetPasswordForm()
    {
        return view('auth.forgetPassword'); // Hiển thị view quên mật khẩu
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
            Log::warning('Validation failed for email: ' . $request->email, [
                'error' => $validator->errors()->first('email')
            ]);
            return response()->json([
                'error' => $validator->errors()->first('email')
            ], 422);
        }

        // Random mã token
        $token = Str::random(64);

        // Lưu token vào bảng password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // Gửi email chứa mã reset tới gmail của người dùng
        try {
            Log::info('Chuẩn bị gửi email đặt lại mật khẩu đến: ' . $request->email, [
                'token' => $token
            ]);
            Mail::to($request->email)->send(new ForgotPasswordMail($token));
            Log::info('Email đặt lại mật khẩu đã được gửi thành công đến: ' . $request->email);
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email đặt lại mật khẩu đến: ' . $request->email, [
                'error' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'Không thể gửi email. Vui lòng thử lại sau.',
            ], 500);
        }

        return response()->json([
            'message' => 'Mã đặt lại mật khẩu đã được gửi qua email!',
        ], 200);
    }
}