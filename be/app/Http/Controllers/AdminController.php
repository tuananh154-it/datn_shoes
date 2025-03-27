<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
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