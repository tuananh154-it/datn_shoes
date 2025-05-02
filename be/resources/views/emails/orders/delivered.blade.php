@component('mail::message')
# Đơn hàng của bạn đã được giao!

Xin chào {{ $order->username }},

Dưới đây là thông tin chi tiết đơn hàng của bạn:

<table border="1" cellpadding="8" cellspacing="0" width="100%" style="border-collapse: collapse; text-align: center; font-size: 14px;">
    <thead style="background-color: #f2f2f2;">
        <tr>
            <th>Sản phẩm</th>
            <th>Màu sắc</th>
            <th>Kích thước</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng tiền</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order->order_details as $detail)
            @php
                $product = $detail->productDetail->product;
            @endphp
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $detail->productDetail->color->name ?? '-' }}</td>
                <td>{{ $detail->productDetail->size->name ?? '-' }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ number_format($detail->price) }}₫</td>
                <td>{{ number_format($detail->total_price) }}₫</td>
            </tr>
        @endforeach
    </tbody>
</table>

<br>

<p><strong>Tổng tiền đơn hàng:</strong> {{ number_format($order->total_price) }}₫</p>

@component('mail::button', ['url' => $confirmUrl])
Xác nhận đã nhận hàng
@endcomponent

Cảm ơn bạn đã mua sắm tại FootVibe!

@endcomponent
