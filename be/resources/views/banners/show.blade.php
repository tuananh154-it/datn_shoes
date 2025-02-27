@extends('master')

@section('content')
<div class="row">
    <div class="col-sm-12">
            <div class="card shadow-sm">
                <header class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Chi Tiết Banner</h1>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('banners.index') }}" style="color: inherit;">Banner</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Chi Tiết Banner</li>
                            </ol>
                        </nav>
                    </div>
                </header>

                <div class="card-body">

                    <form action="{{ route('banners.show', $banners->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        @if ($banners->image_url)
                            <label class="form-label"><strong>Image Banner:</strong></label>
                            <div class="d-flex justify-content-center">
                                <img src="{{ Storage::url($banners->image_url) }}" alt="{{ $banners->title }}"
                                    class="img-thumbnail" style="max-width: 800px;">
                            </div>
                        @endif

                        <div class="form-group mb-3">
                            <label for="title" class="form-label"><strong>Link :</strong></label>
                            <div class="input-group">
                                <input type="text" name="title" id="title" class="form-control"
                                    value="{{ $banners->link }}" readonly>
                            </div>
                        </div>

                        <div class="d-flex">
                            <a href="{{ route('banners.index') }}" class="btn btn-secondary flex-fill me-1">Quay
                                lại</a>
                            <a href="{{ route('banners.edit', $banners->id) }}"
                                class="btn btn-warning flex-fill me-1">Chỉnh
                                sửa</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
