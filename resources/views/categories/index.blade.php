
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
                Danh sách danh mục
            </header>
           
            <div class="mb-3">
                <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm danh mục
                </a>
            </div>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên danh mục </th>
                        <th>Trạng thái </th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            @if ($category->status == 'active')
                                <span class="badge badge-info">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                       
                      
                        <td>
                           
                            <a class="btn btn-success btn-sm" href="{{ route('categories.edit', $category->id) }}"><i class="fa fa-pencil"></i></a> 
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Ban co chac chan muon xoa danh mục?');">
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
