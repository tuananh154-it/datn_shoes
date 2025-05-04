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
        // Kiểm tra xem người dùng có phải là admin hoặc superadmin không
        if (Auth::check() && in_array(Auth::user()->role, ['admin', 'superadmin', 'staff'])) {
            // Nếu người dùng có role 'admin' hoặc 'superadmin', tiếp tục với request
            return $next($request);
        }

        // Nếu người dùng không phải admin hoặc superadmin, chuyển hướng họ đến trang chủ
        return redirect()->route('home')->with('error', 'Bạn không có quyền truy cập trang này');
    }
}
