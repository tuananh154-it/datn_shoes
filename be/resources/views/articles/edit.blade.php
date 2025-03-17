@extends('master')

@section('content')
<style>
   
    .card-body img {
        width: 400px;
        height: auto; /* Giữ tỉ lệ cho ảnh */
        display: block;
        margin: 0 auto; /* Căn giữa các hình ảnh */
    }
    .row{
        padding-top: 60px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
            <div class="card shadow-sm">
                <header class="card-header">
                    Chỉnh sửa bài viết
                </header>

                <div class="card-body">
                    <form action="{{ route('articles.update', $articles->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 ">
                            <label for="image" class="form-label">Ảnh đại diện:</strong></label>

                            <div class="d-flex flex-column align-items-center">
                                @if ($articles->image)
                                    <div class="d-flex justify-content-center">
                                        <img src="{{ Storage::url($articles->image) }}" alt="{{ $articles->title }}"
                                            class="img-thumbnail" style="max-width: 400px; margin-bottom: 10px;">
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert" style="margin-bottom: 10px;">
                                        Chưa có articles.
                                    </div>
                                @endif
                                <label class="form-label">Thay đổi articles:</strong></label>
                                <input type="file" class="form-control" id="image" name="image"
                                    style="width: auto;">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Tên bài viết -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Tên bài viết:</strong></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                value="{{ old('name', $articles->name) }}" placeholder="Nhập tên bài viết">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tiêu đề -->
                        <div class="form-group mb-3">
                            <label for="title" class="form-label">Tiêu đề:</strong></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                value="{{ old('title', $articles->title) }}" placeholder="Nhập tiêu đề bài viết">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mô tả -->
                        <div class="form-group mb-3">
                            <label for="content" class="form-label">Mô tả:</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="4" placeholder="Nhập mô tả bài viết">{{ old('content', $articles->content) }}</textarea>
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Các nút điều hướng -->
                        <div class="mb-3 d-flex">
                            <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-lg flex-fill me-1">Quay lại</a>
                            <button type="reset" class="btn btn-warning btn-lg flex-fill me-1">Reset</button>
                            <button type="submit" class="btn btn-primary btn-lg flex-fill">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
