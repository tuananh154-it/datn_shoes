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
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Chi Tiết Bài Viết</h1>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('articles.index') }}" style="color: inherit;">Bài Viết</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Chi Tiết Bài Viết</li>
                            </ol>
                        </nav>
                    </div>
                </header>

                <div class="card-body">
                    <form action="{{ route('articles.show', $article->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @if ($article->image)
                            <label class="form-label"><strong>Hình ảnh:</strong></label>
                            <div class="text-center"> <!-- Căn giữa ảnh -->
                                <img src="{{ Storage::url($article->image) }}" alt="{{ $article->title }}"
                                    class="img-fluid img-thumbnail" style="max-width: 600px;"> <!-- img-fluid để responsive -->
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label"><strong>Tên :</strong></label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $article->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label"><strong>Tiêu Đề :</strong></label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ $article->title }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label"><strong>Nội Dung :</strong></label>
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
