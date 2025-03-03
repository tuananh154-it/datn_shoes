@extends('layouts.main')
@section('content')
{{-- <style>

    .row{
        padding-top: 60px;
    }
</style> --}}
<div class="row">
    <div class="col-sm-12">
        <div class="card shadow-sm">
                <header class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Chinh sua Voucher</h1>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('vouchers.index') }}" style="color: inherit;">Vouchers</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Chinh sua Vouchers</li>
                            </ol>
                        </nav>
                    </div>
                </header>

                <div class="card-body">
                    <form action="{{ route('vouchers.update', $voucher->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- -->
                        <div class="form-group mb-3">
                            <label for="discount_amount" class="form-label">Số tiền giảm</label>
                            <div class="input-group">
                                <input type="text" name="discount_amount" id="discount_amount" value="{{ old('discount_amount', $voucher->discount_amount) }}">
                            </div>
                            
                        </div>

                        <!-- -->
                        <div class="form-group mb-3">
                            <label for="discount_percent" class="form-label">Phần trăm giảm giá</label>
                            <div class="input-group">
                                <input type="text" name="discount_percent" id="discount_percent" value="{{ old('discount_percent', $voucher->discount_percent) }}">
                            </div>
                            
                        </div>

                        <!--  -->
                        <div class="form-group mb-3">
                            <label for="expiration_date" class="form-label">Ngày hết hạn:</label>
                            <div class="input-group">
                                <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date', $voucher->expiration_date)}}" required>
                            </div>
                           
                        </div>

                        <!--  -->
                        <div class="form-group mb-3">
                            <label for="min_purchase_amount" class="form-label">Số tiền mua tối thiểu:</label>
                            <div class="input-group">
                                <input type="text" name="min_purchase_amount" id="min_purchase_amount" value="{{ old('min_purchase_amount', $voucher->min_purchase_amount) }}">
                            </div>
                            
                        </div>
                        <!--  -->
                       
                        <div class="form-group mb-3">
                            <label for="max_discount_amount" class="form-label">Mức giảm tối đa</label>
                            <div class="input-group">
                                <input type="text" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount', $voucher->max_discount_amount) }}">
                            </div>
                            
                        </div>
                        {{--  --}}
                        <div class="form-group mb-3">
                            <label for="terms_and_conditions" class="form-label">Điều khoản và điều kiện sử dụng</label>
                            <div class="input-group">
                                <textarea name="terms_and_conditions" id="terms_and_conditions">{{ old('terms_and_conditions', $voucher->terms_and_conditions) }}</textarea>
                            </div>
                           
                        </div>
                        {{--  --}}
                        <div class="form-group mb-3">
                            <label  for="status" class="form-label">Trạng thái</label>
                            <select name="status" id="status">
                                <option value="active" {{ $voucher->status == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ $voucher->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                            {{--  --}}

                        <div class="mb-3 d-flex">
                            <button type="submit" class="btn btn-warning btn-lg flex-fill">Cap nhap</button>
                        </div>
                    </form>
                    {{-- <a href="{{ route('vouchers.index') }}">Back to Voucher List</a> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
