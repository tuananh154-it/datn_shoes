@extends('master')

@section('content')
<style>
    .row {
        padding-top: 60px;
    }
    select {
        border-radius: 8px;
        padding: 5px 10px;
        appearance: none;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">Thêm Voucher Mới</header>
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
                <form action="{{ route('vouchers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Tên voucher --}}
                    <div class="form-group mb-3">
                        <label for="name">Tên Voucher</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Số tiền giảm
                    <div class="form-group mb-3">
                        <label for="discount_amount">Số tiền giảm</label>
                        <input type="number" class="form-control @error('discount_amount') is-invalid @enderror" name="discount_amount" id="discount_amount" value="{{ old('discount_amount') }}">
                        @error('discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> --}}

                    {{-- Phần trăm giảm giá --}}
                    <div class="form-group mb-3">
                        <label for="discount_percent">Phần trăm giảm giá</label>
                        <input type="number" class="form-control @error('discount_percent') is-invalid @enderror" name="discount_percent" id="discount_percent" value="{{ old('discount_percent') }}">
                        @error('discount_percent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ngày hết hạn --}}
                    <div class="form-group mb-3">
                        <label for="expiration_date">Ngày hết hạn</label>
                        <input type="date" class="form-control @error('expiration_date') is-invalid @enderror" name="expiration_date" id="expiration_date" value="{{ old('expiration_date') }}">
                        @error('expiration_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Số tiền mua tối thiểu --}}
                    <div class="form-group mb-3">
                        <label for="min_purchase_amount">Số tiền mua tối thiểu</label>
                        <input type="number" class="form-control @error('min_purchase_amount') is-invalid @enderror" name="min_purchase_amount" id="min_purchase_amount" value="{{ old('min_purchase_amount') }}">
                        @error('min_purchase_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Mức giảm tối đa --}}
                    <div class="form-group mb-3">
                        <label for="max_discount_amount">Mức giảm tối đa</label>
                        <input type="number" class="form-control @error('max_discount_amount') is-invalid @enderror" name="max_discount_amount" id="max_discount_amount" value="{{ old('max_discount_amount') }}">
                        @error('max_discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trạng thái --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Hành động --}}
                    <div class="d-flex gap-2">
                        <a href="{{ route('vouchers.index') }}" class="btn btn-secondary btn-lg">Quay lại</a>
                        <button type="reset" class="btn btn-warning btn-lg">Reset</button>
                        <button type="submit" class="btn btn-primary btn-lg">Thêm Mới</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
