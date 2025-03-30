@extends('master')

@section('content')
<style>
    .row {
        padding-top: 60px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Thêm Chi Tiết Sản Phẩm cho: {{ $product->name }}
            </header>
            <div class="card-body">
                <form action="{{ route('product-details.store', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('tmp_variant_images'))
                        <div class="alert alert-info">
                            <strong>Ảnh đã chọn trước đó:</strong><br>
                            @foreach (session('tmp_variant_images') as $img)
                                <img src="{{ asset('storage/' . $img) }}" width="100" style="margin-right: 10px;">
                                <input type="hidden" name="tmp_images[]" value="{{ $img }}">
                            @endforeach
                        </div>
                    @endif

                    <!-- Hình ảnh -->
                    <div class="form-group">
                        <label for="image">Hình ảnh</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>

                    <!-- Kích thước -->
                    <div class="form-group">
                        <label for="size_id">Kích thước</label>
                        <select name="size_id" id="size_id" class="form-control" required>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}" {{ old('size_id') == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Màu sắc -->
                    <div class="form-group">
                        <label for="color_id">Màu sắc</label>
                        <select name="color_id" id="color_id" class="form-control" required>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}" {{ old('color_id') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Số lượng -->
                    <div class="form-group">
                        <label for="quantity">Số lượng</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Số lượng" value="{{ old('quantity') }}" required min="1">
                    </div>

                    <!-- Giá gốc -->
                    <div class="form-group">
                        <label for="default_price">Giá gốc</label>
                        <input type="number" name="default_price" id="default_price" class="form-control" placeholder="Giá gốc" value="{{ old('default_price') }}" required step="0.01">
                    </div>

                    <!-- Giá giảm -->
                    <div class="form-group">
                        <label for="discount_price">Giá giảm</label>
                        <input type="number" name="discount_price" id="discount_price" class="form-control" placeholder="Giá giảm" value="{{ old('discount_price') }}" step="0.01">
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">Thêm Chi Tiết Sản Phẩm</button>
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-secondary">Trở lại</a>
                </form>
            </div>
        </section>
    </div>
</div>

@endsection