@extends('master')

@section('content')

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
                Bảng kích thước
            </header>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-3">
                <a href="{{ route('sizes.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm kích thước
                </a>
            </div>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th><i class=""></i> ID</th>
                        <th class="hidden-phone"><i class=""></i> Tên</th>
                        <th><i class=""></i> Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sizes as $size)
                        <tr>
                            <td>{{ $size->id }}</td>
                            <td class="hidden-phone">{{ $size->name }}</td>
                            <td>
                                @if($color->status == 'active')
                                <span class="badge badge-info label-mini">Hoạt động</span>
                            @else
                                <span class="badge badge-danger label-mini">Không hoạt động</span>
                            @endif
                            </td>
                            <td>
                                <a href="{{ route('sizes.edit', $size->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fa fa-pencil"></i> 
                                </a>

                                <form action="{{ route('sizes.destroy', $size->id) }}" method="POST" style="display:inline;">
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

@endsection
