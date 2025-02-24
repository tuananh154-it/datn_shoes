@extends('master')

@section('content')
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                Thêm kích thước
            </header>
            <div class="card-body">
                <!-- Form gửi đến route sizes.store với method POST -->
                <form action="{{ route('sizes.store') }}" method="POST">
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

                    <!-- Tên kích thước -->
                    <div class="form-group">
                        <label for="name">Tên kích thước</label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg mb-2" placeholder="Tên kích thước" value="{{ old('name') }}">
                    </div>

                    <!-- Nút Thêm -->
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
