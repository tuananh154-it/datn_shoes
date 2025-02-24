@extends('master')
@section('content')
<div class="row">
    <div class="col-sm-12">
            <div class="card shadow-sm">
                <header class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Chi Tiết Comment</h1>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('comments.index') }}" style="color: inherit;">Comment</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Chi Tiết Comment</li>
                            </ol>
                        </nav>
                    </div>
                </header>

                <div class="card-body">

                    <form action="{{ route('comments.show', $comments->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label"><strong>Người dùng:</strong></label>
                            <input type="text" class="form-control" value="{{ $comments->user->username }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Sản phẩm:</strong></label>
                            <input type="text" class="form-control" value="{{ $comments->product->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Nội dung bình luận:</strong></label>
                            <textarea class="form-control" readonly>{{ $comments->comment }}</textarea>
                        </div>

                        @if ($comments->file)
                            <div class="mb-3">
                                <label class="form-label"><strong>File đính kèm:</strong></label>
                                <a href="{{ Storage::url($comments->file) }}" target="_blank" class="d-block">Tải file</a>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label"><strong>Đánh giá:</strong></label>
                            <input type="text" class="form-control" value="{{ $comments->star_rating }} / 5" readonly>
                        </div>

                        <div class="d-flex">
                            <a href="{{ route('comments.index') }}" class="btn btn-secondary flex-fill ">Quay lại</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
