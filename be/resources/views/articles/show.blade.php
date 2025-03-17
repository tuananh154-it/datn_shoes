<!-- resources/views/admin/articles/show.blade.php -->

@extends('master')

@section('content')
<style>

    .row{
        padding-top: 60px;
    }
</style>
<div class="container-fluid"> <!-- Đổi từ container -> container-fluid để full màn hình -->
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
                            <label class="form-label">Hình ảnh:</strong></label>
                            <div class="text-center"> <!-- Căn giữa ảnh -->
                                <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}"
                                    class="img-fluid img-thumbnail" style="max-width: 600px;"> <!-- img-fluid để responsive -->
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên :</strong></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $article->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu Đề :</strong></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $article->title }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội Dung :</strong></label>
                            <textarea name="content" id="content" class="form-control" rows="5" readonly>{{ $article->content }}</textarea>
                        </div>

                        <div class="d-flex">
                            <a href="{{ route('articles.index') }}" class="btn btn-secondary flex-fill me-1">Quay lại</a>
                            <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-warning flex-fill me-1">Chỉnh sửa</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
