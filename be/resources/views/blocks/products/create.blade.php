@extends('master')

@section('content')
    <style>
        .row { padding-top: 60px; }
        .variant-item {
            margin-top: 10px; padding: 10px; border: 1px solid #ccc;
            border-radius: 5px; cursor: pointer; margin-bottom: 10px;
        }
        .variant-item:hover { background-color: #f7f7f7; }
        .variant-details {
            display: none; margin-top: 10px;
            padding: 10px; border: 1px solid #ddd; border-radius: 5px;
        }
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
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Tên sản phẩm -->
                        <div class="form-group">
                            <label for="name">Tên sản phẩm</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                        </div>

                        <!-- Danh mục -->
                        <div class="form-group">
                            <label for="category_id">Danh mục</label>
                            <select name="category_id" id="category_id" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Thương hiệu -->
                        <div class="form-group">
                            <label for="brand_id">Thương hiệu</label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Giá sản phẩm -->
                        <div class="form-group">
                            <label for="price">Giá sản phẩm</label>
                            <input type="number" name="price" id="price" class="form-control" value="{{ old('price') }}">
                        </div>

                        <!-- Mô tả -->
                        <div class="form-group">
                            <label for="description">Mô tả</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        </div>

                        <!-- Trạng thái -->
                        <div class="form-group">
                            <label for="status">Trạng thái</label>
                            <select name="status" id="status" class="form-control">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Đã tạm dừng</option>
                            </select>
                        </div>

                        <!-- Ảnh tạm -->
                        @if(session('product_tmp_image'))
                        <div class="form-group">
                            <label>Ảnh sản phẩm trước đó:</label><br>
                            <img src="{{ asset('storage/' . session('product_tmp_image')) }}" width="100">
                            <input type="hidden" name="product_tmp_image" value="{{ session('product_tmp_image') }}">
                        </div>
                    @endif
                    

                        <!-- Chọn ảnh -->
                        <div class="form-group">
                            <label for="image">Chọn ảnh sản phẩm</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>

                        <!-- Loại sản phẩm -->
                        <div class="form-group">
                            <label for="type">Loại sản phẩm</label>
                            <select name="type" id="type" class="form-control">
                                <option value="simple" {{ old('type') == 'simple' ? 'selected' : '' }}>Sản phẩm đơn</option>
                                <option value="variant" {{ old('type') == 'variant' ? 'selected' : '' }}>Sản phẩm biến thể</option>
                            </select>
                        </div>

                        <!-- Thuộc tính biến thể -->
                        <div id="variantAttributes" style="display: none;">
                            <button type="button" id="addColorSize" class="btn btn-primary">Chọn Thuộc tính</button>
                            <div id="colorOptionsContainer"></div>
                            <div id="sizeOptionsContainer"></div>
                            <button type="button" id="generateVariants" class="btn btn-success">Thêm biến thể</button>
                        </div>

                        <!-- Danh sách biến thể -->
                        <div id="variantList"></div>

                        <button type="submit" class="btn btn-success">Thêm Sản Phẩm</button>
                    </form>
                </div>
            </section>
        </div>
    </div>

    <script>
        
        const colorMap = @json($colors->pluck('name', 'id'));
        const sizeMap = @json($sizes->pluck('name', 'id'));

        const oldType = "{{ old('type') }}";
        const oldColors = @json(old('selected_colors', []));
        const oldSizes = @json(old('selected_sizes', []));
        const oldVariants = @json(old('variant', []));
        const tmpVariantImages = @json(session('variant_tmp_images', []));

        document.getElementById('type').addEventListener('change', function () {
            document.getElementById('variantAttributes').style.display = this.value === 'variant' ? 'block' : 'none';
        });

        document.getElementById('addColorSize').addEventListener('click', function () {
            let colorHTML = '<h5>Chọn màu sắc:</h5>';
            let sizeHTML = '<h5>Chọn kích thước:</h5>';
            @foreach ($colors as $color)
                colorHTML += `<label><input type="checkbox" name="selected_colors[]" value="{{ $color->id }}"> {{ $color->name }}</label><br>`;
            @endforeach
            @foreach ($sizes as $size)
                sizeHTML += `<label><input type="checkbox" name="selected_sizes[]" value="{{ $size->id }}"> {{ $size->name }}</label><br>`;
            @endforeach
            document.getElementById('colorOptionsContainer').innerHTML = colorHTML;
            document.getElementById('sizeOptionsContainer').innerHTML = sizeHTML;
        });

        document.getElementById('generateVariants').addEventListener('click', function () {
            let variantList = document.getElementById('variantList');
            variantList.innerHTML = '';

            let selectedColors = [...document.querySelectorAll('input[name="selected_colors[]"]:checked')].map(el => el.value);
            let selectedSizes = [...document.querySelectorAll('input[name="selected_sizes[]"]:checked')].map(el => el.value);

            if (selectedColors.length === 0 || selectedSizes.length === 0) {
                alert('Vui lòng chọn màu và kích thước.');
                return;
            }

            selectedColors.forEach(color => {
                selectedSizes.forEach(size => {
                    let key = `${color}-${size}`;
                    let colorName = colorMap[color] || color;
                    let sizeName = sizeMap[size] || size;

                    let images = tmpVariantImages[key] || [];
                    let imgPreview = images.map(img => `
                        <img src="/storage/${img}" width="80" style="margin: 5px">
                        <input type="hidden" name="variant_tmp_images[${key}][]" value="${img}">`
                    ).join('');

                    variantList.innerHTML += `
    <div class="variant-item" id="wrapper-${key}">
        <div class="d-flex justify-content-between align-items-center">
            <div onclick="toggleVariant('${key}')" style="flex-grow: 1; cursor: pointer;">
                Màu: ${colorName} - Size: ${sizeName}
            </div>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeVariant('${key}')">Xoá</button>
        </div>
        <div id="variant-${key}" class="variant-details">
            <label>Số lượng:</label>
            <input type="number" name="variant[${key}][quantity]" class="form-control">
            <label>Giá gốc:</label>
            <input type="number" name="variant[${key}][default_price]" class="form-control">
            <label>Giá giảm:</label>
            <input type="number" name="variant[${key}][discount_price]" class="form-control">
            <label>Ảnh:</label>
            <input type="file" name="variant_images[${key}][]" multiple class="form-control">
            <div class="mt-2">${imgPreview}</div>
        </div>
    </div>`;



                });
            });
        });

        function removeVariant(key) {
    const wrapper = document.getElementById(`wrapper-${key}`);
    if (wrapper) {
        wrapper.remove();
    }
}

        function toggleVariant(id) {
            let el = document.getElementById('variant-' + id);
            el.style.display = el.style.display === 'block' ? 'none' : 'block';
        }

        // Khôi phục giá trị nếu có lỗi
        window.addEventListener('DOMContentLoaded', () => {
            if (oldType === 'variant') {
                document.getElementById('type').value = 'variant';
                document.getElementById('variantAttributes').style.display = 'block';
                document.getElementById('addColorSize').click();

                setTimeout(() => {
                    oldColors.forEach(color => {
                        let chk = document.querySelector(`input[name='selected_colors[]'][value='${color}']`);
                        if (chk) chk.checked = true;
                    });
                    oldSizes.forEach(size => {
                        let chk = document.querySelector(`input[name='selected_sizes[]'][value='${size}']`);
                        if (chk) chk.checked = true;
                    });
                    document.getElementById('generateVariants').click();

                    for (let key in oldVariants) {
                        let data = oldVariants[key];
                        if (data.quantity)
                            document.querySelector(`input[name='variant[${key}][quantity]']`).value = data.quantity;
                        if (data.default_price)
                            document.querySelector(`input[name='variant[${key}][default_price]']`).value = data.default_price;
                        if (data.discount_price)
                            document.querySelector(`input[name='variant[${key}][discount_price]']`).value = data.discount_price;
                    }
                }, 100);
            }
        });
        ClassicEditor
        .create(document.querySelector('#description'), {
            ckfinder: {
                uploadUrl: '{{ route('ckeditor.upload') }}', // Đảm bảo route này tồn tại
            },
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'link', 'imageUpload', '|', 'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable'
            ]
        })
        .catch(error => {
            console.error(error);
        });
    </script>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
@endsection
