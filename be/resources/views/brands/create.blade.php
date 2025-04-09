
@extends('master')

@section('content')
<style>

    .row{
        padding-top: 60px;
    }
    select {
    border-radius: 8px; /* Bo tròn viền */
    padding: 5px 10px;
    appearance: none; /* Loại bỏ giao diện mặc định */
 }
 /* .site-footer{
    margin: 1000px
 } */

</style>
<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Thêm thương hiệu mới 
            </header>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Form gửi đến route brands.store với phương thức POST -->
                <form action="{{ route('brands.store') }}" method="POST">
                    @csrf
                    {{-- ten thuong hieu  --}}
                    <div class="form-group">
                        <label for="name">Tên thương hiệu</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                {{-- trang thai  --}}
                    <div class="form-group">
                        <label for="status">Trạng thái</label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                
                    <button type="submit" class="btn btn-success">Thêm thương hiệu </button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
