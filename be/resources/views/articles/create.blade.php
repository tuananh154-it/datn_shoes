@extends('master')

@section('content')
<style>
    .row {
        padding-top: 60px;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .input-group .form-control,
    textarea.form-control {
        height: 50px !important;
        width: 100% !important;
        display: block;
        font-size: 16px;
        padding: 10px;
    }

    textarea.form-control {
        height: 150px !important;
        resize: none;
    }

    .btn-lg {
        padding: 12px 20px;
        font-size: 18px;
    }
</style>

<div class="row">
    <div class="col-sm-12">
        <div class="card shadow-sm">
            <header class="card-header">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Thêm Mới Bài Viết</h1>
                    <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('articles.index') }}" style="color: inherit;">Bài Viết</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Thêm Mới Bài Viết</li>
                        </ol>
                    </nav>
                </div>
            </header>

            <div class="card-body">
                <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Tên Bài Viết -->
                    <div class="form-group">
                        <label for="name" class="form-label">Tên Bài Viết:</label>
                        <div class="input-group">
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nhập tên Bài Viết">
                        </div>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Tiêu Đề -->
                    <div class="form-group">
                        <label for="title" class="form-label">Tiêu Đề:</label>
                        <div class="input-group">
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Nhập tiêu đề">
                        </div>
                        @error('title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Mô Tả -->
                    <div class="form-group">
                        <label for="content" class="form-label">Mô Tả:</label>
                        <div class="input-group">
                            <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror" placeholder="Nhập mô tả">{{ old('content') }}</textarea>
                        </div>
                        @error('content')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Ảnh Đại Diện -->
                    <div class="form-group">
                        <label for="image" class="form-label">Ảnh Đại Diện:</label>
                        <div class="input-group">
                            <input type="file" name="image" id="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        </div>
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3 d-flex">
                        <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-lg flex-fill me-1">Quay lại</a>
                        <button type="reset" class="btn btn-warning btn-lg flex-fill me-1">Reset</button>
                        <button type="submit" class="btn btn-primary btn-lg flex-fill">Thêm Mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fields = ['name', 'content', 'title', 'image'];

    fields.forEach(function(field) {
        const inputElement = document.getElementById(field);
        const errorElement = document.querySelector(`#${field}-error`);

        if (inputElement) {
            inputElement.addEventListener('input', function () {
                if (inputElement.classList.contains('is-invalid')) {
                    inputElement.classList.remove('is-invalid');
                }

                if (errorElement) {
                    errorElement.style.display = 'none';
                }
            });
        }
    });
});
</script>
@endsection

@section('js-cus')
<script>
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
