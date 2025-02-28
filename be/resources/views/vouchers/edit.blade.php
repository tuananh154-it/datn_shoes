<style>

    .row{
        padding-top: 60px;
    }
    select {
    border-radius: 8px; /* Bo tròn viền */
    padding: 5px 10px;
    appearance: none; /* Loại bỏ giao diện mặc định */
 }
 .site-footer{
    margin: 1000px
 }

</style>
@extends('master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Cập nhật Voucherr
            </header>
            <div class="card-body">
                <!-- Form gửi đến route vouchers.store với phương thức POST -->
                <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- so tien giam gia --}}
                    <div class="form-group mb-3">
                        <label for="discount_amount" >Số tiền giảm</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', $voucher->discount_amount) }}">
                        </div>
                        
                    </div>

                    <!-- Phần trăm giảm giá-->
                    <div class="form-group mb-3">
                        <label for="discount_percent">Phần trăm giảm giá</label>
                        <div class="input-group">
                            <input type="number"class="form-control" name="discount_percent" id="discount_percent" value="{{ old('discount_percent', $voucher->discount_percent) }}">
                        </div>
                        
                    </div>

                    <!-- Ngày hết hạn -->
                    <div class="form-group mb-3">
                        <label for="expiration_date" >Ngày hết hạn:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="expiration_date" id="expiration_date" value="{{ old('expiration_date', $voucher->expiration_date)}}" required>
                        </div>
                       
                    </div>

                    <!-- Số tiền mua tối thiểu -->
                    <div class="form-group mb-3">
                        <label for="min_purchase_amount" >Số tiền mua tối thiểu:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="min_purchase_amount" id="min_purchase_amount"  value="{{ old('min_purchase_amount', $voucher->min_purchase_amount) }}">
                        </div>
                        
                    </div>
                    <!-- Mức giảm tối đa -->
                   
                    <div class="form-group mb-3">
                        <label for="max_discount_amount" >Mức giảm tối đa</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount', $voucher->max_discount_amount) }}">
                        </div>
                        
                    </div>
                    {{-- Điều khoản và điều kiện sử dụng --}}
                    <div class="form-group mb-3">
                        <label for="terms_and_conditions">Điều khoản và điều kiện sử dụng</label>
                        <div class="input-group">
                            <textarea class="form-control" name="terms_and_conditions" id="terms_and_conditions">{{ old('terms_and_conditions', $voucher->terms_and_conditions) }}</textarea>
                        </div>
                       
                    </div>
                    {{-- Trạng thái --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="status" >Trạng thái</label>
                        <select name="status" id="status" >
                            <option class="form-control" value="active" {{ $voucher->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option class="form-control" value="inactive" {{ $voucher->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                        {{-- hanh dong --}}

                        <div class="mb-3 d-flex">
                            <button type="submit" class="btn btn-warning btn-lg flex-fill">Cập nhập</button>
                        </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
