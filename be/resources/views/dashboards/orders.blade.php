@extends('master')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Danh sách đơn hàng</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Trạng thái</th>
                <th>Tổng tiền</th>
                <th>Ngày đặt</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->customer->name }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ number_format($order->total_amount, 0, ',', '.') }} VND</td>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
           
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Quay lại</a>
</div>
@endsection
