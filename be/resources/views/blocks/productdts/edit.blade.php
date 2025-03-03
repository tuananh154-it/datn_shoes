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
                Chỉnh sửa chi tiết sản phẩm: {{ $detail->product->name }}
            </header>
            <div class="card-body">
                <!-- Hiển thị thông báo thành công nếu có -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form gửi đến route product-details.update với phương thức PUT -->
                <form action="{{ route('product-details.update', $detail->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Hiển thị ảnh hiện tại -->
                    <div class="form-group">
                        <label for="current_image">Hình ảnh hiện tại</label><br>
                        @if($detail->image)
                            <img src="{{ asset('storage/' . $detail->image) }}" alt="Ảnh sản phẩm" style="width: 120px; height: auto;">
                        @else
                            <span>Không có ảnh</span>
                        @endif
                    </div>

                    <!-- Cập nhật ảnh mới -->
                    <div class="form-group">
                        <label for="image">Cập nhật hình ảnh mới</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>

                    <!-- Kích thước -->
                    <div class="form-group">
                        <label for="size_id">Kích thước</label>
                        <select name="size_id" id="size_id" class="form-control" required>
                            @foreach($sizes as $size)
                                <option value="{{ $size->id }}" {{ $detail->size_id == $size->id ? 'selected' : '' }}>
                                    {{ $size->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Màu sắc -->
                    <div class="form-group">
                        <label for="color_id">Màu sắc</label>
                        <select name="color_id" id="color_id" class="form-control" required>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}" {{ $detail->color_id == $color->id ? 'selected' : '' }}>
                                    {{ $color->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Số lượng -->
                    <div class="form-group">
                        <label for="quantity">Số lượng</label>
                        <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $detail->quantity }}" required min="1">
                    </div>

                    <!-- Giá gốc -->
                    <div class="form-group">
                        <label for="default_price">Giá gốc</label>
                        <input type="number" name="default_price" id="default_price" class="form-control" value="{{ $detail->default_price }}" required step="0.01">
                    </div>

                    <!-- Giá giảm -->
                    <div class="form-group">
                        <label for="discount_price">Giá giảm</label>
                        <input type="number" name="discount_price" id="discount_price" class="form-control" value="{{ $detail->discount_price }}" step="0.01">
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="active" {{ $detail->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ $detail->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>

                    <!-- Nút cập nhật -->
                    <button type="submit" class="btn btn-primary">Cập nhật chi tiết sản phẩm</button>
                    <a href="{{ route('products.show', $detail->product_id) }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </section>
    </div>
</div>

@endsection
