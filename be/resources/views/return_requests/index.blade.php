
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
                Danh sách hoàn hàng
            </header>
             {{-- tim kiem ,loc thuong hieu--}}
             <div class="mb-3">
                <form action="{{  route('return_requests.index') }}" method="GET">
                    <div class="row">
                        {{-- <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm đơn hàng" value="{{ request()->search }}">
                        </div> --}}
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">Chọn trạng thái</option>
                                <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ request()->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="approved" {{ request()->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request()->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                        {{-- <div class="col-md-2">
                            <a href="{{route("categories.index")}}" class="btn btn-success btn-sm">Quay lai danh sach</a>
                        </div> --}}
                    </div>

                </form>
            </div>
            {{-- them moi  --}}


            <table class="table table-striped table-advance table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Đơn hàng</th>
                        <th>Người yêu cầu</th>
                        <th>Lý do</th>
                        <th>Trạng thái</th>
                        <th>Ngày yêu cầu</th>
                        <th>Xác nhận</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returnRequests as $request)
                        <tr>
                            <td>{{ $request->id }}</td>
                            <td>{{ $request->order->id ?? 'N/A' }}</td>
                            <td>{{ $request->user->name ?? 'N/A' }}</td>
                            <td>{{ $request->reason }}</td>
                            <td>{{ $request->status }}</td>
                            <td>{{ $request->requested_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($request->status == 'approved')
                                    <span class="badge badge-success">Đã duyệt</span>
                                @else
                                    <span class="badge badge-warning">Chưa duyệt</span>
                                @endif
                            </td>
                            <td class="action-btns">
                                <a href="{{ route('return_requests.show', $request->id) }}" class="btn btn-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if($request->status !== 'approved')
                                    <form action="{{ route('return_requests.approve', $request->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success">
                                            <i class="fa fa-check"></i> Duyệt
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $returnRequests->links() }}

        </section>
    </div>
</div>

@endsection
