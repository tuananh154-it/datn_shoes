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
                Chỉnh sửa Banner
            </header>

            <div class="card-body">
                <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Ảnh Banner:</label>
                        <div class="d-flex flex-column align-items-center">
                            @if ($banner->image_url)
                                <div class="d-flex justify-content-center">
                                    <img src="{{ Storage::url($banner->image_url) }}" alt="{{ $banner->title }}" class="img-thumbnail" style="max-width: 400px; margin-bottom: 10px;">
                                </div>
                            @else
                                <div class="alert alert-warning" role="alert" style="margin-bottom: 10px;">
                                    Chưa có banner.
                                </div>
                            @endif
                            <label class="form-label">Thay đổi Banner:</label>
                            <input type="file" class="form-control" id="image_url" name="image_url" style="width: auto;">
                            @error('image_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Link Banner -->
                    <div class="form-group mb-3">
                        <label for="link" class="form-label">Link:</label>
                        <input type="text" class="form-control @error('link') is-invalid @enderror" id="link" name="link"
                            value="{{ old('link', $banner->link) }}" placeholder="Nhập link cho banner">
                        @error('link')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Các nút điều hướng -->
                    <a href="{{ route('banners.index') }}" class="btn btn-secondary">Quay lại</a>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
