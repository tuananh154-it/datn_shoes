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
                        <h1 class="flex-grow-1 fs-3 fw-semibold my-2 my-sm-3">Danh Sách Banner</h1>
                        <nav class="flex-shrink-0 my-2 my-sm-0 ms-sm-3" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('banners.index') }}" style="color: inherit;">Banner</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Danh Sách Banner</li>
                            </ol>
                        </nav>
                    </div>
                </header>
                <div class="span6">
                    <div id="hidden-table-info_length" class="dataTables_length">
                        <form action="{{ route('banners.index') }}" method="GET">
                            <label>Xem
                                <select class="form-control-sm ml-1 custom-select-small" name="per_page" onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select><span style="margin-left:5px;">mục</span>
                            </label>
                        </form>
                    </div>
                </div>
                <div class="span6">
                    <div class="dataTables_filter" id="hidden-table-info_filter">
                        <a href="{{ route('banners.create') }}" class="btn btn-success btn-sm">Tạo mới</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" style="table-layout: fixed; width: 100%;">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 5%;">#</th>
                                <th style="width: 20%;">Ảnh</th>
                                <th style="width: 35%;">Liên kết</th>
                                <th style="width: 20%;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($noResults)
                                <tr>
                                    <td colspan="4" class="text-center">Không có banner phù hợp</td>
                                </tr>
                            @else
                                @foreach ($banners as $banner)
                                    <tr>
                                        <td class="text-center">{{ $banner->id }}</td>
                                        <td><img class="text-truncate" src="{{ asset('storage/' . $banner->image_url) }}" alt="{{ $banner->title }}" width="150"></td>
                                        <td class="text-truncate">{{ $banner->link }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('banners.show', $banner->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="d-inline-block">
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
                    <div class="row-fluid">
                        <div class="span6">
                            <div class="dataTables_info" id="hidden-table-info_info">
                                Hiển thị từ {{ $banners->firstItem() }} đến {{ $banners->lastItem() }} của tổng cộng {{ $banners->total() }} mục
                            </div>
                        </div>
                        <div class="span6">
                            <div class="dataTables_paginate paging_bootstrap pagination">
                                <ul class="pagination">
                                    <li class="prev">
                                        <a href="{{ $banners->previousPageUrl() }}" aria-label="Previous">← Trước</a>
                                    </li>
                                    @foreach ($banners->getUrlRange(1, $banners->lastPage()) as $page => $url)
                                        <li class="{{ $page == $banners->currentPage() ? 'active' : '' }}">
                                            <a href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endforeach
                                    <li class="next">
                                        <a href="{{ $banners->nextPageUrl() }}" aria-label="Next">Sau →</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
