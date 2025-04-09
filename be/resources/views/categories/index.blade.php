
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
             {{-- tim kiem ,loc thuong hieu--}}
             <div class="mb-3">
                <form action="{{ route('categories.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm" value="{{ request()->search }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request()->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request()->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>
                       
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                        {{-- <div class="col-md-2">
                            <a href="{{route("categories.index")}}" class="btn btn-success btn-sm">Quay lai danh sach</a>
                        </div> --}}
                    </div>

                </form>
            </div>
            {{-- them moi  --}}
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
                                <span class="badge badge-info">Hoạt động </span>
                            @else
                                <span class="badge badge-danger">Không hoạt động </span>
                            @endif
                        </td>
                       
                      
                        <td>
                           
                            <a class="btn btn-primary  btn-sm" href="{{ route('categories.edit', $category->id) }}"><i class="fa fa-pencil"></i></a> 
                            {{-- <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Ban co chac chan muon xoa danh mục?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash-o "></i></button>
                            </form> --}}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {!! $categories->links() !!}
            </div>
        </section>
    </div>
</div>

@endsection
