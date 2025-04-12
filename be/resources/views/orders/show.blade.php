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

<div class="row m-0 p-4">
    <div class="col-12">

    <div class="text-center mb-5 mt-4">
        <h3 class="fw-bold text-primary text-uppercase">
            <i class="bi bi-receipt me-2"></i>Chi tiết đơn hàng #{{ $order->id }}
        </h3>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body px-4 py-4">

            {{-- 🛒 Sản phẩm đã đặt --}}
            <h5 class="fw-semibold text-primary mb-3">
                <i class="bi bi-box-seam me-2"></i>Sản phẩm đã đặt
            </h5>
            <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên Sản Phẩm</th>
                            <th>Giá</th>
                            <th>Số Lượng</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($order->order_details as $orderDetail)
                        <tr>
                            <td>
                                @if($orderDetail->productDetail?->product?->image)
                                    <img src="{{ asset('storage/' . $orderDetail->productDetail->product->image) }}" class="img-thumbnail" style="width: 70px;">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                            <td>{{ $orderDetail->productDetail->product->name ?? 'N/A' }}</td>
                            <td>{{ number_format($orderDetail->price, 0, ',', '.') }}₫</td>
                            <td>{{ $orderDetail->quantity }}</td>
                            <td>{{ number_format($orderDetail->price * $orderDetail->quantity, 0, ',', '.') }}₫</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-end mt-2">
                    <strong>Tổng cộng:</strong> {{ number_format($total_product_value, 0, ',', '.') }}₫
                </div>
            </div>

            {{-- 🧾 Thông tin chung --}}
            <h5 class="fw-semibold text-primary"><i class="bi bi-info-circle me-2"></i>Thông tin đơn hàng</h5>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Người đặt:</strong> {{ $order->user->name ?? 'Không rõ' }}</div>
                <div class="col-md-4"><strong>Ngày đặt:</strong> {{ $order->created_at?->format('d/m/Y') ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }}₫</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Phương thức thanh toán:</strong><br>
                    @switch($order->payment_method)
                        @case('cash_on_delivery')
                            <span class="badge bg-light text-dark border border-secondary">Tiền mặt</span>
                            @break
                        @case('momo')
                            <span class="badge" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba;">Momo</span>
                            @break
                        @case('zalopay')
                            <span class="badge" style="background-color: #d0f0ff; color: #007bff; border: 1px solid #b6e0fe;">ZaloPay</span>
                            @break
                        @default
                            <span class="badge bg-light text-muted border">Không rõ</span>
                    @endswitch
                </div>

                <div class="col-md-4">
                    <strong>Trạng thái thanh toán:</strong><br>
                    @switch($order->payment_status)
                        @case('paid') <span class="text-success">Đã thanh toán</span> @break
                        @case('unpaid') <span class="text-danger">Chưa thanh toán</span> @break
                        @case('pending') <span class="text-warning">Đang chờ</span> @break
                        @case('failed') <span class="text-danger">Thất bại</span> @break
                        @default <span class="text-muted">Không rõ</span>
                    @endswitch
                </div>

                <div class="col-md-4">
                    <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                        @csrf
                        @method('PUT')
                        <label for="status" class="form-label fw-semibold">Trạng thái đơn hàng:</label>
                        <select name="status" id="status" class="form-select form-select-sm w-auto d-inline-block bg-light text-dark" onchange="this.form.submit()">
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
                                <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            {{-- 📦 Địa chỉ giao hàng --}}
            <h5 class="fw-semibold text-primary mt-4"><i class="bi bi-geo-alt me-2"></i>Thông tin giao hàng</h5>
            <div class="row mb-2">
                <div class="col-md-6"><strong>Địa chỉ:</strong> {{ $order->address }}</div>
                <div class="col-md-6"><strong>Số điện thoại:</strong> {{ $order->phone_number }}</div>
            </div>
            @if($order->note)
                <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
            @endif

            <div class="text-center mt-4">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
</div>

@endsection
