@extends('master')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 font-weight-bold mt-5 ">📊 Top 10  sản phẩm  </h2>
    
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
    <p></p>
    <form method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label>Năm:</label>
                <input type="number" name="year" value="{{ request('year', date('Y')) }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Tháng:</label>
                <input type="number" name="month" value="{{ request('month') }}" class="form-control">
            </div>
            <div class="col-md-4">
                <label>Ngày:</label>
                <input type="number" name="day" value="{{ request('day') }}" class="form-control">
            </div>
            <div class="col-md-12 text-center mt-2">
                <button type="submit" class="btn btn-primary">Lọc dữ liệu</button>
            </div>
        </div>
    </form>
    
    <canvas id="top10Chart"></canvas>
    <h4 class="mt-4 fw-bold text-dark ">📌 Danh sách  sản phẩm bán chạy </h4>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Sản phẩm</th>
                <th>Số lượng bán</th>
                <th>Tổng doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($topProducts as $index => $product)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->total_sold }}</td>
                    <td>{{ number_format($product->total_revenue, 0, ',', '.') }} đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('top10Chart').getContext('2d');
    var topProducts = @json($topProducts);
    
    var labels = topProducts.map(p => p.product_name);
    var data = topProducts.map(p => p.total_sold);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Số lượng bán',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
