@extends('master')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Trang Thống Kê</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">TỔNG SỐ ĐƠN HÀNG ({{ $totalOrders }})</h5>
                    <a href="{{ route('dashboards.orders') }}" class="text-white">Chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">ĐÁNH GIÁ ({{ $totalReviews }})</h5>
                    <a href="{{ route('dashboards.reviews') }}" class="text-white">Chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">SẢN PHẨM ({{ $totalProducts }})</h5>
                    <a href="{{ route('dashboards.products') }}" class="text-white">Chi tiết</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">THÀNH VIÊN ({{ $totalUsers }})</h5>
                    <a href="{{ route('dashboards.users') }}" class="text-white">Chi tiết</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-6">
            <label>Chọn năm</label>
            <input type="number" class="form-control" id="year" value="{{ date('Y') }}">
        </div>
        <div class="col-md-6">
            <label>Chọn tháng</label>
            <input type="month" class="form-control" id="month" value="{{ date('Y-m') }}">
        </div>
    </div>
    <button class="btn btn-primary mt-3" id="filter">Lọc</button>
    <div class="row mt-4">
        <div class="col-md-8">
            <canvas id="revenueChart"></canvas>
            <p class="text-center mt-2">Biểu đồ doanh thu theo tháng</p>
        </div>
        <div class="col-md-4">
            <canvas id="orderStatusChart"></canvas>
            <p class="text-center mt-2">Biểu đồ trạng thái đơn hàng</p>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        function renderCharts(revenueData, orderStatusData) {
            var monthLabels = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"];
            var revenueValues = monthLabels.map(month => revenueData[month] || 0);

            var ctxRevenue = document.getElementById("revenueChart").getContext("2d");
            new Chart(ctxRevenue, {
                type: "bar",
                data: {
                    labels: monthLabels,
                    datasets: [{
                        label: "Doanh thu (VNĐ)",
                        data: revenueValues,
                        backgroundColor: "rgba(54, 162, 235, 0.2)",
                        borderColor: "rgba(54, 162, 235, 1)",
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: "top" }
                    },
                    scales: {
                        x: { title: { display: true, text: "Tháng" } },
                        y: { title: { display: true, text: "Doanh thu (VNĐ)" } }
                    }
                }
            });

            var orderLabels = Object.keys(orderStatusData);
            var orderValues = Object.values(orderStatusData);
            if (orderLabels.length === 0) {
                orderLabels = ["Không có dữ liệu"];
                orderValues = [1];
            }

            var ctxOrderStatus = document.getElementById("orderStatusChart").getContext("2d");
            new Chart(ctxOrderStatus, {
                type: "pie",
                data: {
                    labels: orderLabels,
                    datasets: [{
                        data: orderValues,
                        backgroundColor: ["#007bff", "#28a745", "#ffc107", "#dc3545"]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: "top" }
                    }
                }
            });
        }

        var revenueData = @json($monthlyRevenue) || {};
        var orderStatusData = @json($orderStatus) || {};
        renderCharts(revenueData, orderStatusData);

        document.getElementById("filter").addEventListener("click", function () {
            var year = document.getElementById("year").value;
            var month = document.getElementById("month").value;
            fetch(`/dashboards/filter?year=${year}&month=${month}`)
                .then(response => response.json())
                .then(data => {
                    renderCharts(data.monthlyRevenue || {}, data.orderStatus || {});
                });
        });
    });
</script>
@endsection
