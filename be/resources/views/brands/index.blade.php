
@extends('master')

@section('content')
<style>

    .row{
        padding-top: 60px;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Danh sách Thương hiệu  
            </header>
           
            <div class="mb-3">
                <a href="{{ route('brands.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm thương hiệu 
                </a>
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
            </div>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên thương hiệu </th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands  as $brand)
                    <tr>
                        <td>{{ $brand->id }}</td>
                        <td>{{ $brand->name }}</td>
                        <td>
                            @if ($brand->status == 'active')
                                <span class="badge badge-info">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                       
                      
                        <td>
                           
                            <a class="btn btn-success btn-sm" href="{{ route('brands.edit', $brand->id) }}"><i class="fa fa-pencil"></i></a> 
                            <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa thương hiệu?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash-o "></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
            </table>
        </section>
    </div>
</div>

@endsection
