@extends('master')

@section('content')
<style>
    .row{padding-top:60px}
    .table th,.table td{vertical-align:middle;text-align:center}
    .table th{white-space:nowrap;background:#f8f9fa}
    .table td{word-wrap:break-word;max-width:300px}
    .table-responsive{overflow-x:auto}
    .text-truncate{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:250px}
</style>

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">Danh sách bình luận</header>

            {{-- Tìm kiếm --}}
            <div class="mb-3">
                <form action="{{ route('comments.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                   placeholder="Tìm kiếm bình luận"
                                   value="{{ request()->search }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Chọn số bản ghi / trang --}}
            {{-- <div class="d-flex justify-content-between px-3 py-2">
                <form action="{{ route('comments.index') }}" method="GET">
                    <label>Xem
                        <select class="form-control-sm ml-1 custom-select-small" name="per_page"
                                onchange="this.form.submit()">
                            @foreach([10,25,50,100] as $n)
                                <option value="{{ $n }}" {{ request('per_page',10)==$n?'selected':'' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </label>
                </form>
            </div> --}}

            {{-- Bảng bình luận --}}
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>Người dùng</th>
                            <th>Sản phẩm</th>
                            <th>Nội dung</th>
                            <!-- <th>Ảnh</th> -->
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($comments->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Không có bình luận nào</td>
                            </tr>
                        @else
                            @foreach ($comments as $item)
                                <tr>
                                    <td>{{ $item->user->name }}</td>
                                    <td class="text-truncate">{{ $item->product->name }}</td>
                                    <td class="text-truncate">{{ $item->content }}</td>
                                    <!-- <td>
                                        @if($item->file)
                                            <a href="{{ Storage::url($item->file) }}" target="_blank">Tải file</a>
                                        @else
                                            Không có file
                                        @endif
                                    </td> -->
                                    <td>
                                        <a href="{{ route('comments.show',$item->id) }}"
                                           class="btn btn-primary btn-sm">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            {{-- Phân trang --}}
            <div class="row-fluid">
                <div class="span6">
                    <div class="dataTables_info">
                        Hiển thị từ {{ $comments->firstItem() }} đến {{ $comments->lastItem() }}
                        của tổng cộng {{ $comments->total() }} mục
                    </div>
                </div>
                <div class="span6">
                    <div class="dataTables_paginate paging_bootstrap pagination">
                        <ul class="pagination">
                            <li class="prev">
                                <a href="{{ $comments->previousPageUrl() }}">← Trước</a>
                            </li>
                            @foreach($comments->getUrlRange(1,$comments->lastPage()) as $page=>$url)
                                <li class="{{ $page==$comments->currentPage()?'active':'' }}">
                                    <a href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="next">
                                <a href="{{ $comments->nextPageUrl() }}">Sau →</a>
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