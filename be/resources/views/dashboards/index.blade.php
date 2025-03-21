@extends('master')

@section('content')
<div class="container py-4">
    <h1 class="my-4 text-center fw-bold text-uppercase text-dark">Dashboard</h1>
    {{-- tim kiem  --}}
    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="from_date" class="form-label">Từ ngày:</label>
                <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-4">
                <label for="to_date" class="form-label">Đến ngày:</label>
                <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">Tìm kiếm</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>
    
{{--  thong ke bang--}}
    <div class="row g-4">
        @php
            $stats = [
                ['title' => 'Tổng số sản phẩm  ', 'value' => $totalProducts, 'color' => 'primary'],
                ['title' => 'Sản phẩm trong kho', 'value' => $totalStock, 'color' => 'info'],
                ['title' => 'Sản phẩm đã bán', 'value' => $totalSold, 'color' => 'danger'],
                ['title' => 'Sản phẩm còn lại', 'value' => $totalRemaining, 'color' => 'success'],
            ];
        @endphp
        @foreach ($stats as $stat)
            <div class="col-md-3">
                <div class="card shadow-lg rounded-lg border-0 bg-{{ $stat['color'] }} text-white">
                    <div class="card-body text-center py-4">
                        <h5 class="fw-bold">{{ $stat['title'] }}</h5>
                        <h2 class="fw-bold">{{ $stat['value'] }}</h2>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mt-3">
        @php
            $orders = [
                ['title' => 'Tổng số đơn hàng', 'value' => $totalOrders, 'color' => 'dark'],
                ['title' => 'Doanh thu', 'value' => number_format($revenue, 0, ',', '.') . ' VNĐ', 'color' => 'warning'],
                ['title' => 'Đơn hàng hôm nay', 'value' => $ordersToday, 'color' => 'primary'],
                ['title' => 'Doanh thu hôm nay', 'value' => number_format($revenueToday, 0, ',', '.') . ' VNĐ', 'color' => 'success'],
            ];
        @endphp
        @foreach ($orders as $order)
            <div class="col-md-3">
                <div class="card shadow-lg rounded-lg border-0 bg-{{ $order['color'] }} text-white">
                    <div class="card-body text-center py-4">
                        <h5 class="fw-bold">{{ $order['title'] }}</h5>
                        <h2 class="fw-bold">{{ $order['value'] }}</h2>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-4 mt-3">
        @php
            $users = [
                ['title' => 'Tổng số người dùng', 'value' => $totalUsers, 'color' => 'secondary'],
                ['title' => 'Super Admin', 'value' => $totalSuperAdmin, 'color' => 'dark'],
                ['title' => 'Admin', 'value' => $totalAdmin, 'color' => 'primary'],
                ['title' => 'Khách hàng', 'value' => $totalCustomers, 'color' => 'info'],
            ];
        @endphp
        @foreach ($users as $user)
            <div class="col-md-3">
                <div class="card shadow-lg rounded-lg border-0 bg-{{ $user['color'] }} text-white">
                    <div class="card-body text-center py-4">
                        <h5 class="fw-bold">{{ $user['title'] }}</h5>
                        <h2 class="fw-bold">{{ $user['value'] }}</h2>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="row g-4 mt-3">
        @php
            $vouchers = [
                ['title' => 'Tổng số voucher', 'value' => $totalVouchers, 'color' => 'info'],
                ['title' => 'Voucher đang hoạt động', 'value' => $activeVouchers, 'color' => 'success'],
                ['title' => 'Voucher không hoạt động', 'value' => $inactiveVouchers, 'color' => 'danger'],
            ];
        @endphp
        @foreach ($vouchers as $voucher)
            <div class="col-md-4">
                <div class="card shadow-lg rounded-lg border-0 bg-{{ $voucher['color'] }} text-white">
                    <div class="card-body text-center py-4">
                        <h5 class="fw-bold">{{ $voucher['title'] }}</h5>
                        <h2 class="fw-bold">{{ $voucher['value'] }}</h2>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{-- bieu đồ  --}}
    <div class="row g-4 mt-3">
        <div class="col-md-6">
            <div class="card shadow-lg rounded-lg border-0">
                <div class="card-body">
                    <h5 class="fw-bold text-center">Thống kê sản phẩm</h5>
                    <canvas id="productChart"></canvas>
                </div>
            </div>
        </div>
    
        <div class="col-md-6">
            <div class="card shadow-lg rounded-lg border-0">
                <div class="card-body">
                    <h5 class="fw-bold text-center">Thống kê đơn hàng</h5>
                    <canvas id="orderChart"></canvas>
                </div>
            </div>
        </div>
    
        <div class="col-md-6 mt-3">
            <div class="card shadow-lg rounded-lg border-0">
                <div class="card-body">
                    <h5 class="fw-bold text-center">Thống kê người dùng</h5>
                    <canvas id="userChart"></canvas>
                </div>
            </div>
        </div>
    
        <div class="col-md-6 mt-3">
            <div class="card shadow-lg rounded-lg border-0">
                <div class="card-body">
                    <h5 class="fw-bold text-center">Thống kê voucher</h5>
                    <canvas id="voucherChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- scrip cua bieu đồ  --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Biểu đồ sản phẩm
        new Chart(document.getElementById("productChart"), {
            type: "doughnut",
            data: {
                labels: ["Tổng sản phẩm", "Trong kho", "Đã bán", "Còn lại"],
                datasets: [{
                    data: [{{ $totalProducts }}, {{ $totalStock }}, {{ $totalSold }}, {{ $totalRemaining }}],
                    backgroundColor: ["#007bff", "#17a2b8", "#dc3545", "#28a745"]
                }]
            }
        });

        // Biểu đồ đơn hàng
        new Chart(document.getElementById("orderChart"), {
            type: "bar",
            data: {
                labels: ["Tổng đơn", "Doanh thu", "Đơn hôm nay", "Doanh thu hôm nay"],
                datasets: [{
                    label: "Số lượng",
                    data: [{{ $totalOrders }}, {{ $revenue }}, {{ $ordersToday }}, {{ $revenueToday }}],
                    backgroundColor: ["#343a40", "#ffc107", "#007bff", "#28a745"]
                }]
            }
        });

        // Biểu đồ người dùng
        new Chart(document.getElementById("userChart"), {
            type: "pie",
            data: {
                labels: ["Tổng người dùng", "Super Admin", "Admin", "Khách hàng"],
                datasets: [{
                    data: [{{ $totalUsers }}, {{ $totalSuperAdmin }}, {{ $totalAdmin }}, {{ $totalCustomers }}],
                    backgroundColor: ["#6c757d", "#343a40", "#007bff", "#17a2b8"]
                }]
            }
        });

        // Biểu đồ voucher
        new Chart(document.getElementById("voucherChart"), {
            type: "doughnut",
            data: {
                labels: ["Tổng voucher", "Đang hoạt động", "Không hoạt động"],
                datasets: [{
                    data: [{{ $totalVouchers }}, {{ $activeVouchers }}, {{ $inactiveVouchers }}],
                    backgroundColor: ["#007bff", "#28a745", "#dc3545"]
                }]
            }
        });
    });
</script>
