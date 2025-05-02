@extends('master')

@section('content')

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="container py-4">
    <h3 class="text-primary fw-bold text-uppercase text-center mb-5">
        <i class="bi bi-receipt me-2"></i>Chi tiết đơn hàng #{{ $order->id }}
    </h3>

    {{-- 🛒 Sản phẩm đã đặt --}}
    <div class="mb-4">
        <h5 class="fw-semibold text-dark mb-3"><i class="bi bi-box me-2"></i>Sản phẩm</h5>

        <div class="list-group">
            @foreach ($order->order_details as $orderDetail)
            @php
                $product = $orderDetail->productDetail->product ?? null;
                $color = $orderDetail->productDetail->color->name ?? null;
                $size = $orderDetail->productDetail->size->name ?? null;
            @endphp
            <div class="list-group-item mb-3 border rounded p-3 shadow-sm d-flex align-items-center">
                <img src="{{ asset('storage/' . ($product->image ?? '')) }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;" alt="Ảnh sản phẩm">
                <div class="flex-grow-1">
                    <div class="fw-bold">{{ $product->name ?? 'N/A' }}</div>
                    <div class="text-muted">Màu: {{ $color }} | Kích cỡ: {{ $size }}</div>
                    <div class="mt-2">
                        <span class="badge bg-success">Đã đánh giá</span>
                    </div>
                </div>
                <div class="text-end" style="min-width: 180px;">
                    <div><strong>Giá:</strong> {{ number_format($orderDetail->price, 0, ',', '.') }}₫</div>
                    <div><strong>Số lượng:</strong> {{ $orderDetail->quantity }}</div>
                    <div><strong>Thành tiền:</strong> {{ number_format($orderDetail->price * $orderDetail->quantity, 0, ',', '.') }}₫</div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Tổng kết --}}
        <div class="mt-4 text-end">
            <div>Tạm tính: {{ number_format($total_product_value, 0, ',', '.') }}₫</div>
            <div>Phí vận chuyển: {{ number_format($shipping_fee, 0, ',', '.') }}₫</div>
            @if($order->voucher)
                <div>Giảm giá: {{ number_format($total_product_value + $shipping_fee - $total_price, 0, ',', '.') }}₫ (Mã: {{ $order->voucher->name }})</div>
            @endif
            <div class="fs-5 fw-bold mt-2">Tổng cộng: {{ number_format($total_price, 0, ',', '.') }}₫</div>
        </div>
    </div>

    {{-- 📋 Thông tin đơn hàng --}}
    <div class="mb-4">
        <h5 class="fw-semibold text-dark mb-3"><i class="bi bi-info-circle me-2"></i>Thông tin đơn hàng</h5>
        <div class="row">
            <div class="col-md-4"><strong>Người đặt:</strong> {{ $order->user->name ?? 'Không rõ' }}</div>
            <div class="col-md-4"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</div>
            <div class="col-md-4"><strong>Phương thức thanh toán:</strong>
                @switch($order->payment_method)
                    @case('cash_on_delivery') <span class="badge bg-secondary">Tiền mặt</span> @break
                    @case('momo') <span class="badge bg-warning text-dark">Momo</span> @break
                    @case('zalopay') <span class="badge bg-info text-dark">ZaloPay</span> @break
                    @default <span class="badge bg-light text-muted border">Không rõ</span>
                @endswitch
            </div>
            <div class="col-md-4 mt-2"><strong>Trạng thái thanh toán:</strong>
                @switch($order->payment_status)
                    @case('paid') <span class="text-success">Đã thanh toán</span> @break
                    @case('unpaid') <span class="text-danger">Chưa thanh toán</span> @break
                    @case('pending') <span class="text-warning">Đang chờ</span> @break
                    @default <span class="text-muted">Không rõ</span>
                @endswitch
            </div>
            <div class="col-md-4 mt-2">
                <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                    @csrf
                    @method('PUT')
                    <label class="form-label fw-semibold mb-1">Trạng thái đơn hàng:</label>
                    <select name="status" class="form-select form-select-sm w-auto d-inline-block bg-light text-dark" onchange="this.form.submit()">
                        @foreach ([
                            'pending' => 'Chờ xác nhận',
                            'confirmed' => 'Đã xác nhận',
                            'processing' => 'Đang xử lý',
                            'shipping' => 'Đang giao hàng',
                            'delivered' => 'Đã giao',
                            'completed' => 'Hoàn tất',
                            'cancelled' => 'Đã hủy',
                            'returned' => 'Trả hàng',
                            'refunded' => 'Hoàn tiền'
                        ] as $key => $label)
                            <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    {{-- 🚚 Thông tin giao hàng --}}
    <div class="mb-4">
        <h5 class="fw-semibold text-dark mb-3"><i class="bi bi-geo-alt me-2"></i>Thông tin giao hàng</h5>
        <div class="row">
            <div class="col-md-6"><strong>Địa chỉ:</strong> {{ $order->address }}</div>
            <div class="col-md-6"><strong>Số điện thoại:</strong> {{ $order->phone_number }}</div>
        </div>
        @if($order->note)
            <div class="mt-2"><strong>Ghi chú:</strong> {{ $order->note }}</div>
        @endif
    </div>

    {{-- 🔙 Quay lại --}}
    <div class="text-center mt-4">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark px-4">
            <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
        </a>
    </div>
</div>

@endsection
