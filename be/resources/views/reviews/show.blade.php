@extends('master')

@section('content')
<style>
    .row {
        padding-top: 60px
    }

    .table th,
    .table td {
        vertical-align: middle;
        text-align: center
    }

    .table th {
        white-space: nowrap;
        background: #f8f9fa
    }

    .table td {
        word-wrap: break-word;
        max-width: 300px
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">Chi tiết đánh giá</header>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Người dùng</th>
                        <th>Vai&nbsp;trò</th>
                        <th>Sản&nbsp;phẩm</th>
                        <th>Size</th>
                        <th>Màu</th>
                        {{-- <th>Giá&nbsp;bán</th> --}}
                        <th>Đánh&nbsp;giá</th>
                        <th>Nội&nbsp;dung</th>
                        <th>Phản&nbsp;hồi</th>
                        <th>Ngày&nbsp;giờ&nbsp;đăng</th>
                        <th>Ảnh</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $user = $review->is_anonymous ? null : $review->user;
                    $detail = $review->orderDetail->productDetail ?? null;
                    $product = $detail?->product;
                    $size = $detail?->size?->name ?? null;
                    $color = $detail?->color?->name ?? null;
                    $img = $product?->image ?? null;
                    @endphp
                    <tr>
                        {{-- ID --}}
                        <td>{{ $review->id }}</td>

                        {{-- USER --}}
                        <td>{{ $user?->name ?? ($review->is_anonymous ? 'Ẩn danh' : 'Không có thông tin') }}</td>
                        <td>{{ $user?->role ?? 'Không có thông tin' }}</td>

                        {{-- PRODUCT --}}
                        <td>{{ $product?->name ?? 'Không có thông tin' }}</td>
                        <td>{{ $size  ?? 'Không có thông tin' }}</td>
                        <td>{{ $color ?? 'Không có thông tin' }}</td>

                        {{-- PRICE --}}
                        {{-- <td>
                            {{ isset($review->orderDetail->price)
                               ? number_format($review->orderDetail->price, 0, ',', '.').' ₫'
                               : 'Không có thông tin' }}
                        </td> --}}

                        {{-- REVIEW --}}
                        <td>{{ $review->rating ?? 'Không có thông tin' }}/5</td>
                        <td>{{ $review->content ?? 'Không có thông tin' }}</td>
                        <td>{{ $review->reply   ?? 'Không có thông tin' }}</td>

                        {{-- DATE-TIME --}}
                        <td>
                            {{ optional($review->created_at)->format('d/m/Y H:i')
                               ?? 'Không có thông tin' }}
                        </td>

                        {{-- IMAGE --}}
                       
                            <!-- @if($img)
                                <a href="{{ asset($img) }}" target="_blank">Xem</a>
                            @else
                                Không có ảnh
                            @endif -->
                        <td>
                            @if ($review->image)
                            <img src="{{ Storage::url($review->image) }}" alt="Review Image" style="max-width: 100px; height: auto;">
                            @else
                            Không có ảnh
                            @endif
                        </td>
                       
                    </tr>
                </tbody>
            </table>

            <div class="d-flex justify-content-start">
                <a href="{{ route('reviews.index') }}" class="btn btn-primary">
                    Quay lại
                </a>
            </div>
        </section>
    </div>
</div>
@endsection