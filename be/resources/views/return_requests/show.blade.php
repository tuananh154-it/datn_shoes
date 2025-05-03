@extends('master')

@section('content')
<style>
    /* Đặt font và background toàn cục */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    /* Căn giữa nội dung */
    .container {
        padding-top: 3%;
    }

    /* Card yêu cầu hoàn trả */
    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        width: 100%;
        margin-bottom: 30px;
    }

    /* Header của card */
    .card-header {
        background-color: #78CD51;
        color: #fff;
        padding: 20px;
        font-size: 1.25rem;
        font-weight: bold;
        text-align: center;
    }

    /* Nội dung card */
    .card-body {
        padding: 20px;
    }

    /* Các đoạn text thông tin */
    .card-body p {
        font-size: 1rem;
        margin-bottom: 10px;
        line-height: 1.5;
        color: #333;
    }

    /* Cỡ chữ lớn cho phần thông tin yêu cầu */
    .request-info li {
        font-size: 1.2rem; /* Tăng kích thước chữ cho phần thông tin yêu cầu */
        margin-bottom: 12px;
    }

    /* Table yêu cầu hoàn trả */
    .table-responsive {
        margin-top: 20px;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .table thead {
        background-color: #f8f9fa;
    }

    /* Dòng trong bảng với màu yêu cầu */
    .table tbody tr {
        background-color: #41CAC0; /* Màu yêu cầu */
    }

    /* Nút chỉnh sửa */
    .btn-warning {
        background-color: #ffc107;
        border: none;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 0.9rem;
        transition: background-color 0.3s ease;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    /* Thông báo lỗi */
    .alert-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    /* Định dạng hình ảnh trong table */
    .img-fluid {
        max-width: 80px;
        border-radius: 5px;
    }

    /* Định dạng tổng số tiền */
    .total-price {
        font-size: 1.25rem;
        font-weight: bold;
    }
</style>

<div class="container">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Thông tin yêu cầu hoàn trả
            </header>

            @if(isset($message))
                <div class="alert alert-warning text-center">
                    {{ $message }}
                </div>
            @else
                <div class="card-body">
                    <div class="mb-4">
                        <h3>Thông tin yêu cầu:</h3>
                        <ul class="list-unstyled request-info">
                            <li><strong>Lý do:</strong> {{ $reason['reason'] }}</li>
                            <li><strong>Mô tả:</strong> {{ $reason['description'] }}</li>
                            <li><strong>Số tài khoản ngân hàng:</strong> {{ $reason['bank_account'] }}</li>
                        </ul>
                    </div>

                    <h3>Sản phẩm trong yêu cầu:</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr style="background-color: #73eee6" class="text-center">
                                    <th>Tên sản phẩm</th>
                                    <th>Hình ảnh</th>
                                    <th>Kích cỡ</th>
                                    <th>Màu sắc</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng giá</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td>{{ $product['product_name'] }}</td>
                                    <td>
                                        <img src="{{ $product['product_image'] }}" alt="{{ $product['product_name'] }}"
                                            class="img-fluid">
                                    </td>
                                    <td>{{ $product['size'] }}</td>
                                    <td>{{ $product['color'] }}</td>
                                    <td>{{ number_format($product['product_price'], 2) }} đ</td>
                                    <td>{{ $product['quantity'] }}</td>
                                    <td>{{ number_format($product['total_price'], 2) }} đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-right mt-3 total-price">
                        <strong>Tổng cộng: </strong>{{ number_format($all_total, 2) }} đ
                    </div>
                </div>
            @endif
        </section>
    </div>
</div>

@endsection
