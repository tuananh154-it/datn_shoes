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
                Chi tiết sản phẩm
            </header>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Hiển thị hình ảnh sản phẩm nếu có -->
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name }}">
                        @else
                            <img src="{{ asset('images/default-product.jpg') }}" class="img-fluid" alt="Default image">
                        @endif
                    </div>

                    <div class="col-md-6">
                        <h4>{{ $product->name }}</h4>
                        <p><strong>Giá:</strong> {{ number_format($product->price, 2) }} VNĐ</p>
                        <p><strong>Mô tả:</strong> {{ $product->description ?? 'Chưa có mô tả.' }}</p>
                        <p><strong>Trạng thái:</strong> 
                            @if($product->status == 'active')
                                <span class="badge badge-success">Hoạt động</span>
                            @else
                                <span class="badge badge-danger">Không hoạt động</span>
                            @endif
                        </p>

                        <p><strong>Danh mục:</strong> {{ $product->category->name }}</p>
                        <p><strong>Thương hiệu:</strong> {{ $product->brand->name }}</p>
                    </div>
                </div>
            </div>

            <footer class="card-footer text-right">
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Trở lại</a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Chỉnh sửa</a>
                <!-- Nút thêm chi tiết sản phẩm -->
                <a href="{{ route('products.details.create', $product->id) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm chi tiết sản phẩm
                </a>
            </footer>
        </section>
    </div>
</div>

@endsection
