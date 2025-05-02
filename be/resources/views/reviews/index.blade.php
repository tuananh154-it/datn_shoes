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
                    Danh sách đánh giá
                </header>

                {{-- Form tìm kiếm --}}
                {{-- <div class="mb-3">
                    <form action="{{ route('reviews.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm đánh giá" value="{{ request()->search }}">
                            </div>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                            </div>
                        </div>
                    </form>
                </div> --}}
{{-- Form tìm kiếm --}}
<div class="mb-3">
    <form action="{{ route('reviews.index') }}" method="GET">
        <div class="row">
            {{-- Nội dung đánh giá --}}
            <div class="col-md-3 mb-2">
                <input  type="text"
                        name="search"
                        class="form-control"
                        placeholder="Tìm nội dung đánh giá"
                        value="{{ request('search') }}">
            </div>

            {{-- Tên sản phẩm --}}
            <div class="col-md-3 mb-2">
                <input  type="text"
                        name="product"
                        class="form-control"
                        placeholder="Tìm theo tên sản phẩm"
                        value="{{ request('product') }}">
            </div>

            {{-- Rating (1-5) --}}
            <div class="col-md-2 mb-2">
                <select name="rating" class="form-control">
                    <option value="">Chọn rating</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} sao
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Nút tìm kiếm --}}
            <div class="col-md-2 mb-2">
                <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
            </div>
        </div>
    </form>
</div>

                {{-- Form chọn số lượng hiển thị mỗi trang --}}
                {{-- <div class="d-flex justify-content-between px-3 py-2">
                    <form action="{{ route('reviews.index') }}" method="GET">
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
                </div> --}}

                {{-- Bảng hiển thị bình luận --}}
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Order ID</th>
                                <th>Người dùng</th>
                                <th>Sản phẩm</th>
                                <th>Nội dung</th>
                                <th>Rating</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reviews as $rv)
                                <tr>
                                    <td>{{ $rv->order->code ?? $rv->order_id }}</td>
                                    <td>{{ $rv->user->name ?? '—' }}</td>
                                    <td>
                                        {{ optional($rv->orderDetail->productDetail->product)->name ?? '—' }}
                                        @if($rv->orderDetail)
                                            <br>
                                            {{-- <small class="text-muted">
                                                {{ $rv->orderDetail->productDetail->color->name ?? '' }}
                                                {{ $rv->orderDetail->productDetail->size->name ? '/'.$rv->orderDetail->productDetail->size->name : '' }}
                                            </small> --}}
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($rv->content, 40) }}</td>
                                    <td class="text-center">{{ $rv->rating }}/5</td>
                                    <td>{{ $rv->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('reviews.show', $rv->id) }}" class="btn btn-success btn-sm">  <i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center">Chưa có đánh giá.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{-- ...Bảng hiển thị đánh giá ở trên --}}

</div> {{-- .table-responsive --}}

{{-- PHÂN TRANG --}}
{{-- <div class="row-fluid">
    <div class="span6">
        <div class="dataTables_info">
            Hiển thị từ {{ $reviews->firstItem() }} đến {{ $reviews->lastItem() }}
            của tổng cộng {{ $reviews->total() }} mục
        </div>
    </div>
    <div class="span6">
        <div class="dataTables_paginate paging_bootstrap pagination">
            <ul class="pagination">
                <li class="prev">
                    <a href="{{ $reviews->previousPageUrl() }}">← Trước</a>
                </li>
                @foreach($reviews->getUrlRange(1,$reviews->lastPage()) as $page=>$url)
                    <li class="{{ $page==$reviews->currentPage()?'active':'' }}">
                        <a href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endforeach
                <li class="next">
                    <a href="{{ $reviews->nextPageUrl() }}">Sau →</a>
                </li>
            </ul>
        </div>
    </div>
</div> --}}
<div class="row">
    <div class="col-12">
        {{ $reviews->links() }}
    </div>
</div>

                {{-- Phân trang --}}
                {{-- <div class="row-fluid">
                    <div class="span6">
                        <div class="dataTables_info" id="hidden-table-info_info">
                            Hiển thị từ {{ $reviews->firstItem() }} đến {{ $reviews->lastItem() }} của tổng
                            cộng {{ $reviews->total() }} mục
                        </div>
                    </div>
                    <div class="span6">
                        <div class="dataTables_paginate paging_bootstrap pagination">
                            <ul class="pagination">
                                <li class="prev">
                                    <a href="{{ $reviews->previousPageUrl() }}" aria-label="Previous">← Trước</a>
                                </li>
                                @foreach ($reviews->getUrlRange(1, $reviews->lastPage()) as $page => $url)
                                    <li class="{{ $page == $reviews->currentPage() ? 'active' : '' }}">
                                        <a href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                                <li class="next">
                                    <a href="{{ $reviews->nextPageUrl() }}" aria-label="Next">Sau →</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> --}}

            </section>
        </div>
    </div>

    <script src="{{ asset('assets/admin/js/dynamic_table_init.js') }}"></script>

@endsection
