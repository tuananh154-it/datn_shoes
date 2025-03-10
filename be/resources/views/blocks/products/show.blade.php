
@extends('master')

@section('content')
<style>

    .row{
        padding-top: 60px;
    }
</style>
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
                      
                        <p ><strong>Mô tả:</strong> {!! $product->description ?? 'Chưa có mô tả.' !!}</p>
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
            <div class="row">
                <div class="col-lg-12">
                    <section class="card">
                        <table class="table table-striped table-advance table-hover">
                            <thead>
                                <tr>
                                    <th><i class=""></i> ID</th>
                                    <th><i class=""></i> Ảnh</th>
                                    <th><i class=""></i> Size</th>
                                    <th><i class=""></i> Màu sắc</th>
                                    <th><i class=""></i> Số lượng</th>
                                    <th><i class=""></i> Giá gốc</th>
                                    <th><i class=""></i> Giá giảm</th>
                                    <th><i class=""></i> Trạng thái</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->productDetails as $detail)
                                    <tr>
                                        <td>{{ $detail->id }}</td>
                                        <td>
                                            @if($detail->image)
                                                <img src="{{ asset('storage/' . $detail->image) }}" alt="Ảnh sản phẩm" style="width: 80px; height: auto;">
                                            @else
                                                <span>Không có ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $detail->size->name }}</td>
                                        <td>{{ $detail->color->name }}</td>
                                        <td>{{ $detail->quantity }}</td>
                                        <td>{{ number_format($detail->default_price, 2) }} VNĐ</td>
                                        <td>
                                            @if($detail->discount_price)
                                                {{ number_format($detail->discount_price, 2) }} VNĐ
                                            @else
                                                <span class="text-muted">Không có</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($detail->status == 'active')
                                                <span class="badge badge-info label-mini">Hoạt động</span>
                                            @else
                                                <span class="badge badge-danger label-mini">Không hoạt động</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('product-details.edit', $detail->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-pencil"></i>
                                            </a>
            
                                            <form action="{{ route('product-details.destroy', $detail->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa chi tiết sản phẩm này?');">
                                                    <i class="fa fa-trash-o"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
            
            
            

            <footer class="card-footer text-right">
                <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">Trở lại</a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Chỉnh sửa</a>
                <!-- Nút thêm chi tiết sản phẩm -->
              
                <a href="{{ route('product-details.create', $product->id) }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm chi tiết sản phẩm
                </a>
                
            </footer>
        </section>
    </div>
</div>
<style>
    /* Giới hạn kích thước hình ảnh trong mô tả sản phẩm */
.image img {
    max-width: 100%;
    height: auto;
    max-height: 200px; /* Hoặc một giá trị bạn muốn */
}
p img {
    max-width: 100%;
    height: auto;
    max-height: 200px; /* Hoặc một giá trị bạn muốn */
}

</style>
@endsection
