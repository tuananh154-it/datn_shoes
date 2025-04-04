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
                Danh sách mã giảm giá
            </header>
           
            {{-- Tìm kiếm, lọc voucher --}}
            <div class="mb-3">
                <form action="{{ route('vouchers.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm" value="{{ request()->search }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="active" {{ request()->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="inactive" {{ request()->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Thêm mới --}}
            <div class="mb-3">
                <a href="{{ route('vouchers.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm mã giảm giá 
                </a>
            </div>

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên mã giảm giá</th>
                        <th>Phần trăm giảm giá</th>
                        <th>Số lượng</th> {{-- Thêm cột số lượng --}}
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $voucher)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $voucher->name }}</td>
                            <td>{{ $voucher->discount_percent }} %</td>
                            <td>{{ $voucher->quantity }}</td> {{-- Hiển thị số lượng voucher --}}
                            <td>
                                @if ($voucher->status == 'active')
                                    <span class="badge badge-info">Hoạt động</span>
                                @else
                                    <span class="badge badge-danger">Không hoạt động</span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-warning btn-sm" href="{{ route('vouchers.show', $voucher->id) }}"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-primary btn-sm" href="{{ route('vouchers.edit', $voucher->id) }}"><i class="fa fa-pencil"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {!! $vouchers->links() !!}
            </div>
        </section>
    </div>
</div>
@endsection
