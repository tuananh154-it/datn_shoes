@extends('master')

@section('content')
<style>
    .row{padding-top:60px}
    .table th,.table td{vertical-align:middle;text-align:center}
    .table th{white-space:nowrap;background:#f8f9fa}
    .table td{word-wrap:break-word;max-width:300px}
</style>

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">Chi tiết bình luận</header>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Sản phẩm</th>
                        <th>Nội dung</th>
                        <th>Ngày&nbsp;đăng</th>   {{-- ngày --}}
                        <th>Giờ&nbsp;đăng</th>     {{-- giờ --}}
                        <!-- <th>Ảnh</th> -->
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $comment->id }}</td>
                        <td>{{ $comment->user->name }}</td>
                        <td>{{ $comment->user->email }}</td>
                        <td>{{ $comment->user->role ?? 'N/A' }}</td>
                        <td>{{ $comment->product->name }}</td>
                        <td>{{ $comment->content }}</td>
                        <td>{{ $comment->created_at->format('d/m/Y') }}</td>  {{-- ngày --}}
                        <td>{{ $comment->created_at->format('H:i') }}</td>    {{-- giờ --}}
                        <!-- <td>
                            @if($comment->file)
                                <a href="{{ Storage::url($comment->file) }}" target="_blank">Tải file</a>
                            @else
                                Không có file
                            @endif
                        </td> -->
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-start">
                <a href="{{ route('comments.index') }}" class="btn btn-primary">
                    Quay lại
                </a>
            </div>
        </section>
    </div>
</div>
@endsection