@extends('master')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 font-weight-bold mt-5 ">📊 Trang Thống Kê tài khoản </h2>
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
    
    <div class="row">
        <!-- Biểu đồ tài khoản -->
        <div class="col-md-6">
            <canvas id="accountChart"></canvas>
        </div>

        <!-- Bảng thống kê -->
        <div class="col-md-6">
            <table class="table table-bordered mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Vai trò</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accountStats as $stat)
                    <tr>
                        <td>
                            @if($stat->role == 'superadmin') Super Admin
                            @elseif($stat->role == 'admin') Admin
                            @elseif($stat->role == 'customer') Khách hàng
                            @else {{ ucfirst($stat->role) }}
                            @endif
                        </td>
                        <td>{{ $stat->count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Thêm thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        fetch("{{ route('admin.accountStatsData') }}")
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('accountChart').getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: Object.keys(data).map(role => {
                            if (role === 'superadmin') return 'Super Admin';
                            if (role === 'admin') return 'Admin';
                            if (role === 'customer') return 'Khách hàng';
                            return role.charAt(0).toUpperCase() + role.slice(1);
                        }),
                        datasets: [{
                            data: Object.values(data),
                            backgroundColor: ['#007bff', '#28a745', '#ffc107'],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
            });
    });
</script>
@endsection
