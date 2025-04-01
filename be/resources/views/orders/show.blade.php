@extends('master')

@section('content')
<div class="mt-5">
    <div class="row">
        <!-- Bên trái: Chi tiết sản phẩm và tổng kết -->
        <div class="col-md-8">
            <section class="card">
                <header class="card-header bg-warning text-white font-weight-bold text-uppercase">
                    Chi tiết Đơn Hàng #{{ $order->id }}
                </header>

                <div class="card-body bg-light">
                    <!-- Chi tiết sản phẩm -->
                    <div class="mb-4">
                        <div class="bg-info p-3 rounded text-white">
                            <h5><strong>Chi Tiết Sản Phẩm</strong></h5>
                        </div>
                        <div class="bg-white p-3 rounded shadow-sm">
                            <table class="table table-bordered">
                                <thead class="bg-warning text-white">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên Sản Phẩm</th>
                                        <th>Giá</th>
                                        <th>Số Lượng</th>
                                        <th>Tổng Giá Trị</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_details as $orderDetail)
                                        <tr>
                                            <td>
                                                @if($orderDetail->productDetail && $orderDetail->productDetail->product && $orderDetail->productDetail->product->image)
                                                    <img src="{{ asset('storage/' . $orderDetail->productDetail->product->image) }}" alt="{{ $orderDetail->productDetail->product->name }}" class="img-fluid" style="max-width: 80px; max-height: 80px;">
                                                @else
                                                    <span>Không có ảnh</span>
                                                @endif
                                            </td>
                                            <td>{{ $orderDetail->productDetail ? $orderDetail->productDetail->product->name : 'N/A' }}</td>
                                            <td>{{ number_format($orderDetail->price, 2) }} VND</td>
                                            <td>{{ $orderDetail->quantity }}</td>
                                            <td>{{ number_format($orderDetail->quantity * $orderDetail->price, 2) }} VND</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tổng kết -->
                    <div class="mb-4">
                        <div class="bg-info p-3 rounded text-white">
                            <h5><strong>Tổng Kết</strong></h5>
                        </div>
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p><strong>Tổng Giá Trị Sản Phẩm:</strong> {{ number_format($total_product_value, 2) }} VND</p>
                            {{-- <p><strong>Phí Vận Chuyển:</strong> {{ number_format($shipping_fee, 2) }} VND</p> --}}
                            {{-- <p><strong class="text-danger">Tổng Tiền:</strong> <strong>{{ number_format($total_price, 2) }} VND</strong></p> --}}
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Bên phải: Thông tin đơn hàng và địa chỉ giao hàng -->
        <div class="col-md-4">
            <section class="card">
                <header class="card-header bg-warning text-white font-weight-bold text-uppercase">
                    Thông Tin Đơn Hàng #{{ $order->id }}
                </header>

                <div class="card-body bg-light">
                    <!-- Thông tin đơn hàng -->
                    <div class="mb-4">
                        <div class="bg-info p-3 rounded text-white">
                            <h5><strong>Thông Tin Đơn Hàng</strong></h5>
                        </div>
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p><strong>Mã Đơn Hàng:</strong> {{ $order->id }}</p>
                            <p><strong>Người Đặt:</strong> {{ $order->customer ? $order->customer->name : 'Không có thông tin' }}</p>
                            <p><strong>Ngày Đặt:</strong> {{ $order->created_at ? $order->created_at->format('d/m/Y') : 'N/A' }}</p>
                            <p><strong>Tổng Tiền:</strong> {{ number_format($order->total_price, 2) }} VND</p>
                            <p><strong>Phương Thức Thanh Toán:</strong>
                                @switch($order->payment_method)
                                    @case('credit_card')
                                        Thanh Toán Online
                                        @break
                                    @case('cash_on_delivery')
                                        Thanh toán khi nhận hàng
                                        @break
                                    @case('paypal')
                                        PayPal
                                        @break
                                    @default
                                        N/A
                                @endswitch
                            </p>
                            <p><strong>Trạng Thái Đơn Hàng:</strong>
                                @switch($order->status)
                                    @case('waiting_for_confirmation')
                                        <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        @break
                                    @case('waiting_for_pickup')
                                        <span class="badge bg-primary text-white">Chờ lấy hàng</span>
                                        @break
                                    @case('waiting_for_delivery')
                                        <span class="badge bg-info text-white">Chờ giao hàng</span>
                                        @break
                                    @case('delivered')
                                        <span class="badge bg-success text-white">Đã giao</span>
                                        @break
                                    @case('returned')
                                        <span class="badge bg-secondary text-white">Đã trả lại</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger text-white">Đã hủy</span>
                                        @break
                                    @default
                                        N/A
                                @endswitch
                            </p>
                            <p><strong>Trạng Thái Thanh Toán:</strong>
                                @switch($order->payment_status)
                                    @case('paid')
                                        Đã thanh toán
                                        @break
                                    @case('unpaid')
                                        Chưa thanh toán
                                        @break
                                    @case('pending')
                                        Đang chờ
                                        @break
                                    @case('failed')
                                        Thanh toán thất bại
                                        @break
                                    @default
                                        N/A
                                @endswitch
                            </p>
                            <p><strong>Ghi Chú của Khách Hàng:</strong>
                                {{ $order->note ? $order->note : 'Không có ghi chú' }}
                            </p>
                        </div>
                    </div>

                    <!-- Địa chỉ giao hàng -->
                    <div class="mb-4">
                        <div class="bg-info p-3 rounded text-white">
                            <h5><strong>Địa Chỉ Giao Hàng</strong></h5>
                        </div>
                        <div class="bg-white p-3 rounded shadow-sm">
                            <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                            <p><strong>Số Điện Thoại:</strong> {{ $order->phone_number }}</p>
                        </div>
                    </div>

                    <a href="{{ route('orders.index') }}" class="btn btn-secondary ">Quay lại danh sách đơn hàng</a>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection
