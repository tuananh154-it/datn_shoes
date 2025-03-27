@extends('master')

@section('content')
    <style>
        .row { padding-top: 60px; }
        .variant-item {
            margin-top: 10px; padding: 10px; border: 1px solid #ccc;
            border-radius: 5px; cursor: pointer; margin-bottom: 10px;
        }
        .variant-item:hover { background-color: #f7f7f7; }
        .variant-details { display: none; margin-top: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { margin-top: 10px; }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">Thêm Sản Phẩm Mới</header>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Trường thông tin sản phẩm đầy đủ -->
                        <div class="form-group">
                            <label for="name">Tên sản phẩm</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Tên sản phẩm" value="{{ old('name') }}">
                        </div>

                        <div class="form-group">
                            <label for="category_id">Danh mục</label>
                            <select name="category_id" id="category_id" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="brand_id">Thương hiệu</label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="price">Giá sản phẩm</label>
                            <input type="number" name="price" id="price" class="form-control" placeholder="Giá sản phẩm" value="{{ old('price') }}">
                        </div>

                        <div class="form-group">
                            <label for="description">Mô tả sản phẩm</label>
                            <textarea name="description" id="description" rows="4" class="form-control" placeholder="Mô tả sản phẩm">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Không hoạt động</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="image">Ảnh sản phẩm</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="type">Chọn loại sản phẩm</label>
                            <select name="type" id="type" class="form-control">
                                <option value="simple" {{ old('type') == 'simple' ? 'selected' : '' }}>Sản phẩm đơn giản</option>
                                <option value="variant" {{ old('type') == 'variant' ? 'selected' : '' }}>Sản phẩm có biến thể</option>
                            </select>
                        </div>

                        <div id="variantAttributes" style="display: none;">
                            <div class="form-group">
                                <button type="button" id="addColorSize" class="btn btn-primary">Thêm Thuộc Tính</button>
                            </div>

                            <div id="colorOptionsContainer"></div>
                            <div id="sizeOptionsContainer"></div>

                            <button type="button" id="generateVariants" class="btn btn-success">Thêm biến thể</button>
                        </div>

                        <div id="variantList"></div>

                        <button type="submit" class="btn btn-success">Thêm Sản Phẩm</button>
                    </form>
                </div>
            </section>

            <script>
                document.getElementById('type').addEventListener('change', function () {
                    let variantSection = document.getElementById('variantAttributes');
                    document.getElementById('variantList').innerHTML = '';
                    variantSection.style.display = this.value === 'variant' ? 'block' : 'none';
                });

                document.getElementById('addColorSize').addEventListener('click', function () {
                    let colorContainer = document.getElementById('colorOptionsContainer');
                    let sizeContainer = document.getElementById('sizeOptionsContainer');
                    colorContainer.innerHTML = '<h5>Chọn màu sắc:</h5>';
                    sizeContainer.innerHTML = '<h5>Chọn kích thước:</h5>';

                    @foreach ($colors as $color)
                        colorContainer.innerHTML += `<label><input type="checkbox" name="selected_colors[]" value="{{ $color->id }}"> {{ $color->name }}</label><br>`;
                    @endforeach

                    @foreach ($sizes as $size)
                        sizeContainer.innerHTML += `<label><input type="checkbox" name="selected_sizes[]" value="{{ $size->id }}"> {{ $size->name }}</label><br>`;
                    @endforeach
                });

                document.getElementById('generateVariants').addEventListener('click', function () {
                    let selectedColors = [...document.querySelectorAll('input[name="selected_colors[]"]:checked')].map(e => e.value);
                    let selectedSizes = [...document.querySelectorAll('input[name="selected_sizes[]"]:checked')].map(e => e.value);

                    if (selectedColors.length === 0 || selectedSizes.length === 0) {
                        alert('Vui lòng chọn cả màu sắc và kích thước.');
                        return;
                    }

                    let variantList = document.getElementById('variantList');
                    variantList.innerHTML = ''; // Clear previous variants

                    selectedColors.forEach(color => {
                        selectedSizes.forEach(size => {
                            let variantId = `variant-${color}-${size}`;
                            variantList.innerHTML += `
                                <div class="variant-item" onclick="toggleVariant('${variantId}')">
                                    Màu: ${color} - Kích thước: ${size}
                                </div>
                                <div id="${variantId}" class="variant-details">
                                    <label>Số lượng:</label>
                                    <input type="number" name="variant[${color}-${size}][quantity]" class="form-control" placeholder="Số lượng">
                                    <label>Giá mặc định:</label>
                                    <input type="number" name="variant[${color}-${size}][default_price]" class="form-control" placeholder="Giá mặc định">
                                    <label>Giá giảm:</label>
                                    <input type="number" name="variant[${color}-${size}][discount_price]" class="form-control" placeholder="Giá giảm">
                                    <label>Ảnh biến thể:</label>
                                    <input type="file" name="variant_images[${color}-${size}][]" class="form-control" multiple>


                                    <button type="button" class="btn btn-danger" onclick="removeVariant('${variantId}')">Xóa</button>
                                </div>
                            `;
                        });
                    });
                });

                function toggleVariant(id) {
                    let element = document.getElementById(id);
                    element.style.display = element.style.display === 'block' ? 'none' : 'block';
                }

                function removeVariant(id) {
                    document.getElementById(id).previousElementSibling.remove();
                    document.getElementById(id).remove();
                }
            </script>
{{-- ======= --}}



            ClassicEditor
                .create(document.querySelector('#description'), {
                    ckfinder: {
                        uploadUrl: '{{ route('ckeditor.upload') }}',
                    },
                    debug: 'all',
                    toolbar: [
                        'undo', 'redo', '|', 'bold', 'italic', '|', 'link', 'imageUpload', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });

                    </script>
{{-- >>>>>>> tuan-anh2 --}}
        </div>
    </div>
@endsection
