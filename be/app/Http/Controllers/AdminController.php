<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Comment;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        // Lấy tổng số đơn hàng, sản phẩm, thành viên và đánh giá
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalReviews = Comment::count();

        // Lấy dữ liệu doanh thu theo tháng của năm hiện tại
        $monthlyRevenue = Order::whereYear('created_at', Carbon::now()->year)
            ->where('status', 'completed')
            ->whereNotNull('created_at') // Tránh lỗi khi created_at bị null
            ->selectRaw('SUM(total_price) as revenue, MONTH(created_at) as month')
            ->groupBy('month')
            ->pluck('revenue', 'month');

        // Lấy dữ liệu trạng thái đơn hàng của năm hiện tại
        $orderStatus = Order::whereYear('created_at', Carbon::now()->year)
            ->whereNotNull('created_at')
            ->selectRaw('COUNT(id) as count, status')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('dashboards.index', compact(
            'totalOrders', 'totalProducts', 'totalUsers', 'totalReviews', 'monthlyRevenue', 'orderStatus'
        ));
    }

    public function filterStatistics(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month', null); // Mặc định không lọc theo tháng

        if (!$month) {
            // Nếu chỉ lọc theo năm, lấy doanh thu cả 12 tháng
            $monthlyRevenue = Order::whereYear('created_at', $year)
                ->where('status', 'completed')
                ->whereNotNull('created_at')
                ->selectRaw('SUM(total_price) as revenue, MONTH(created_at) as month')
                ->groupBy('month')
                ->pluck('revenue', 'month');
        } else {
            // Nếu lọc theo cả tháng và năm, chỉ lấy doanh thu của tháng đó
            $monthlyRevenue = Order::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('status', 'completed')
                ->whereNotNull('created_at')
                ->sum('total_price'); // Trả về tổng doanh thu của tháng đó
        }

        // Lấy số đơn hàng theo trạng thái
        $orderStatus = Order::whereYear('created_at', $year)
            ->when($month, function ($query) use ($month) {
                return $query->whereMonth('created_at', $month);
            })
            ->whereNotNull('created_at')
            ->selectRaw('COUNT(id) as count, status')
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json([
            'monthlyRevenue' => $monthlyRevenue,
            'orderStatus' => $orderStatus
        ]);
    }

    public function orders()
    {
        $orders = Order::latest()->paginate(10);
        return view('dashboards.orders', compact('orders'));
    }

    public function reviews()
    {
        $reviews = Comment::latest()->paginate(10);
        return view('dashboards.reviews', compact('reviews'));
    }

    public function products()
    {
        $products = Product::latest()->paginate(10);
        return view('dashboards.products', compact('products'));
    }

    public function users()
    {
        $users = User::latest()->get();
        return view('dashboards.users', compact('users'));
    }
}
