@extends('master')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Bảng sản phẩm
            </header>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-3">
                <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm sản phẩm
                </a>
            </div>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th><i class=""></i> ID</th>
                        <th><i class=""></i> Tên sản phẩm</th>
                        <th><i class=""></i> Ảnh sản phẩm</th> <!-- Thêm cột Ảnh -->
                        <th><i class=""></i> Trang thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>
                                <!-- Hiển thị ảnh nếu có -->
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100px; height: auto;">
                                @else
                                    <span>Không có ảnh</span>
                                @endif
                            </td>
                            <td>
                                @if($product->status == 'active')
                                    <span class="badge badge-info label-mini">Hoạt động</span>
                                @else
                                    <span class="badge badge-danger label-mini">Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-pencil"></i> 
                                </a>

                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
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

@endsection
