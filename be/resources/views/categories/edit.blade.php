
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
                Cập nhập danh mục 
            </header>
            <div class="card-body">
                <!-- Form gửi đến route categories.edit với phương thức put  -->
                <form action="{{ route('categories.update',$category->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <!-- sua ten danh muc-->
                    <div class="form-group mb-3">
                        <label for="discount_percent">Tên danh mục </label>
                        <div class="input-group">
                            <input type="text"class="form-control" name="name" id="name" value="{{ $category->name }}">
                        </div>
                        
                    </div>
                    {{-- sua Trạng thái --}}
                    <div class="form-group mb-3">
                        <label class="form-label" for="status" >Trạng thái</label>
                        <select name="status" id="status" >
                            <option class="form-control" value="active" {{ $category->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option class="form-control" value="inactive" {{ $category->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                        {{-- hanh dong --}}
                        <button type="submit" class="btn btn-warning">Cập nhập</button>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection
