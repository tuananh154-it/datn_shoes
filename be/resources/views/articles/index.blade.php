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
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Danh sách bài viết</h1>
                    <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('articles.index') }}" style="color: inherit;">Bài viết</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Danh sách bài viết</li>
                        </ol>
                    </nav>
                </div>
            </header>
            <div class="span6">
                <div id="hidden-table-info_length" class="dataTables_length">
                    <form action="{{ route('articles.index') }}" method="GET">
                        <label>Xem
                            <select class="form-control-sm ml-1 custom-select-small" name="per_page"
                                onchange="this.form.submit()">
                                <option value="10"
                                    {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25"
                                    {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50"
                                    {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100"
                                    {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select><span style="margin-left:5px;">mục</span>
                        </label>
                    </form>
                </div>
            </div>
            <div class="span6">
                <div class="dataTables_filter" id="hidden-table-info_filter">
                    <a href="{{ route('articles.create') }}" class="btn btn-success btn-sm">Tạo
                        mới</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped" style="table-layout: fixed; width: 100%;">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 5%;">#</th>
                            <th style="width: 15%;">Tên</th>
                            <th style="width: 20%;">Tiêu đề</th>
                            <th style="width: 30%;">Nội dung</th>
                            <th style="width: 20%;">Hình ảnh</th>
                            <th style="width: 10%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($articles->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">Không có bài viết phù hợp</td>
                            </tr>
                        @else
                            @foreach ($articles as $item)
                                <tr>
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ Str::limit($item->content, 50) }}</td>
                                    <td class="text-center">
                                        @if ($item->image)
                                            <img src="{{ Storage::url($item->image) }}" alt="Hình ảnh bài viết" width="80">
                                        @else
                                            <span class="text-muted">Không có ảnh</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('articles.show', $item->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <form action="{{ route('articles.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <p>Hiển thị từ {{ $articles->firstItem() }} đến {{ $articles->lastItem() }} của tổng cộng {{ $articles->total() }} mục</p>
                </div>
                <div class="col-md-6 text-right">
                    {{ $articles->links() }}
                </div>
            </div>
        </section>
    </div>
</div>




    <script src="/client/flatlab-4/{{ asset('assets') }}/js/dynamic_table_init.js"></script>
@endsection
