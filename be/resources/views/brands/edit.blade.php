
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
                Cập nhập thương hiệu  
            </header>
            <div class="card-body">
                <!-- Form gửi đến route brands.edit với phương thức put  -->
                <form action="{{ route('brands.update',$brand->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- sua ten thuong hieu-->
                    <div class="form-group mb-3">
                        <label for="discount_percent">Tên thương hiệu </label>
                        <div class="input-group">
                            <input type="text"class="form-control" name="name" id="name" value="{{ $brand->name }}">
                        </div>
                        
                    </div>
                    {{-- sua Trạng thái --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="status" >Trạng thái</label>
                        <select name="status" id="status" >
                            <option class="form-control" value="active" {{ $brand->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option class="form-control" value="inactive" {{ $brand->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                        {{-- hanh dong --}}

                        <div class="mb-3 d-flex">
                            <button type="submit" class="btn btn-warning btn-lg flex-fill">Cập nhập</button>
                        </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
