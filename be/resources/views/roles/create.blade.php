@extends('master')

@section('content')
    <div class="container">
        <h1>Create Role</h1>
        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Role Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter role name" required>
            </div>

            <div class="mb-3">
                <h3 class="bg-primary text-white p-2 text-center">PHÂN QUYỀN</h3>
                <table class="table table-bordered">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>STT</th>
                            <th>Chức năng</th>
                            <th>Xem</th>
                            <th>Thêm</th>
                            <th>Sửa</th>
                            <th>Xóa</th>
                            <th>Duyệt</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @php $stt = 1; @endphp
                        @foreach ($groupedPermissions as $group => $permissions)
                                        <tr>
                                            <td>{{ $stt++ }}</td>
                                            <td>{{ ucwords(str_replace('_', ' ', $group)) }}</td>

                                            @foreach (['show', 'create', 'edit', 'delete', 'approve'] as $action)
                                                                @php
                                                                    // Lấy phần sau dấu ":" trong group, ví dụ: "role:products" => "products"
                                                                    $groupName = str_replace('role:', '', $group);
                                                                    // Tạo tên quyền theo dạng action-groupName, ví dụ: show-products, create-products
                                                                    $permissionName = "{$action}-{$groupName}";
                                                                    $permission = $permissions->firstWhere('name', $permissionName);
                                                                @endphp
                                                                <td>
                                                                    @if ($permission)
                                                                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                                            class="form-check-input">
                                                                    @else
                                                                        <span class="text-muted">Không có quyền</span>
                                                                    @endif
                                                                </td>
                                            @endforeach
                                        </tr>
                        @endforeach



                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-success">Thêm mới</button>
        </form>
    </div>
@endsection
