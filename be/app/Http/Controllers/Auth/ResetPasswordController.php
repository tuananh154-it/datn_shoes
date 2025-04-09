<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    // API đặt lại mật khẩu với token
    public function submitResetPasswordForm(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }

        // Checking token
        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        // Kiểm tra xem token có tồn tại và chưa hết hạn
        if (!$resetToken) {
            return response()->json([
                'error' => 'Mã đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.'
            ], 400);
        }

        // Cập nhật mật khẩu mới cho người dùng trong bảng users
        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        // Xóa token sau khi sử dụng
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'message' => 'Mật khẩu của bạn đã được đặt lại thành công!'
        ], 200);
    }
}
