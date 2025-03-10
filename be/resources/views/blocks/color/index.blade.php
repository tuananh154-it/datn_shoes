
<style>

    .row{
        padding-top: 60px;
    }
</style>@extends('master')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Bảng màu sắc
            </header>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
           
            <div class="mb-3">
                <form action="{{ route('colors.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm màu sắc" value="{{ request()->search }}">
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
                    </div>
                </form>
            </div>
            

            <div class="mb-3">
                <a href="{{ route('colors.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm màu
                </a>
            </div>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th><i class=""></i> ID</th>
                        <th class="hidden-phone"><i class=""></i> Tên</th>
                        <th><i class=""></i> Trang thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($colors as $color)
                        <tr>
                            <td>{{ $color->id }}</td>
                            <td class="hidden-phone">{{ $color->name }}</td>
                            <td>
                                @if($color->status == 'active')
                                    <span class="badge badge-info label-mini">Hoạt động</span>
                                @else
                                    <span class="badge badge-danger label-mini">Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('colors.edit', $color->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-pencil"></i> 
                                </a>

                                <form action="{{ route('colors.destroy', $color->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash-o"></i> 
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
</div>
<div class="pagination">
    {{ $colors->links() }}
</div>

@endsection
