@extends('master')

@section('content')
<style>

    .row{
        padding-top: 60px;
    }
</style>
<div class="row">
    <div class="col-sm-12">
            <div class="card shadow-sm">
                <header class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Chỉnh Sửa Bài Viết</h1>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('articles.index') }}" style="color: inherit;">Bài viết</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Chỉnh Sửa Bài Viết</li>
                            </ol>
                        </nav>
                    </div>
                </header>

                <div class="card-body">
                    <form action="{{ route('articles.update', $articles->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 ">
                            <label for="image" class="form-label"><strong>Ảnh đại diện:</strong></label>

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
                                <label class="form-label"><strong>Thay đổi articles:</strong></label>
                                <input type="file" class="form-control" id="image" name="image"
                                    style="width: auto;">
                                @error('image')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!-- Tên bài viết -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label"><strong>Tên bài viết:</strong></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                value="{{ old('name', $articles->name) }}" placeholder="Nhập tên bài viết">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tiêu đề -->
                        <div class="form-group mb-3">
                            <label for="title" class="form-label"><strong>Tiêu đề:</strong></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                value="{{ old('title', $articles->title) }}" placeholder="Nhập tiêu đề bài viết">
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mô tả -->
                        <div class="form-group mb-3">
                            <label for="content" class="form-label"><strong>Mô tả:</strong></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content"
                                rows="4" placeholder="Nhập mô tả bài viết">{{ old('content', $articles->content) }}</textarea>
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
    </div>
@endsection
