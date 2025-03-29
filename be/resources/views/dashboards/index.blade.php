@extends('master')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4 font-weight-bold mt-5 ">📊 Trang Thống Kê Doanh Thu</h2>

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
    </ul>

    <h4 class="mt-3 font-weight-bold">📌 Doanh thu theo thời gian</h4>

    <!-- Bộ lọc thời gian -->
    <form method="GET" action="{{ route('dashboards.index') }}" class="my-3">
        <div class="d-flex align-items-center">
            <span class="fw-bold me-3">📅 Thống kê theo:</span>
            
            <div class="form-check form-check-inline">
                <input class="form-check-input filter-type" type="radio" name="filter-type" value="day" id="filter-day">
                <label class="form-check-label" for="filter-day">Ngày</label>
            </div>
        
            <div class="form-check form-check-inline">
                <input class="form-check-input filter-type" type="radio" name="filter-type" value="week" id="filter-week">
                <label class="form-check-label" for="filter-week">Tuần</label>
            </div>
        
            <div class="form-check form-check-inline">
                <input class="form-check-input filter-type" type="radio" name="filter-type" value="month" id="filter-month">
                <label class="form-check-label" for="filter-month">Tháng</label>
            </div>
        
            <div class="form-check form-check-inline">
                <input class="form-check-input filter-type" type="radio" name="filter-type" value="year" id="filter-year">
                <label class="form-check-label" for="filter-year">Năm</label>
            </div>
        </div>

        <div class="mt-3">
            <!-- Chọn ngày và giờ -->
            <div id="date-picker" class="filter-input" style="display: none;">
                <label for="selected-date">Ngày:</label>
                <input type="date" name="selected-date" class="form-control d-inline w-auto">
                <label for="selected-hour">Giờ:</label>
                <input type="time" name="selected-hour" class="form-control d-inline w-auto">
            </div>

            <!-- Chọn tuần -->
            <!-- Chọn tuần -->
            <div id="week-picker" class="filter-input" style="display: none;">
                <label for="selected-week">Tuần:</label>
                <input type="number" name="selected-week" class="form-control d-inline w-auto" min="1" max="52">
                <label for="selected-year-week">Năm:</label>
                <input type="number" name="selected-year-week" class="form-control d-inline w-auto" min="2000" max="2099">
                
            </div>


            <!-- Chọn tháng và năm -->
            <div id="month-picker" class="filter-input" style="display: none;">
                <label for="selected-month">Tháng:</label>
                <input type="number" name="selected-month" class="form-control d-inline w-auto" min="1" max="12">
                <label for="selected-year-month">Năm:</label>
                <input type="number" name="selected-year-month" class="form-control d-inline w-auto" min="2000" max="2099">
            </div>

            <!-- Chọn năm -->
            <div id="year-picker" class="filter-input" style="display: none;">
                <label for="selected-year">Năm:</label>
                <input type="number" name="selected-year" class="form-control d-inline w-auto" min="2000" max="2099">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">📊 Lọc thống kê</button>
    </form>

    <!-- Kiểm tra nếu không có dữ liệu -->
    @if(empty($labels) || empty($revenues))
        <div class="alert alert-warning">⚠ Không có dữ liệu cho khoảng thời gian này.</div>
    @else
        <!-- Biểu đồ -->
        <canvas id="revenueChart"></canvas>

        <!-- Bảng doanh thu -->
        <table class="table table-bordered mt-4">
    <thead class="thead-dark">
        <tr>
            <th>Giai đoạn</th>
            <th>Doanh thu (VNĐ)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($labels as $index => $label)
            @if($revenues[$index] > 0) <!-- Kiểm tra doanh thu > 0 -->
                <tr>
                    <td class="font-weight-bold">{{ $label }}</td>
                    <td>{{ number_format($revenues[$index]) }} đ</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

    @endif
</div>

<!-- Thư viện Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        let ctx = document.getElementById("revenueChart")?.getContext("2d");

        if (ctx && @json($labels) && @json($revenues)) {
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: "Doanh thu (VNĐ)",
                        data: @json($revenues),
                        borderColor: "green",
                        borderWidth: 3,
                        fill: false,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true, position: 'top' },
                        tooltip: { enabled: true }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Thời gian' } },
                        y: {
                            title: { display: true, text: 'Doanh thu (VNĐ)' },
                            beginAtZero: false,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('vi-VN').format(value) + " đ";
                                }
                            }
                        }
                    }
                }
            });
        }

        // Hiển thị input tương ứng với bộ lọc đã chọn
        function toggleInputFields() {
            let filterType = document.querySelector('input[name="filter-type"]:checked')?.value;
            document.querySelectorAll('.filter-input').forEach(el => el.style.display = 'none');

            if (filterType === 'day') {
                document.getElementById('date-picker').style.display = 'block';
            } else if (filterType === 'week') {
                document.getElementById('week-picker').style.display = 'block';
            } else if (filterType === 'month') {
                document.getElementById('month-picker').style.display = 'block';
            } else if (filterType === 'year') {
                document.getElementById('year-picker').style.display = 'block';
            }
        }

        document.querySelectorAll('.filter-type').forEach(input => {
            input.addEventListener("change", toggleInputFields);
        });

        toggleInputFields(); // Gọi ngay khi tải trang
    });
</script>
@endsection
