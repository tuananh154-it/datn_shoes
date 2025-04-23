@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Thông tin yêu cầu hoàn trả</h1>

        @if(isset($message))
            <div class="alert alert-warning text-center">
                {{ $message }}
            </div>
        @else
            <div class="card shadow-sm p-4">

                <div class="mb-4">
                    <h3>Thông tin yêu cầu:</h3>
                    <ul class="list-unstyled">
                        <li><strong>Reason:</strong> {{ $reason['reason'] }}</li>
                        <li><strong>Description:</strong> {{ $reason['description'] }}</li>
                        <li><strong>Bank Account:</strong> {{ $reason['bank_account'] }}</li>
                    </ul>
                </div>

                <h3>Sản phẩm trong yêu cầu:</h3>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="table-primary text-center">
                                <th>Product Name</th>
                                <th>Image</th>
                                <th>Size</th>
                                <th>Color</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>{{ $product['product_name'] }}</td>
                                <td><img src="{{ $product['product_image'] }}" alt="{{ $product['product_name'] }}"
                                        class="img-fluid" style="max-width: 60px; border-radius: 5px;"></td>
                                <td>{{ $product['size'] }}</td>
                                <td>{{ $product['color'] }}</td>
                                <td>{{ number_format($product['product_price'], 2) }} đ</td>
                                <td>{{ $product['quantity'] }}</td>
                                <td>{{ number_format($product['total_price'], 2) }} đ</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-right mt-3">
                    <h4><strong>Tổng cộng: </strong>{{ number_format($all_total, 2) }} đ</h4>
                </div>
            </div>
        @endif
    </div>
@endsection