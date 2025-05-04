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
                                                                            @elseif($request->status == 'rejected')
                                                                                <span class="badge badge-danger">Đã từ chối</span>
                                                                            @else
                                                                                <span class="badge badge-warning">Chưa duyệt</span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="action-btns">
                                                                            <a href="{{ route('return_requests.show', $request->id) }}"
                                                                                class="btn btn-primary">
                                                                                <i class="fa fa-eye"></i> Xem chi tiết
                                                                            </a>
                                                                            @if($request->status == 'reviewed')
                                                                                <form
                                                                                    action="{{ route('return_requests.approve', $request->id) }}"
                                                                                    method="POST" style="display: inline;">
                                                                                    @csrf
                                                                                    <button type="submit" class="btn btn-success">
                                                                                        <i class="fa fa-check"></i> Duyệt
                                                                                    </button>
                                                                                </form>
                                                                                <button type="button" class="btn btn-danger btn-reject"
                                                                                    data-url="{{ route('return_requests.reject', $request->id) }}">
                                                                                    <i class="fa fa-times"></i> Từ chối
                                                                                </button>
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

<!-- Modal từ chối -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="rejectForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header" style="background-color: #FF6C60; color: white;">
                    <h5 class="modal-title" id="rejectModalLabel">Lý do từ chối yêu cầu hoàn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea name="reason" id="rejectReason" class="form-control" rows="4"
                            placeholder="Nhập lý do từ chối..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" id="rejectConfirmButton" class="btn btn-danger">Xác nhận từ chối</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const rejectButtons = document.querySelectorAll('.btn-reject');
        const rejectForm = document.getElementById('rejectForm');
        const rejectModal = new bootstrap.Modal(document.getElementById('rejectModal'));
        const rejectReason = document.getElementById('rejectReason');
        const rejectConfirmButton = document.getElementById('rejectConfirmButton');

        rejectButtons.forEach(button => {
            button.addEventListener('click', function () {
                const requestId = this.getAttribute('data-request-id');
                const url = this.getAttribute('data-url');
                rejectForm.action = url;
                rejectReason.value = '';
                rejectModal.show();
                setTimeout(() => rejectReason.focus(), 500);
            });
        });

        rejectConfirmButton.addEventListener('click', function (event) {
            if (rejectReason.value.trim() === '') {
                event.preventDefault();
                alert('Vui lòng nhập lý do từ chối trước khi xác nhận.');
            }
        });
    });
</script>