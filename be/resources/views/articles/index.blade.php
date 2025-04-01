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
                Danh sách Bài viết  
            </header>
            
            {{-- Tìm kiếm, lọc bài viết --}}
            <div class="mb-3">
                <form action="{{ route('articles.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bài viết" value="{{ request()->search }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
            </div>
            
            {{-- Thêm bài viết --}}
            <div class="mb-3">
                <a href="{{ route('articles.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm bài viết
                </a>
            </div>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên bài viết</th>
                        <th>Tiêu đề</th>
                        <th>Hình ảnh</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($articles as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->title }}</td>
                            <td>
                                @if ($item->image)
                                    <img src="{{ Storage::url($item->image) }}" alt="Hình ảnh bài viết" width="80">
                                @else
                                    <span class="text-muted">Không có ảnh</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-warning  btn-sm" href="{{ route('articles.show', $item->id) }}"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-primary  btn-sm" href="{{ route('articles.edit', $item->id) }}"><i class="fa fa-pencil "></i></a>
                                <form action="{{ route('articles.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {!! $articles->links() !!}
            </div>
        </section>
    </div>
</div>
@endsection
