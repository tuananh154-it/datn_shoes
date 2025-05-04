@extends('master')

@section('content')

    <style>
        .row {
            padding-top: 60px;
        }
        .table th, .table td {
            vertical-align: middle;
            text-align: center;
        }

        .table th {
            white-space: nowrap;
            background-color: #f8f9fa;
        }

        .table td {
            word-wrap: break-word;
            max-width: 300px; /* Giới hạn chiều rộng của ô nội dung */
        }

        .table-responsive {
            overflow-x: auto;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 250px;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header">
                    Danh sách bình luận
                </header>

                {{-- Form tìm kiếm --}}
                <div class="mb-3">
                    <form action="{{ route('comments.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bình luận" value="{{ request()->search }}">
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Form chọn số lượng hiển thị mỗi trang --}}
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

                {{-- Bảng hiển thị bình luận --}}
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr class="text-center">
                                <th>Người dùng</th>
                                <th>Sản phẩm</th>
                                <th>Nội dung</th>
                                <th>Ảnh</th>
                                <th>Số sao</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($comments->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center">Không có bình luận nào</td>
                                </tr>
                            @else
                                @foreach ($comments as $item)
                                    <tr>
                                        <td>{{ $item->user->name }}</td>
                                        <td class="text-truncate">{{ $item->product->name }}</td>
                                        <td class="text-truncate" style="max-width: 300px;">{{ $item->content }}</td>
                                        <td>
                                            @if ($item->file)
                                                <a href="{{ Storage::url($item->file) }}" target="_blank">Tải file</a>
                                            @else
                                                Không có file
                                            @endif
                                        </td>
                                        <td class="text-end">{{ $item->star_rating }}</td>
                                        <td class="text-center">
                                            {{-- Nút xem chi tiết --}}
                                            @if (!$item->deleted_at)
                                                <a href="{{ route('comments.show', $item->id) }}"
                                                   class="btn btn-primary btn-sm">
                                                   <i class="fa fa-eye"></i>
                                                </a>
                                            @endif

                                            {{-- Nút khôi phục (chỉ hiện khi đã xoá) --}}
                                            @if ($item->deleted_at)
                                                <form action="{{ route('comments.restore', $item->id) }}"
                                                      method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <i class="bi bi-arrow-repeat"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            {{-- Form xoá vĩnh viễn đã bị gỡ bỏ --}}
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
                        <div class="dataTables_info" id="hidden-table-info_info">
                            Hiển thị từ {{ $comments->firstItem() }} đến {{ $comments->lastItem() }} của tổng
                            cộng {{ $comments->total() }} mục
                        </div>
                    </div>
                    <div class="span6">
                        <div class="dataTables_paginate paging_bootstrap pagination">
                            <ul class="pagination">
                                <li class="prev">
                                    <a href="{{ $comments->previousPageUrl() }}" aria-label="Previous">← Trước</a>
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
