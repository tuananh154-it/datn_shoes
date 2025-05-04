<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, $roles)
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Tách danh sách vai trò từ chuỗi vào một mảng
        $rolesArray = explode('|', $roles);

        // Kiểm tra nếu người dùng có một trong những vai trò trong danh sách
        if (!in_array(Auth::user()->role, $rolesArray)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
