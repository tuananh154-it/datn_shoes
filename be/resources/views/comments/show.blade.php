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
                Chi tiết Bình luận
            </header>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Sản phẩm</th>
                        <th>Nội dung</th>
                        <th>Đánh giá</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $comment->id }}</td>
                        <td>{{ $comment->user->username }}</td>
                        <td>{{ $comment->product->name }}</td>
                        <td>{{ Str::limit($comment->comment, 50) }}</td>
                        <td>{{ $comment->star_rating }} / 5</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>
</div>
@endsection