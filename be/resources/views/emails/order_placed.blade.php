<h2>Chào {{ $order->username }},</h2>

<p>Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi!</p>

@if ($order->status === 'delivered')
    <p>Đơn hàng <strong>#FV-HN-{{ $order->id }}</strong> của bạn đã được giao thành công. Vui lòng xác nhận rằng bạn đã nhận được hàng và hài lòng để hoàn tất đơn hàng.</p>
@else
    <p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi!</p>
@endif

<p><strong>Mã đơn hàng:</strong> #FV-HN-{{ $order->id }}</p>
<p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</p>
<p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }}đ</p>
<p><strong>Địa chỉ giao hàng:</strong> {{ $order->address }}</p>

<hr>

<h3>🛒 Sản phẩm đã đặt:</h3>
<table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>Sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Size</th>
            <th>Màu</th>
            <th>Tổng</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->order_details as $item)
            <tr>
                <td>{{ $item->productDetail->product->name ?? 'Sản phẩm đã xóa' }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->productDetail->size->name }}</td>
                <td>{{ $item->productDetail->color->name }}</td>
                <td>{{ number_format($item->total_price, 0, ',', '.') }}đ</td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>

<!-- <p>Chúng tôi sẽ liên hệ và giao hàng đến địa chỉ:</p>
<p>{{ $order->address }}</p> -->

<p>
    @if ($order->status === 'pending')
        <a href="{{ route('orders.confirm', ['id' => $order->id]) }}"
           style="display:inline-block;padding:12px 20px;background:#28a745;color:white;text-decoration:none;border-radius:5px;">
            ✅ Xác nhận đơn hàng
        </a>
        <a href="{{ route('orders.cancel', ['id' => $order->id]) }}"
           style="display:inline-block;padding:12px 20px;background:#dc3545;color:white;text-decoration:none;border-radius:5px;">
            ❌ Hủy đơn hàng
        </a>
    @elseif ($order->status === 'delivered')
        <a href="{{ route('orders.complete', ['id' => $order->id]) }}"
           style="display:inline-block;padding:12px 20px;background:#28a745;color:white;text-decoration:none;border-radius:5px;">
            ✅ Xác nhận hoàn tất
        </a>
    @endif
</p>

<p>Trân trọng,<br>Đội ngũ hỗ trợ</p>