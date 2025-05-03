{{-- resources/views/return_requests/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Gửi yêu cầu hoàn cho đơn hàng #{{ $order->id }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('return_requests.store', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="reason">Lý do</label>
            <input type="text" name="reason" id="reason" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả (tùy chọn)</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="bank_account">Số tài khoản nhận tiền hoàn</label>
            <input type="text" name="bank_account" id="bank_account" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="image">Ảnh (tùy chọn)</label>
            <input type="file" name="image" id="image" class="form-control-file">
        </div>
        <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
    </form>

    <a href="{{ route('return_requests.index') }}" class="btn btn-secondary mt-3">Quay lại danh sách yêu cầu</a>
</div>
@endsection
