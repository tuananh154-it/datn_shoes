@extends('master')

@section('content')

<style>
    .row {
        padding-top: 60px;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Danh sách Banner
            </header>
            
            {{-- Tìm kiếm, lọc banner --}}
            <div class="mb-3">
                <form action="{{ route('banners.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm banner" value="{{ request()->search }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
            </div>
            
            {{-- Thêm mới --}}
            <div class="mb-3">
                <a href="{{ route('banners.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm Banner
                </a>
            </div>
            
            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Liên kết</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banners as $banner)
                        <tr>
                            <td>{{ $banner->id }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $banner->image_url) }}" alt="{{ $banner->title }}" width="150">
                            </td>
                            <td>{{ $banner->link }}</td>
                            <td>
                                <a href="{{ route('banners.show', $banner->id) }}" class="btn btn-warning  btn-sm">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a class="btn btn-primary  btn-sm" href="{{ route('articles.edit', $banner->id) }}"><i class="fa fa-pencil "></i></a>
                                <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa banner này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div class="d-flex justify-content-center">
                {!! $banners->links() !!}
            </div>
        </section>
    </div>
</div>

@endsection
