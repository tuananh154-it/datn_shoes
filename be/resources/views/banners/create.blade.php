@extends('master')

@section('content')
<div class="row">
    <div class="col-sm-12">
            <div class="card shadow-sm mb-4 ">
                
                        <header class="card-header ">
                            Thêm thương hiệu mới 
                        </header>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('banners.index') }}" style="color: inherit;">Banner</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Thêm Banner</li>
                            </ol>
                        </nav>
                    </div>
                

                <div class="card-body">

                    <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="image_url" class="form-label">Image :</label>
                            <div class="input-group">
                                <input type="file" name="image_url" id="image_url" class="form-control">
                            </div>
                            @error('image_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="link" class="form-label">Liên kết :</label>
                            <div class="input-group">
                                <input type="text" name="link" id="link" class="form-control">
                            </div>
                            @error('title')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>

                        {{-- hành động  --}}
                            <a href="{{ route('banners.index') }}"
                                class="btn btn-secondary ">Quay lại</a>
                            <button type="reset" class="btn btn-warning ">Reset</button>
                            <button type="submit" class="btn btn-primary ">Thêm Mới</button>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
