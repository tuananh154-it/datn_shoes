@extends('master')

@section('content')

    <style>
        .container {
            padding-top: 60px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #FF6C60;
            color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .btn-group .btn {
            margin-right: 5px;
        }

        .action-btns {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: white;
        }
    </style>

    <div class="container">
        <div class="card shadow">
            <div class="card-header" style="background-color: #41CAC0; color: white;">
                <h4 class="mb-0">Danh sách yêu cầu hoàn</h4>
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <table class="table table-bordered table-hover">
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
                                        <i class="fa fa-eye"></i> Xem chi tiết
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
            </div>
        </div>
    </div>

@endsection