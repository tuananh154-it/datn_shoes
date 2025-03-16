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
                    Thêm mới bài viết
                </header>

                <div class="card-body">
                    <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Tên Bài Viết -->
                        <div class="form-group">
                            <label for="name" class="form-label">Tên Bài Viết:</label>
                            <div class="input-group">
                                <input type="text" name="name" id="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="Nhập tên Bài Viết">
                            </div>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tiêu Đề -->
                        <div class="form-group">
                            <label for="title" class="form-label">Tiêu Đề:</label>
                            <div class="input-group">
                                <input type="text" name="title" id="title"
                                    class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}"
                                    placeholder="Nhập tiêu đề">
                            </div>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Mô Tả -->
                        <div class="form-group">
                            <label for="content" class="form-label">Mô Tả:</label>
                            <div class="input-group">
                                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror"
                                    placeholder="Nhập mô tả">{{ old('content') }}</textarea>
                            </div>
                            @error('content')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Ảnh Đại Diện -->
                        <div class="form-group">
                            <label for="image" class="form-label">Ảnh Đại Diện:</label>
                            <div class="input-group">
                                <input type="file" name="image" id="image"
                                    class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            </div>
                            @error('image')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex">
                            <a href="{{ route('articles.index') }}" class="btn btn-secondary btn-lg flex-fill me-1">Quay
                                lại</a>
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
        ClassicEditor
            .create(document.querySelector('#content'), {
                ckfinder: {
                    uploadUrl: '{{ route('ckeditor.upload') }}', // Route để xử lý upload ảnh
                },
                toolbar: [
                    'undo', 'redo', '|', 'bold', 'italic', '|', 'link', 'imageUpload', '|',
                    'bulletedList', 'numberedList', '|', 'blockQuote', 'insertTable'
                ]
            })
            .catch(error => {
                console.error(error);
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
