@extends('master')

@section('content')
<style>
    .row {
        padding-top: 60px;
    }

    .img-fluid-custom {
        max-width: 100%; /* Đảm bảo hình ảnh không vượt quá chiều rộng của container */
        height: auto; /* Đảm bảo tỉ lệ hình ảnh không bị biến dạng */
        display: block; /* Đảm bảo hình ảnh là block để căn giữa */
        margin: 20px auto; /* Tạo khoảng cách và căn giữa hình ảnh */
    }

    .form-control img {
        max-width: 100%; /* Đảm bảo ảnh không vượt quá chiều rộng của container */
        height: auto; /* Giữ tỉ lệ gốc của ảnh */
        display: block; /* Đảm bảo ảnh là block element */
        margin: 10px auto; /* Thêm khoảng cách cho ảnh */
    }

    .card-body img {
        max-width: 100%; /* Đảm bảo tất cả hình ảnh trong nội dung đều không vượt quá chiều rộng của card */
        width: 400px;
        height: auto; /* Giữ tỉ lệ cho ảnh */
        display: block;
        margin: 0 auto; /* Căn giữa các hình ảnh */
    }
</style>

<div class="container-fluid"> 
    <div class="row">
        <div class="col-12"> <!-- Đảm bảo chiếm hết màn hình -->
            <div class="card shadow-sm w-100"> <!-- Thêm w-100 để mở rộng -->
                <header class="card-header">
                    Chi tiết mã giảm giá 
                </header>

                <div class="card mt-3">
                    <div class="card-body">
                        <h1 class="card-title">{{ $voucher->name }}</h1>
                        <p><strong>Mã giảm giá:</strong> {{ $voucher->id }}</p>
                        <p><strong>Phần trăm giảm:</strong> {{ $voucher->discount_percent ?? 'N/A' }}%</p>
                        <p><strong>Ngày hết hạn:</strong> {{ $voucher->expiration_date }}</p>
                        <p><strong>Giá trị tối thiểu:</strong> {{ $voucher->min_purchase_amount ?? 'N/A' }} VNĐ</p>
                        <p><strong>Giảm tối đa:</strong> {{ $voucher->max_discount_amount ?? 'N/A' }} VNĐ</p>
                        <p><strong>Số lượng:</strong> {{ $voucher->quantity ?? 'N/A' }}</p> <!-- Thêm dòng này để hiển thị số lượng -->
                        <p><strong>Ngày tạo:</strong> {{ $voucher->created_at ? $voucher->created_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'Không xác định' }}</p>
                        <p><strong>Ngày cập nhật:</strong> {{ $voucher->updated_at ? $voucher->updated_at->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'Không xác định' }}</p>                        
                        <p><strong>Điều khoản:</strong> {!! nl2br(e($voucher->terms_and_conditions ?? 'Không có')) !!}</p>
                        <p><strong>Trạng thái:</strong> 
                            @if($voucher->status == 'active')
                                <span class="badge bg-info">Hoạt động</span>
                            @else
                                <span class="badge bg-danger">Không hoạt động</span>
                            @endif
                        </p>
                        <a href="{{ route('vouchers.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
