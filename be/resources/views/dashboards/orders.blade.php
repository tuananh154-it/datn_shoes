@extends('master')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 font-weight-bold mt-5">📦 Thống Kê Trạng Thái Đơn Hàng</h2>
    
    <!-- Thanh Menu -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboards.index') ? 'active' : '' }}" href="{{ route('dashboards.index') }}">Doanh thu</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboards.product') ? 'active' : '' }}" href="{{ route('dashboards.product') }}">Thống kê sản phẩm </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboards.top10') ? 'active' : '' }}" href="{{ route('dashboards.top10') }}">Top 10 sản phẩm bán chạy </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboards.users') ? 'active' : '' }}" href="{{ route('dashboards.users') }}">Thống kê tài khoản</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboards.orders') ? 'active' : '' }}" href="{{ route('dashboards.orders') }}">Thống kê trạng thái đơn hàng </a>
        </li>
    </ul>

    @if(empty($labels) || empty($totals))
        <div class="alert alert-warning">⚠ Không có dữ liệu đơn hàng.</div>
    @else
        <canvas id="orderStatusChart" style="width: 100%; max-width: 500px; aspect-ratio: 1 / 1; margin: 30px auto; display: block;"></canvas>

        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Trạng thái</th>
                    <th>Số lượng đơn hàng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td class="font-weight-bold">
                            {{ $statuses[$order->status] ?? ucfirst(str_replace('_', ' ', $order->status)) }}
                        </td>
                        <td>{{ $order->total }}</td>
                    </tr>
                @endforeach
                <tr class="fw-bold">
                    <td>Tổng cộng</td>
                    <td>{{ $totalOrders }}</td>
                </tr>
            </tbody>
        </table>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let ctx = document.getElementById("orderStatusChart")?.getContext("2d");

        if (ctx && @json($labels) && @json($totals)) {
            new Chart(ctx, {
                type: "pie",
                data: {
                    labels: @json($labels),
                    datasets: [{
                        data: @json($totals),
                        backgroundColor: [
                            '#f39c12', // Chờ xác nhận
                            '#8e44ad', // Chờ lấy hàng
                            '#3498db', // Đang giao
                            '#2ecc71', // Đã giao
                            '#e67e22', // Đã hoàn trả
                            '#e74c3c', // Đã hủy
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { enabled: true }
                    }
                }
            });
        }
    });
</script>
@endsection
