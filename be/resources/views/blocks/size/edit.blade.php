@extends('master')

@section('content')
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                Sửa kích thước
            </header>
            <div class="card-body">
                <!-- Form gửi đến route sizes.update với phương thức PUT -->
                <form action="{{ route('sizes.update', $size->id) }}" method="POST">
                    @csrf
                    @method('PUT') <!-- Chỉ định phương thức PUT để cập nhật dữ liệu -->

                    <!-- Hiển thị lỗi nếu có -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Tên kích thước -->
                    <div class="form-group">
                        <label for="name">Tên kích thước</label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg mb-2" placeholder="Tên kích thước" value="{{ old('name', $size->name) }}">
                    </div>

                    <!-- Chọn trạng thái -->
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control form-control-lg mb-2">
                            <option value="active" {{ $size->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ $size->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>

                    <!-- Nút Cập nhật -->
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
