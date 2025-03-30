<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\OrderDetail;



class AdminController extends Controller
{
    // thống kê tài khoản 
    public function users()
    {
        // Đếm số lượng tài khoản theo vai trò
        $accountStats = DB::table('users')
            ->select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get();

        return view('dashboards.users', compact('accountStats'));
    }

    public function getAccountStatsData()
    {
        $accountStats = DB::table('users')
            ->select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role');

        return response()->json($accountStats);
    }
    //thống kê sản phẩm 
    public function products()
    {
        // Lấy tất cả danh mục, kể cả danh mục không có sản phẩm
        $categories = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('product_details', 'products.id', '=', 'product_details.product_id')
            ->select(
                'categories.id',
                'categories.name as category_name',
                DB::raw('COALESCE(SUM(product_details.quantity), 0) as total_stock') // Nếu NULL thì thay bằng 0
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

        // Lấy danh sách chi tiết sản phẩm trong từng danh mục
        $productDetails = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('product_details', 'products.id', '=', 'product_details.product_id')
            ->leftJoin('sizes', 'product_details.size_id', '=', 'sizes.id')
            ->leftJoin('colors', 'product_details.color_id', '=', 'colors.id')
            ->select(
                'categories.name as category_name',
                'products.name as product_name',
                'sizes.name as size',
                'colors.name as color',
                'product_details.quantity'
            )
            ->orderBy('categories.name')
            ->get();

        return view('dashboards.product', compact('categories', 'productDetails'));
    }

    //thống kê top 10 sp
    public function top10(Request $request)
{
    $year = $request->input('year', date('Y'));
    $month = $request->input('month', null);
    $day = $request->input('day', null);

    $query = DB::table('order_details as od')
        ->join('product_details as pd', 'od.product_detail_id', '=', 'pd.id')
        ->join('products as p', 'pd.product_id', '=', 'p.id')
        ->join('orders as o', 'od.order_id', '=', 'o.id')
        ->select(
            'p.id as product_id',
            'p.name as product_name',
            DB::raw('SUM(od.quantity) as total_sold'),
            DB::raw('SUM(od.total_price) as total_revenue')
        )
        ->whereYear('o.created_at', $year);
    
    if ($month) {
        $query->whereMonth('o.created_at', $month);
    }
    if ($day) {
        $query->whereDay('o.created_at', $day);
    }

    $topProducts = $query->groupBy('p.id', 'p.name')
        ->orderByDesc('total_sold')
        ->limit(10)
        ->get();
    
    return view('dashboards.top10', compact('topProducts', 'year', 'month', 'day'));
}

    //Thống kê doanh thu 
    public function index(Request $request)
    {
        $filterType = $request->input('filter-type', 'year'); // Mặc định là năm

        $labels = [];
        $revenues = [];

        if ($filterType === 'day') {
            // Lấy ngày được chọn
            $date = $request->input('selected-date', Carbon::today()->toDateString());

            // Tạo đủ 24 giờ
            for ($i = 0; $i < 24; $i++) {
                $labels[] = $i . ":00";
                $revenues[$i] = 0; // Mặc định doanh thu là 0
            }

            // Lấy doanh thu từng giờ
            $orders = Order::whereDate('created_at', $date)
                ->selectRaw('HOUR(created_at) as hour, SUM(total_price) as revenue')
                ->groupBy('hour')
                ->pluck('revenue', 'hour');

            // Gán dữ liệu vào mảng
            foreach ($orders as $hour => $revenue) {
                $revenues[$hour] = $revenue;
            }
        } elseif ($filterType === 'week') {
            // Lấy năm và tuần
            $year = $request->input('selected-year-week', Carbon::now()->year);
            $week = $request->input('selected-week', Carbon::now()->weekOfYear);

            // Lấy ngày đầu và cuối tuần
            $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $endOfWeek = $startOfWeek->copy()->endOfWeek();

            // Tạo đủ 7 ngày
            for ($i = 0; $i < 7; $i++) {
                $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
                $labels[] = $date;
                $revenues[$i] = 0;
            }

            // Lấy doanh thu theo ngày
            $orders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->selectRaw('DATE(created_at) as day, SUM(total_price) as revenue')
                ->groupBy('day')
                ->pluck('revenue', 'day');

            // Gán vào mảng
            foreach ($orders as $day => $revenue) {
                $index = array_search($day, $labels);
                if ($index !== false) {
                    $revenues[$index] = $revenue;
                }
            }
        } elseif ($filterType === 'month') {
            // Lấy tháng được chọn
            $month = $request->input('selected-month', Carbon::now()->month);
            $year = $request->input('selected-year-month', Carbon::now()->year);

            // Xác định các tuần trong tháng
            $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
            $endOfMonth = $startOfMonth->copy()->endOfMonth();

            // Xác định số tuần
            $weeksInMonth = ceil($endOfMonth->day / 7);

            for ($i = 1; $i <= $weeksInMonth; $i++) {
                $labels[] = "Tuần $i";
                $revenues[$i - 1] = 0;
            }

            // Lấy doanh thu theo tuần
            $orders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->selectRaw('WEEK(created_at, 3) - WEEK(?, 3) + 1 as week, SUM(total_price) as revenue', [$startOfMonth])
                ->groupBy('week')
                ->pluck('revenue', 'week');

            foreach ($orders as $week => $revenue) {
                if (isset($revenues[$week - 1])) {
                    $revenues[$week - 1] = $revenue;
                }
            }
        } elseif ($filterType === 'year') {
            // Lấy năm được chọn
            $year = $request->input('selected-year', Carbon::now()->year);

            // Tạo đủ 12 tháng
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = "Tháng $i";
                $revenues[$i - 1] = 0;
            }

            // Lấy doanh thu từng tháng
            $orders = Order::whereYear('created_at', $year)
                ->selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
                ->groupBy('month')
                ->pluck('revenue', 'month');

            foreach ($orders as $month => $revenue) {
                $revenues[$month - 1] = $revenue;
            }
        }

        return view('dashboards.index', compact('labels', 'revenues', 'filterType'));
    }
}
