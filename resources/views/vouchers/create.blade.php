
@extends('master')

@section('content')
<style>

    .row{
        padding-top: 60px;
    }
    select {
    border-radius: 8px; /* Bo tròn viền */
    padding: 5px 10px;
    appearance: none; /* Loại bỏ giao diện mặc định */
 }
 /* .site-footer{
    margin: 1000px
 } */

</style>
<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Thêm Voucher Mới
            </header>
            <div class="card-body">
                <!-- Form gửi đến route vouchers.store với phương thức POST -->
                <form action="{{ route('vouchers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- ten voucher --}}
                    <div class="form-group mb-3">
                        <label for="name" >Tên Voucher</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="name" id="name" value="{{ old('name') }}">
                        </div>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    

                    {{-- so tien giam gia --}}
                    <div class="form-group mb-3">
                        <label for="discount_amount" >Số tiền giảm</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="discount_amount" id="discount_amount" value="{{ old('discount_amount') }}">
                        </div>
                        @error('discount_amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phần trăm giảm giá-->
                    <div class="form-group mb-3">
                        <label for="discount_percent">Phần trăm giảm giá</label>
                        <div class="input-group">
                            <input type="number"class="form-control" name="discount_percent" id="discount_percent" value="{{ old('discount_percent') }}">
                        </div>
                        @error('discount_percent')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Ngày hết hạn -->
                    <div class="form-group mb-3">
                        <label for="expiration_date" >Ngày hết hạn:</label>
                        <div class="input-group">
                            <input type="date" class="form-control" name="expiration_date" id="expiration_date" value="{{ old('expiration_date') }}" required>
                        </div>
                        @error('expiration_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Số tiền mua tối thiểu -->
                    <div class="form-group mb-3">
                        <label for="min_purchase_amount" >Số tiền mua tối thiểu:</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="min_purchase_amount" id="min_purchase_amount" value="{{ old('min_purchase_amount') }}">
                        </div>
                        
                    </div>
                    <!-- Mức giảm tối đa -->
                   
                    <div class="form-group mb-3">
                        <label for="max_discount_amount" >Mức giảm tối đa</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}">
                        </div>
                        
                    </div>
                    {{-- Điều khoản và điều kiện sử dụng --}}
                    <div class="form-group mb-3">
                        <label for="terms_and_conditions">Điều khoản và điều kiện sử dụng</label>
                        <div class="input-group">
                            <textarea class="form-control" name="terms_and_conditions" id="terms_and_conditions"></textarea>
                        </div>
                       
                    </div>
                    {{-- Trạng thái --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="status" >Trạng thái</label>
                        <select name="status" id="status" >
                            <option class="form-control" value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option class="form-control" value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                        {{-- hanh dong --}}

                    <div  class="">
                        <a href="{{ route('vouchers.index') }}" class="btn btn-secondary btn-lg flex-fill me-1">Quay lại</a>
                        <button type="reset" class="btn btn-warning btn-lg flex-fill me-1">Reset</button>
                        <button type="submit" class="btn btn-primary btn-lg flex-fill">Thêm Mới</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
