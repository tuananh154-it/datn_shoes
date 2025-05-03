
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
                Danh sách vai trò
            </header>
             {{-- tim kiem ,loc thuong hieu--}}
             <div class="mb-3">
                <form action="{{ route('roles.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm vai trò" value="{{ request()->search }}">
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
                <a href="{{ route('roles.create') }}" class="btn btn-light" style="margin-top:12px;background-color:#78CD51;color:white;border:#78CD51">
                    <i class="fa fa-plus"></i> Thêm quyền hạn
                </a>
            </div>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên Vai Trò</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $index => $role)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ ucfirst($role->name) }}</td>
                            <td class="action-btns">
                                @if (strtolower($role->name) !== 'super-admin')
                                    <div class="btn-group">
                                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning" style="background-color: #41CAC0;border:#41CAC0">
                                            <i class="fa fa-pencil"></i>
                                        </a>

                                        {{-- <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-trash"></i> Xóa
                                            </button>
                                        </form> --}}
                                    </div>
                                @else
                                    <span class="badge bg-secondary"></span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{-- <div class="d-flex justify-content-center">
                {!! $categories->links() !!}
            </div> --}}
        </section>
    </div>
</div>

@endsection
