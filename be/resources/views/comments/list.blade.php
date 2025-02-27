@extends('master')

@section('content')

    <style>
        .row {
            padding-top: 60px;
        }

        .text-truncate {
            max-width: 200px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .custom-select-small {
            font-size: 0.70rem;
            height: 20px;
            padding: 2px 6px;
            width: auto;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Danh Sách Bình Luận</h1>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('comments.index') }}" style="color: inherit;">Bình Luận</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Danh Sách</li>
                            </ol>
                        </nav>
                    </div>
                </header>
                <div class="d-flex justify-content-between px-3 py-2">
                    <form action="{{ route('comments.index') }}" method="GET">
                        <label>Xem
                            <select class="form-control-sm ml-1 custom-select-small" name="per_page"
                                onchange="this.form.submit()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </label>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Người dùng</th>
                                <th>Sản phẩm</th>
                                <th>Nội dung</th>
                                <th>Ảnh</th>
                                <th>Số sao</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($noResults)
                                <tr>
                                    <td colspan="7" class="text-center">Không có bình luận phù hợp</td>
                                </tr>
                            @else
                                @foreach ($comments as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->user->username }}</td>
                                        <td class="text-truncate">{{ $item->product->name }}</td>
                                        <td class="text-truncate" style="max-width: 300px;">{{ $item->comment }}</td>
                                        <td>
                                            @if ($item->file)
                                                <a href="{{ Storage::url($item->file) }}" target="_blank">Tải file</a>
                                            @else
                                                Không có file
                                            @endif
                                        </td>
                                        <td class="text-end">{{ $item->star_rating }} / 5</td>
                                        <td class="text-center">
                                            @if (!$item->deleted_at)
                                                <a href="{{ route('comments.show', $item->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                            @endif
                                            @if ($item->deleted_at)
                                                <form action="{{ route('comments.restore', $item->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm"><i class="bi bi-arrow-repeat"></i></button>
                                                </form>
                                            @else
                                                <form action="{{ route('comments.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="dataTables_info" id="hidden-table-info_info">
                            Hiển thị từ {{ $comments->firstItem() }} đến {{ $comments->lastItem() }} của tổng
                            cộng {{ $comments->total() }} mục
                        </div>
                    </div>
                    <div class="span6">
                        <div class="dataTables_paginate paging_bootstrap pagination">
                            <ul class="pagination">
                                <li class="prev">
                                    <a href="{{ $comments->previousPageUrl() }}" aria-label="Previous">←
                                        Trước</a>
                                </li>
                                @foreach ($comments->getUrlRange(1, $comments->lastPage()) as $page => $url)
                                    <li class="{{ $page == $comments->currentPage() ? 'active' : '' }}">
                                        <a href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                                <li class="next">
                                    <a href="{{ $comments->nextPageUrl() }}" aria-label="Next">Sau →</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script src="{{ asset('assets/admin/js/dynamic_table_init.js') }}"></script>

@endsection
