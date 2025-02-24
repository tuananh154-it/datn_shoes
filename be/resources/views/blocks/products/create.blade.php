<style>

    .row{
        padding-top: 60px;
    }
</style>
@extends('master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Thêm Sản Phẩm Mới
            </header>
            <div class="card-body">
                <!-- Form gửi đến route products.store với phương thức POST -->
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Hiển thị lỗi nếu có -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Tên sản phẩm -->
                    <div class="form-group">
                        <label for="name">Tên sản phẩm</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Tên sản phẩm" value="{{ old('name') }}">
                    </div>

                    <!-- Ảnh sản phẩm -->
                    <div class="form-group">
                        <label for="image">Ảnh sản phẩm</label>
                        <input type="file" name="image" id="image" class="form-control">
                    </div>

                    <!-- Giá sản phẩm -->
                    <div class="form-group">
                        <label for="price">Giá sản phẩm</label>
                        <input type="number" name="price" id="price" class="form-control" placeholder="Giá sản phẩm" value="{{ old('price') }}">
                    </div>

                    <!-- Mô tả sản phẩm -->
                    <div class="form-group">
                        <label for="description">Mô tả sản phẩm</label>
                        <textarea name="description" id="description" class="form-control" rows="4" placeholder="Mô tả sản phẩm">{{ old('description') }}</textarea>
                    </div>

                    <!-- Trạng thái sản phẩm -->
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>

                    <!-- Chọn danh mục -->
                    <div class="form-group">
                        <label for="category_id">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chọn thương hiệu -->
                    <div class="form-group">
                        <label for="brand_id">Thương hiệu</label>
                        <select name="brand_id" id="brand_id" class="form-control">
                            <option value="">Chọn thương hiệu</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nút Thêm -->
                    <button type="submit" class="btn btn-success">Thêm Sản Phẩm</button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
