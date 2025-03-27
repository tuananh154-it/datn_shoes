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

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-3">
                <form action="{{ route('orders.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm Tên Người Dùng" value="{{ request()->search }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="waiting_for_confirmation" {{ request()->status == 'waiting_for_confirmation' ? 'selected' : '' }}>Chờ xác nhận</option>
                                <option value="waiting_for_pickup" {{ request()->status == 'waiting_for_pickup' ? 'selected' : '' }}>Chờ lấy hàng</option>
                                <option value="waiting_for_delivery" {{ request()->status == 'waiting_for_delivery' ? 'selected' : '' }}>Chờ giao hàng</option>
                                <option value="delivered" {{ request()->status == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                <option value="returned" {{ request()->status == 'returned' ? 'selected' : '' }}>Đã trả lại</option>
                                <option value="cancelled" {{ request()->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
            </div>

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
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                @switch($order->payment_method)
                                    @case('credit_card') Thẻ tín dụng @break
                                    @case('cash_on_delivery') Thanh toán khi nhận hàng @break
                                    @case('paypal') PayPal @break
                                    @default N/A
                                @endswitch
                            </td>
                            <td>{{ number_format($order->total_price, 2) }} VND</td>
                            <td>
                                <form action="{{ route('orders.update_status', $order->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('PUT')

                                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                                        <option value="waiting_for_confirmation" {{ $order->status == 'waiting_for_confirmation' ? 'selected' : '' }}>Chờ xác nhận</option>
                                        <option value="waiting_for_pickup" {{ $order->status == 'waiting_for_pickup' ? 'selected' : '' }} @if($order->status != 'waiting_for_confirmation') disabled @endif>Chờ lấy hàng</option>
                                        <option value="waiting_for_delivery" {{ $order->status == 'waiting_for_delivery' ? 'selected' : '' }} @if($order->status != 'waiting_for_pickup') disabled @endif>Chờ giao hàng</option>
                                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }} @if($order->status != 'waiting_for_delivery') disabled @endif>Đã giao</option>
                                        <option value="returned" {{ $order->status == 'returned' ? 'selected' : '' }}>Đã trả lại</option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                    </select>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i> Xem chi tiết
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination">
                {{ $orders->links() }}
            </div>
        </section>
    </div>
</div>

@endsection
