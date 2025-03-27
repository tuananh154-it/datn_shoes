<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra xem người dùng có phải là admin không
        if (Auth::check() && Auth::user()->role == 'superadmin') {
            // Nếu người dùng là admin, tiếp tục với request
            return $next($request);
        }

        // Nếu người dùng không phải admin, chuyển hướng họ đến trang chủ
        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này');
    }
}
