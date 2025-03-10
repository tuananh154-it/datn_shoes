
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
                <!-- Form gửi đến route brands.store với phương thức POST -->
                <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                   
                    <!-- ten thuong hieu-->
                    <div class="form-group mb-3">
                        <label for="name">Tên thương hiệu </label>
                        <div class="input-group">
                            <input type="text"class="form-control" name="name" id="name" value="{{ old('name') }}">
                        </div>
                        
                    </div>
                    {{-- Trạng thái --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="status" >Trạng thái</label>
                        <select name="status" id="status" >
                            <option class="form-control" value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option class="form-control" value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                        {{-- hanh dong --}}

                    <div  class="">
                        <a href="{{ route('brands.index') }}" class="btn btn-secondary btn-lg flex-fill me-1">Quay lại</a>
                        <button type="reset" class="btn btn-warning btn-lg flex-fill me-1">Reset</button>
                        <button type="submit" class="btn btn-primary btn-lg flex-fill">Thêm Mới</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
