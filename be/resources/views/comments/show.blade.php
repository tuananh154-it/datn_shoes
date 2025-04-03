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
                Danh sách Bình luận
            </header>
            
            <div class="mb-3">
                <form action="{{ route('comments.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bình luận" value="{{ request()->search }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request()->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request()->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Sản phẩm</th>
                        <th>Nội dung</th>
                        <th>Đánh giá</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>{{ $comment->user->username }}</td>
                            <td>{{ $comment->product->name }}</td>
                            <td>{{ Str::limit($comment->comment, 50) }}</td>
                            <td>{{ $comment->star_rating }} / 5</td>
                            <td>
                                <a class="btn btn-primary btn-sm" href="{{ route('comments.show', $comment->id) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {!! $comments->links() !!}
            </div>
        </section>
    </div>
</div>
@endsection