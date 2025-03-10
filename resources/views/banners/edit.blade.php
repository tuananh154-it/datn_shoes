@extends('master')

@section('content')
<div class="row">
    <div class="col-sm-12">
            <div class="card shadow-sm">
                <header class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Chỉnh Sửa Banner</h1>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('banners.index') }}" style="color: inherit;">Banner</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Chỉnh Sửa Banner</li>
                            </ol>
                        </nav>
                    </div>
                </header>

                <div class="card-body">

                    <form action="{{ route('banners.update', $banner->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3 text-center">
                            <label for="image_url" class="form-label"><strong>Image Banner:</strong></label>

                            <div class="d-flex flex-column align-items-center">
                                @if ($banner->image_url)
                                    <div class="d-flex justify-content-center">
                                        <img src="{{ Storage::url($banner->image_url) }}" alt="{{ $banner->title }}"
                                            class="img-thumbnail" style="max-width: 400px; margin-bottom: 10px;">
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert" style="margin-bottom: 10px;">
                                        Chưa có banner.
                                    </div>
                                @endif
                                <label class="form-label"><strong>Thay đổi banner:</strong></label>
                                <input type="file" class="form-control" id="image_url" name="image_url"
                                    style="width: auto;">
                                @error('image_url')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="link" class="form-label"><strong>Link :</strong></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="link" name="link"
                                    value="{{ old('link', $banner->link) }}">
                            </div>
                            @error('link')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 d-flex">
                            <a href="{{ route('banners.index') }}"
                                class="btn btn-secondary btn-lg flex-fill me-1">Quay lại</a>
                            <button type="reset" class="btn btn-warning btn-lg flex-fill me-1">Reset</button>
                            <button type="submit" class="btn btn-primary btn-lg flex-fill">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
