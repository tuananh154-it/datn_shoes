@extends('master')

@section('content')
<div class="container">
    <h2 class="mt-4">Chi tiết Voucher</h2>
    
    <div class="card mt-3">
        <div class="card-body">
            <p><h1 class="card-title">{{ $voucher->name }}</h1></p>
            <p><strong>Mã giảm giá:</strong> {{ $voucher->id   }} </p>
            <p><strong>Phần trăm giảm:</strong> {{ $voucher->discount_percent ?? 'N/A' }}%</p>
            <p><strong>Ngày hết hạn:</strong> {{ $voucher->expiration_date }}</p>
            <p><strong>Giá trị tối thiểu:</strong> {{ $voucher->min_purchase_amount ?? 'N/A' }} VNĐ</p>
            <p><strong>Giảm tối đa:</strong> {{ $voucher->max_discount_amount ?? 'N/A' }} VNĐ</p>
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
@endsection
