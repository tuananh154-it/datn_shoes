@extends('layouts.main')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <section class="card">
            <header class="card-header">
               Danh sach Voucher
            </header>
            <div ><button class="btn btn-success"><a href="{{route('vouchers.create')}}">Them moi Voucher</a></button></div>
            <table class="table table-striped table-advance table-hover">
                <thead>
                <tr>
                    <th> Số tiền giảm</th>
                    <th> Phần trăm giảm giá</th>
                    <th>Ngày hết hạn </th>
                    <th>Số tiền mua tối thiểu </th>
                    <th>Mức giảm tối đa </th>
                    <th> Điều khoản và điều kiện sử dụng </th>
                    <th>Trạng thái </th>
                    <th>Thời gian tạo</th>
                    <th>Thời gian cập nhật</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($vouchers as $voucher)
                <tr>
                    <td>{{ $voucher->discount_amount }}</td>
                    <td>{{ $voucher->discount_percent }}</td>
                    <td>{{ date('d-m-Y', strtotime($voucher->expiration_date)) }}</td>
                    <td>{{ $voucher->min_purchase_amount }}</td>
                    <td>{{ $voucher->max_discount_amount }}</td>
                    <td>{{ $voucher->terms_and_conditions }}</td>
                    <td><span class="badge badge-info label-mini">{{ $voucher->status }}</span></td>
                    <td>{{ $voucher->created_at }}</td>
                    <td>{{ $voucher->updated_at }}</td>
                    {{-- <td>
                        <button class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>
                        <button class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></button>
                        <button class="btn btn-danger btn-sm"><i class="fa fa-trash-o "></i></button>
                    </td> --}}
                    <td>
                        <button class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>
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
</div
@endsection