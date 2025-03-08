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
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Thêm Mới Voucher</h1>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('vouchers.index') }}" style="color: inherit;">Vouchers</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Thêm Mới Vouchers</li>
                            </ol>
                        </nav>
                    </div>
                </header>

                <div class="card-body">
                    <form action="{{ route('vouchers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- -->
                        <div class="form-group mb-3">
                            <label for="discount_amount" class="form-label">Số tiền giảm</label>
                            <div class="input-group">
                                <input type="text" name="discount_amount" id="discount_amount" value="{{ old('discount_amount') }}">
                            </div>
                            
                        </div>

                        <!-- -->
                        <div class="form-group mb-3">
                            <label for="discount_percent" class="form-label">Phần trăm giảm giá</label>
                            <div class="input-group">
                                <input type="text" name="discount_percent" id="discount_percent" value="{{ old('discount_percent') }}">
                            </div>
                            
                        </div>

                        <!--  -->
                        <div class="form-group mb-3">
                            <label for="expiration_date" class="form-label">Ngày hết hạn:</label>
                            <div class="input-group">
                                <input type="date" name="expiration_date" id="expiration_date" value="{{ old('expiration_date') }}" required>
                            </div>
                           
                        </div>

                        <!--  -->
                        <div class="form-group mb-3">
                            <label for="min_purchase_amount" class="form-label">Số tiền mua tối thiểu:</label>
                            <div class="input-group">
                                <input type="text" name="min_purchase_amount" id="min_purchase_amount" value="{{ old('min_purchase_amount') }}">
                            </div>
                            
                        </div>
                        <!--  -->
                       
                        <div class="form-group mb-3">
                            <label for="max_discount_amount" class="form-label">Mức giảm tối đa</label>
                            <div class="input-group">
                                <input type="text" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}">
                            </div>
                            
                        </div>
                        {{--  --}}
                        <div class="form-group mb-3">
                            <label for="terms_and_conditions" class="form-label">Điều khoản và điều kiện sử dụng</label>
                            <div class="input-group">
                                <textarea name="terms_and_conditions" id="terms_and_conditions"></textarea>
                            </div>
                           
                        </div>
                        {{--  --}}
                        <div class="form-group mb-3">
                            <label  for="status" class="form-label">Trạng thái</label>
                            <select name="status" id="status">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                            {{--  --}}

                        <div class="mb-3 d-flex">
                            <a href="{{ route('vouchers.index') }}" class="btn btn-secondary btn-lg flex-fill me-1">Quay lại</a>
                            <button type="reset" class="btn btn-warning btn-lg flex-fill me-1">Reset</button>
                            <button type="submit" class="btn btn-primary btn-lg flex-fill">Thêm Mới</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- @extends('master')

@section('content') --}}

{{-- @endsection --}}

{{-- @section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fields = ['name', 'content', 'title', 'image'];

    fields.forEach(function(field) {
        const inputElement = document.getElementById(field);
        const errorElement = document.getElementById(`${field}-error`);

        if (inputElement) {
            inputElement.addEventListener('input', function () {

                if (inputElement.classList.contains('is-invalid')) {
                    inputElement.classList.remove('is-invalid');
                }

                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            });
        }
    });
});
</script>
@endsection --}}