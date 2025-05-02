@extends('master')

@section('content')
<style>
    .row {
        padding-top: 60px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Bảng Đơn Hàng
            </header>

            @foreach (['success', 'error', 'warning', 'info'] as $msg)
                @if(session($msg))
                    <div class="alert alert-{{ $msg == 'error' ? 'danger' : $msg }}">
                        {{ session($msg) }}
                    </div>
                @endif
            @endforeach

            <div class="mb-3">
                <form action="{{ route('orders.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm Tên Người Dùng" value="{{ request()->search }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control" onchange="this.form.submit()">
                                <option value="">Tất cả trạng thái</option>
                                <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="confirmed" {{ request()->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="processing" {{ request()->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipping" {{ request()->status == 'shipping' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="delivered" {{ request()->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                <option value="completed" {{ request()->status == 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                                <option value="returned" {{ request()->status == 'returned' ? 'selected' : '' }}>Trả hàng</option>
                                <option value="refunded" {{ request()->status == 'refunded' ? 'selected' : '' }}>Hoàn tiền</option>
                                <option value="cancelled" {{ request()->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
            </div>

            @php
                $statusAllowBulkUpdate = ['pending', 'confirmed', 'processing', 'shipping', 'delivered'];
            @endphp

            @if (request()->status && in_array(request()->status, $statusAllowBulkUpdate))
                <form action="{{ route('orders.bulk_update_status') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <div class="row">
                            <div class="col-md-3">
                                <select name="bulk_status" class="form-control">
                                    <option value="">Chọn trạng thái mới</option>
                                    @php
                                        $validStatuses = [
                                            'pending' => ['confirmed', 'cancelled'],
                                            'confirmed' => ['processing', 'cancelled'],
                                            'processing' => ['shipping', 'cancelled'],
                                            'shipping' => ['delivered'],
                                            'delivered' => ['completed', 'returned'],
                                        ];
                                        $currentStatus = request()->status;
                                        $statuses = $validStatuses[$currentStatus] ?? [];
                                    @endphp
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">
                                            @switch($status)
                                                @case('pending') Chờ xác nhận @break
                                                @case('confirmed') Đã xác nhận @break
                                                @case('processing') Đang xử lý @break
                                                @case('shipping') Đang giao hàng @break
                                                @case('delivered') Đã giao @break
                                                @case('completed') Hoàn tất @break
                                                @case('returned') Trả hàng @break
                                                @case('refunded') Hoàn tiền @break
                                                @case('cancelled') Đã hủy @break
                                            @endswitch
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success">Cập nhật hàng loạt</button>
                            </div>
                        </div>
                    </div>

                    <table class="table table-striped table-advance table-hover">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all" onclick="toggleSelectAll(this)"></th>
                                <th>Mã Đơn</th>
                                <th>Người Dùng</th>
                                <th>Ngày Đặt</th>
                                <th>Hình Thức Thanh Toán</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>
                                        @if (!in_array($order->status, ['completed', 'returned', 'refunded', 'cancelled']))
                                            <input type="checkbox" name="order_ids[]" value="{{ $order->id }}" class="order-checkbox">
                                        @endif
                                    </td>
                                    <td>#FV-HN-{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        @switch($order->payment_method)
                                            @case('credit_card') Thẻ tín dụng @break
                                            @case('cash_on_delivery') Thanh toán khi nhận hàng @break
                                            @case('momo') MoMo @break
                                            @default N/A
                                        @endswitch
                                    </td>
                                    <td>{{ number_format($order->total_price, 2) }} VND</td>
                                    <td>
                                        <form action="{{ route('orders.update_status', $order->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                                <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }} @if($order->status !== 'pending') disabled @endif>Đã xác nhận</option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }} @if($order->status !== 'confirmed') disabled @endif>Đang xử lý</option>
                                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }} @if($order->status !== 'processing') disabled @endif>Đang giao hàng</option>
                                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }} @if($order->status !== 'shipping') disabled @endif>Đã giao</option>
                                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }} @if($order->status !== 'delivered') disabled @endif>Hoàn tất</option>
                                                <option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }} @if($order->status !== 'delivered') disabled @endif>Trả hàng</option>
                                                <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }} @if($order->status !== 'returned') disabled @endif>Hoàn tiền</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-warning btn-sm">
                                            <i class="fa fa-eye"></i> Xem chi tiết
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>
            @else
                <table class="table table-striped table-advance table-hover">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Người Dùng</th>
                            <th>Ngày Đặt</th>
                            <th>Hình Thức Thanh Toán</th>
                            <th>Tổng Tiền</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>#FV-HN-{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                                <td>
                                    @switch($order->payment_method)
                                        @case('credit_card') Thẻ tín dụng @break
                                        @case('cash_on_delivery') Thanh toán khi nhận hàng @break
                                        @case('momo') MoMo @break
                                        @default N/A
                                    @endswitch
                                </td>
                                <td>{{ number_format($order->total_price) }}₫</td>
                                <td>
                                    <form action="{{ route('orders.update_status', $order->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }} @if($order->status !== 'pending') disabled @endif>Đã xác nhận</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }} @if($order->status !== 'confirmed') disabled @endif>Đang xử lý</option>
                                            <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }} @if($order->status !== 'processing') disabled @endif>Đang giao hàng</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }} @if($order->status !== 'shipping') disabled @endif>Đã giao</option>
                                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }} @if($order->status !== 'delivered') disabled @endif>Hoàn tất</option>
                                            <option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }} @if($order->status !== 'delivered') disabled @endif>Trả hàng</option>
                                            <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }} @if($order->status !== 'returned') disabled @endif>Hoàn tiền</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-eye"></i> Xem chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            <div class="pagination">
                {{ $orders->withQueryString()->links() }}
            </div>
        </section>
    </div>
</div>

<script>
    function toggleSelectAll(source) {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
        });
    }
</script>

@endsection
