@extends('master')

@section('content')
    <style>
        .row {
            padding-top: 60px;
        }
    </style>
    <div class="row">
        <div class="col">
            <section class="card">
                <header class="card-header">
                    Chỉnh sửa sản phẩm
                </header>
                <div class="card-body">
                    <!-- Form gửi đến route products.update với phương thức PUT -->
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Chỉ định phương thức PUT để cập nhật dữ liệu -->

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
                            <input type="text" name="name" id="name" class="form-control form-control-lg mb-2"
                                placeholder="Tên sản phẩm" value="{{ old('name', $product->name) }}">
                        </div>

                        <!-- Mô tả sản phẩm -->
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            
                            <textarea name="description" id="description" class="form-control form-control-lg mb-2" placeholder="Mô tả">{{ old('description', $product->description) }}</textarea>
                        </div>

                        


                        <!-- Giá sản phẩm -->
                        <div class="form-group">
                            <label for="price">Giá</label>
                            <input type="text" name="price" id="price" class="form-control form-control-lg mb-2"
                                placeholder="Giá" value="{{ old('price', $product->price) }}">
                        </div>


                        <!-- Ảnh sản phẩm -->
                        <div class="form-group">
                            <label for="image">Ảnh sản phẩm</label>
                            <input type="file" name="image" id="image" class="form-control">
                            @if ($product->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Current Image"
                                        style="max-width: 200px; max-height: 200px;">
                                </div>
                            @endif
                        </div>

                        <!-- Trạng thái -->
                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select name="status" id="status" class="form-control form-control-lg mb-2">
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>
                                    Hoạt động</option>
                                <option value="inactive"
                                    {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Không hoạt động
                                </option>
                            </select>
                        </div>

                        <!-- Chọn danh mục -->
                        <div class="form-group">
                            <label for="category_id">Danh mục</label>
                            <select name="category_id" id="category_id" class="form-control form-control-lg mb-2">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Chọn thương hiệu -->
                        <div class="form-group">
                            <label for="brand_id">Thương hiệu</label>
                            <select name="brand_id" id="brand_id" class="form-control form-control-lg mb-2">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Nút Cập nhật -->
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
    <script>
        // ClassicEditor
        //     .create(document.querySelector('#description'), {
        //         ckfinder: {
        //             uploadUrl: '{{ route('ckeditor.upload') }}'  // Đảm bảo đường dẫn upload đúng
        //         },
        //         toolbar: [
        //             'undo', 'redo', '|', 'bold', 'italic', '|', 'link', 'imageUpload', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable'
        //         ]
        //     })
        //     .catch(error => {
        //         console.error(error);
        //     });
        ClassicEditor
        .create(document.querySelector('#description'), {
            ckfinder: {
                uploadUrl: '{{ route('ckeditor.upload') }}',
            },
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|', 'link', 'imageUpload', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable'
            ]
        })
        .catch(error => {
            console.error(error);
        });
            
    </script>
@endsection
{{-- <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script> --}}

