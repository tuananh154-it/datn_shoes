@extends('master')

@section('content')
<style>
    .row {
        padding-top: 60px;
    }

    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }

    .table th {
        white-space: nowrap;
        background-color: #f8f9fa;
    }

    .table td {
        word-wrap: break-word;
        max-width: 300px; /* Giới hạn chiều rộng của ô nội dung */
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
                        <th>Ảnh</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $comment->id }}</td>
                        <td>{{ $comment->user->name }}</td>
                        <td>{{ $comment->product->name }}</td>
                        <td>{{ $comment->comment }}</td>
                        <td>{{ $comment->star_rating }}</td>
                        <td>
                            @if ($comment->file)
                                <a href="{{ Storage::url($comment->file) }}" target="_blank">Tải file</a>
                            @else
                                Không có file
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-between">
                <a href="{{ route('comments.index') }}" class="btn btn-primary">Quay lại danh sách bình luận</a>

                @if (!$comment->deleted_at)
                    {{-- <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">Xóa bình luận</button>
                    </form> --}}
                @else
                    <form action="{{ route('comments.restore', $comment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-warning">Phục hồi bình luận</button>
                    </form>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection
