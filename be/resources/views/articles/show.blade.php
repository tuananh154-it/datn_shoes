<!-- resources/views/admin/articles/show.blade.php -->

@extends('master')

@section('content')
<style>
    .row {
        padding-top: 60px;
    }

    .img-fluid-custom {
        max-width: 100%; /* Đảm bảo hình ảnh không vượt quá chiều rộng của container */
        height: auto; /* Đảm bảo tỉ lệ hình ảnh không bị biến dạng */
        display: block; /* Đảm bảo hình ảnh là block để căn giữa */
        margin: 20px auto; /* Tạo khoảng cách và căn giữa hình ảnh */
    }
.form-control img {
        max-width: 100%; /* Đảm bảo ảnh không vượt quá chiều rộng của container */
        height: auto; /* Giữ tỉ lệ gốc của ảnh */
        display: block; /* Đảm bảo ảnh là block element */
        margin: 10px auto; /* Thêm khoảng cách cho ảnh */
    }
    .card-body img {
        max-width: 100%; /* Đảm bảo tất cả hình ảnh trong nội dung đều không vượt quá chiều rộng của card */
        width: 400px;
        height: auto; /* Giữ tỉ lệ cho ảnh */
        display: block;
        margin: 0 auto; /* Căn giữa các hình ảnh */
    }
</style>
<div class="container-fluid"> 
    <div class="row">
        <div class="col-12"> <!-- Đảm bảo chiếm hết màn hình -->
            <div class="card shadow-sm w-100"> <!-- Thêm w-100 để mở rộng -->
                <header class="card-header">
                    Chi tiết bài viết
                </header>

                <div class="card-body">
                    <form action="{{ route('articles.show', $article->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($article->image)
                            <label class="form-label">Hình ảnh:</label>
                            <div class="text-center">
                                <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}"
                                    class="img-fluid-custom" width="500px">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên :</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $article->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu Đề :</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $article->title }}" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Nội Dung :</label>
                            <textarea name="content" id="content" class="form-control">{!! $article->content !!}</textarea>
                        </div>

                        
                            <a href="{{ route('articles.index') }}" class="btn btn-secondary flex-fill me-1">Quay lại</a>
                            <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-warning flex-fill me-1">Chỉnh sửa</a>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Bao gồm CKEditor -->
<script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script>



<script>
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => {
            console.error(error);
        });
</script>

</div>

@endsection
