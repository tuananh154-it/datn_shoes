@extends('master')

@section('content')
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                Thêm màu sắc
            </header>
            <div class="card-body">
                <!-- Form gửi đến route colors.store với method POST -->
                <form action="{{ route('colors.store') }}" method="POST">
                    @csrf

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
                        <input type="text" name="name" id="name" class="form-control form-control-lg mb-2" placeholder="Tên màu" value="{{ old('name') }}">
                    </div>

                    <!-- Nút Thêm -->
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
