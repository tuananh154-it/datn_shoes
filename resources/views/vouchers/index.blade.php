
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
                Danh sách Voucher
            </header>
           
            <div class="mb-3">
                <a href="{{ route('vouchers.create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Thêm Voucher
                </a>
            </div>
            {{-- tim kiem ,loc voucher --}}
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

            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>Mã Voucher </th>
                        <th>Tên Voucher </th>
                        <th> Số tiền giảm</th>
                        <th> Phần trăm giảm giá</th>
                        {{-- <th>Ngày hết hạn </th>
                        <th>Số tiền mua tối thiểu </th>
                        <th>Mức giảm tối đa </th>
                        <th> Điều khoản và điều kiện sử dụng </th>
                        <th>Trạng thái </th>
                        <th>Thời gian tạo</th>
                        <th>Thời gian cập nhật</th> --}}
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($vouchers as $voucher)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$voucher->name}}</td>
                        <td>{{ $voucher->discount_amount }}</td>
                        {{-- <td>{{ $voucher->discount_percent }}</td>
                        <td>{{ date('d-m-Y', strtotime($voucher->expiration_date)) }}</td>
                        <td>{{ $voucher->min_purchase_amount }}</td>
                        <td>{{ $voucher->max_discount_amount }}</td>
                        <td>{{ $voucher->terms_and_conditions }}</td> --}}
                        {{-- <td><span class="badge badge-info label-mini">{{ $voucher->status }}</span></td> --}}
                        <td>
                            @if ($voucher->status == 'active')
                                <span class="badge badge-info">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        
                        <td>{{ $voucher->created_at }}</td>
                        <td>{{ $voucher->updated_at }}</td>
                      
                        <td>
                            {{-- <button class="btn btn-success btn-sm"><i class="fa fa-check"></i></button> --}}
                            <a class="btn btn-success btn-sm" href="{{ route('vouchers.edit', $voucher->id) }}"><i class="fa fa-pencil"></i></a> 
                            <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Ban co chac chan muon xoa voucher?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" type="submit"><i class="fa fa-trash-o "></i></button>
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
