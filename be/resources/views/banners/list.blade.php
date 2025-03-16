@extends('master')

@section('content')

    <style>
        .row {
            padding-top: 60px;
        }

    </style>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">
                    Danh sách Banner
                </header>
                 {{-- tim kiem ,loc thuong hieu--}}
                 <div class="mb-3"  >
                    <form action="{{ route('banners.index') }}" method="GET">
                    </form>
                </div>
                {{-- them moi  --}}
                <div class="mb-3">
                    <a href="{{ route('banners.create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Thêm Banner
                    </a>
                </div>
                <div class="span6">
                    <div id="hidden-table-info_length" class="dataTables_length">
                        <form action="{{ route('banners.index') }}" method="GET">
                            <label>Xem
                                <select class="form-control-sm ml-1 custom-select-small" name="per_page"
                                    onchange="this.form.submit()">
                                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                </select><span style="margin-left:5px;">mục</span>
                            </label>
                        </form>
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
