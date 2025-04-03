<style>

    .row{
        padding-top: 60px;
    }
</style>
@extends('master')

@section('content')
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                Sửa màu sắc
            </header>
            <div class="card-body">
                <!-- Form gửi đến route colors.update với phương thức PUT -->
                <form action="{{ route('colors.update', $color->id) }}" method="POST">
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

                    <!-- Tên màu -->
                    <div class="form-group">
                        <label for="name">Tên màu</label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg mb-2" placeholder="Tên màu" value="{{ old('name', $color->name) }}">
                    </div>

                    <!-- Chọn trạng thái -->
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" id="status" class="form-control form-control-lg mb-2">
                            <option value="active" {{ $color->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ $color->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>

                    <!-- Nút Cập nhật -->
                    <button type="submit" class="btn btn-warning ">Cập nhật</button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
