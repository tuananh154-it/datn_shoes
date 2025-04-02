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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Form gửi đến route vouchers.store với phương thức POST -->
                <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- Tên voucher --}}
                    <div class="form-group mb-3">
                        <label for="name">Tên Mã giảm giá </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name', $voucher->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Số tiền giảm
                    <div class="form-group mb-3">
                        <label for="discount_amount">Số tiền giảm</label>
                        <input type="number" class="form-control @error('discount_amount') is-invalid @enderror" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', $voucher->discount_amount) }}">
                        @error('discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    {{-- Phần trăm giảm giá --}}
                    <div class="form-group mb-3">
                        <label for="discount_percent">Phần trăm giảm giá</label>
                        <input type="number" class="form-control @error('discount_percent') is-invalid @enderror" name="discount_percent" id="discount_percent" value="{{ old('discount_percent', $voucher->discount_percent) }}">
                        @error('discount_percent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ngày hết hạn --}}
                    <div class="form-group mb-3">
                        <label for="expiration_date">Ngày hết hạn</label>
                        <input type="date" class="form-control @error('expiration_date') is-invalid @enderror" name="expiration_date" id="expiration_date" value="{{ old('expiration_date', $voucher->expiration_date) }}">
                        @error('expiration_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Số tiền mua tối thiểu --}}
                    <div class="form-group mb-3">
                        <label for="min_purchase_amount">Số tiền mua tối thiểu</label>
                        <input type="number" class="form-control @error('min_purchase_amount') is-invalid @enderror" name="min_purchase_amount" id="min_purchase_amount" value="{{ old('min_purchase_amount', $voucher->min_purchase_amount) }}">
                        @error('min_purchase_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mức giảm tối đa --}}
                    <div class="form-group mb-3">
                        <label for="max_discount_amount">Mức giảm tối đa</label>
                        <input type="number" class="form-control @error('max_discount_amount') is-invalid @enderror" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount', $voucher->max_discount_amount) }}">
                        @error('max_discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trạng thái --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status', $voucher->status) == 'active' ? 'selected' : '' }}>Hoạt động </option>
                            <option value="inactive" {{ old('status', $voucher->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                        {{-- hanh dong --}}
                            <button type="submit" class="btn btn-warning ">Cập nhập</button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection