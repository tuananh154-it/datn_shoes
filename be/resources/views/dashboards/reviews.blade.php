@extends('master')
@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Danh sách đánh giá</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Người dùng</th>
                <th>Sản phẩm</th>
                <th>Số sao</th>
                <th>Bình luận</th>
                <th>Ngày đánh giá</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>{{ $review->id }}</td>
                <td>{{ $review->user->name }}</td>
                <td>{{ $review->product->name }}</td>
                <td>{{ $review->star_rating }}</td>
                <td>{{ $review->comment }}</td>
                <td>{{ $review->created_at->format('d/m/Y') }}</td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Quay lại</a>
</div>
@endsection
