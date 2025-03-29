@extends('master')

@section('content')
<div class="container">
    <h2 class="mb-4 font-weight-bold mt-5 ">📊 Trang Thống sản phẩm  </h2>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboards.index') ? 'active' : '' }}" href="{{ route('dashboards.index') }}">Doanh thu</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboards.products') ? 'active' : '' }}" href="{{ route('dashboards.product') }}">Thống kê sản phẩm</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboards.top10') ? 'active' : '' }}" href="{{ route('dashboards.top10') }}">Top 10 sản phẩm bán chạy</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboards.users') ? 'active' : '' }}" href="{{ route('dashboards.users') }}">Thống kê tài khoản</a>
        </li>
    </ul>
    <h4 class="mt-3 font-weight-bold">📌 Sản phẩm theo danh mục</h4>
    <canvas id="categoryStatisticsChart"></canvas>

    <h4 class="mt-4 fw-bold text-dark ">📌 Chi tiết sản phẩm</h4>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Danh mục</th>
                <th>Tên sản phẩm</th>
                <th>Size</th>
                <th>Màu sắc</th>
                <th>Số lượng tồn</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productDetails as $detail)
                <tr>
                    <td>{{ $detail->category_name }}</td>
                    <td>{{ $detail->product_name ?? 'Không có sản phẩm' }}</td>
                    <td>{{ $detail->size ?? '-' }}</td>
                    <td>{{ $detail->color ?? '-' }}</td>
                    <td>{{ $detail->quantity ?? 0 }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var categoryLabels = @json($categories->pluck('category_name'));
    var categoryStockData = @json($categories->pluck('total_stock'));

    var ctx = document.getElementById('categoryStatisticsChart').getContext('2d');
    var categoryChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categoryLabels,
            datasets: [{
                label: 'Số lượng tồn kho',
                data: categoryStockData,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Số lượng sản phẩm'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Danh mục sản phẩm'
                    }
                }
            }
        }
    });
</script>
@endsection
