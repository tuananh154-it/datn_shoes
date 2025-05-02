<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\OrderDetail;
use Carbon\CarbonPeriod;

class AdminController extends Controller
{
    public function users()
    {
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

    public function products()
    {
        $categories = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->leftJoin('product_details', 'products.id', '=', 'product_details.product_id')
            ->select(
                'categories.id',
                'categories.name as category_name',
                DB::raw('COALESCE(SUM(product_details.quantity), 0) as total_stock')
            )
            ->groupBy('categories.id', 'categories.name')
            ->get();

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

    public function index(Request $request)
{
    $filterType = $request->input('filter-type', null); // null nếu dùng bộ lọc khoảng thời gian
    $labels = [];
    $revenues = [];

    if ($request->has(['from_date', 'to_date']) && $request->filled(['from_date', 'to_date'])) {
        // Bộ lọc khoảng thời gian
        $fromDate = Carbon::parse($request->input('from_date'))->startOfDay();
        $toDate = Carbon::parse($request->input('to_date'))->endOfDay();

        $period = CarbonPeriod::create($fromDate, $toDate);
        foreach ($period as $date) {
            $formatted = $date->format('Y-m-d');
            $labels[] = $formatted;
            $revenues[$formatted] = 0;
        }

        $orders = Order::whereBetween('created_at', [$fromDate, $toDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
            ->groupBy('date')
            ->pluck('revenue', 'date');

        foreach ($orders as $date => $revenue) {
            $revenues[$date] = $revenue;
        }

    } elseif ($filterType === 'day') {
        $date = $request->input('selected-date', Carbon::today()->toDateString());

        for ($i = 0; $i < 24; $i++) {
            $labels[] = $i . ":00";
            $revenues[$i] = 0;
        }

        $orders = Order::whereDate('created_at', $date)
            ->selectRaw('HOUR(created_at) as hour, SUM(total_price) as revenue')
            ->groupBy('hour')
            ->pluck('revenue', 'hour');

        foreach ($orders as $hour => $revenue) {
            $revenues[$hour] = $revenue;
        }

    } elseif ($filterType === 'week') {
        $year = $request->input('selected-year-week', Carbon::now()->year);
        $week = $request->input('selected-week', Carbon::now()->weekOfYear);
        $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek();
        $endOfWeek = $startOfWeek->copy()->endOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i)->format('Y-m-d');
            $labels[] = $date;
            $revenues[$date] = 0;
        }

        $orders = Order::whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(created_at) as day, SUM(total_price) as revenue')
            ->groupBy('day')
            ->pluck('revenue', 'day');

        foreach ($orders as $day => $revenue) {
            if (isset($revenues[$day])) {
                $revenues[$day] = $revenue;
            }
        }

    } elseif ($filterType === 'month') {
        $month = $request->input('selected-month', Carbon::now()->month);
        $year = $request->input('selected-year-month', Carbon::now()->year);
        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $weeksInMonth = ceil($endOfMonth->day / 7);

        for ($i = 1; $i <= $weeksInMonth; $i++) {
            $labels[] = "Tuần $i";
            $revenues[$i] = 0;
        }

        $orders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->selectRaw('WEEK(created_at, 3) - WEEK(?, 3) + 1 as week, SUM(total_price) as revenue', [$startOfMonth])
            ->groupBy('week')
            ->pluck('revenue', 'week');

        foreach ($orders as $week => $revenue) {
            if (isset($revenues[$week])) {
                $revenues[$week] = $revenue;
            }
        }

    } elseif ($filterType === 'year' || is_null($filterType)) {
        $year = $request->input('selected-year', Carbon::now()->year);

        for ($i = 1; $i <= 12; $i++) {
            $labels[] = "Tháng $i";
            $revenues[$i] = 0;
        }

        $orders = Order::whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->groupBy('month')
            ->pluck('revenue', 'month');

        foreach ($orders as $month => $revenue) {
            if (isset($revenues[$month])) {
                $revenues[$month] = $revenue;
            }
        }
    }

    return view('dashboards.index', [
        'labels' => array_values($labels),
        'revenues' => array_values($revenues),
        'filterType' => $filterType,
    ]);
}
// thống kê trạng thái đơn hàng
public function orderStatus()
{
    $orders = DB::table('orders')
        ->select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->get();

    $statuses = [
        'waiting_for_confirmation' => 'Chờ xác nhận',
        'waiting_for_pickup' => 'Chờ lấy hàng',
        'waiting_for_delivery' => 'Đang giao',
        'delivered' => 'Đã giao',
        'returned' => 'Đã hoàn trả',
        'cancelled' => 'Đã hủy',
    ];

    $labels = [];
    $totals = [];
    $totalOrders = $orders->sum('total');

    foreach ($orders as $order) {
        $labels[] = $statuses[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status));
        $totals[] = $order->total;
    }

    return view('dashboards.orders ', compact('labels', 'totals', 'orders', 'statuses', 'totalOrders'));
}


    
}
