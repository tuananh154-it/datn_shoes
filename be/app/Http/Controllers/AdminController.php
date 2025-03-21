<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // Mặc định lấy toàn bộ dữ liệu
        $queryOrders = Order::query();
        $queryOrderDetails = OrderDetail::query();
        $queryVouchers = Voucher::query();
        $queryUsers = User::query();

        // Nếu có lọc ngày tháng, thì chỉ lấy dữ liệu trong khoảng thời gian đó
        if ($fromDate && $toDate) {
            $queryOrders->whereBetween('created_at', [$fromDate, $toDate]);
            $queryOrderDetails->whereHas('order', function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('created_at', [$fromDate, $toDate]);
            });
            $queryVouchers->whereBetween('created_at', [$fromDate, $toDate]);
            $queryUsers->whereBetween('created_at', [$fromDate, $toDate]);
        }

        // Thống kê sản phẩm (lọc theo ngày nếu có)
        if ($fromDate && $toDate) {
            $totalProducts = Product::whereBetween('created_at', [$fromDate, $toDate])->count();
            $totalStock = ProductDetail::whereHas('product', function ($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })->sum('quantity');
        } else {
            $totalProducts = Product::count();
            $totalStock = ProductDetail::sum('quantity');
        }

        $totalSold = $queryOrderDetails->sum('quantity');
        $totalRemaining = max($totalStock - $totalSold, 0); // Tránh số âm

        // Thống kê đơn hàng (lọc theo ngày nếu có)
        $totalOrders = $queryOrders->count();
        $revenue = $queryOrders->where('status', 'completed')->sum('total_price');

        if ($fromDate && $toDate) {
            $revenue = (clone $queryOrders)->where('status', 'completed')->sum('total_price');
        }

        // Thống kê đơn hàng & doanh thu trong ngày
        $ordersToday = Order::whereDate('created_at', today())->count();
        $revenueToday = Order::whereDate('created_at', today())->sum('total_price');

        // Thống kê voucher (lọc theo ngày nếu có)
        if ($fromDate && $toDate) {
            $totalVouchers = $queryVouchers->count();
            $activeVouchers = (clone $queryVouchers)->where('status', 'active')->count();
            $inactiveVouchers = (clone $queryVouchers)->where('status', 'inactive')->count();
        } else {
            $totalVouchers = Voucher::count();
            $activeVouchers = Voucher::where('status', 'active')->count();
            $inactiveVouchers = Voucher::where('status', 'inactive')->count();
        }

        // Thống kê người dùng (lọc theo ngày nếu có)
        if ($fromDate && $toDate) {
            $totalUsers = $queryUsers->count();
            $totalSuperAdmin = (clone $queryUsers)->where('role', 'superadmin')->count();
            $totalAdmin = (clone $queryUsers)->where('role', 'admin')->count();
            $totalCustomers = (clone $queryUsers)->where('role', 'user')->count();
        } else {
            $totalUsers = User::count();
            $totalSuperAdmin = User::where('role', 'superadmin')->count();
            $totalAdmin = User::where('role', 'admin')->count();
            $totalCustomers = User::where('role', 'user')->count();
        }

        return view('dashboards.index', compact(
            'totalProducts',
            'totalStock',
            'totalSold',
            'totalRemaining',
            'totalOrders',
            'revenue',
            'ordersToday',
            'revenueToday',
            'totalVouchers',
            'activeVouchers',
            'inactiveVouchers',
            'totalUsers',
            'totalSuperAdmin',
            'totalAdmin',
            'totalCustomers',
            'fromDate',
            'toDate'
        ));
    }
}
