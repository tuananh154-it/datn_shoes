@extends('master')

@section('content')
    <div class="container">
        <h1>Edit Role</h1>
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Để Laravel biết đây là form update -->

            <div class="mb-3">
                <label for="name" class="form-label">Role Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter role name"
                    value="{{ old('name', $role->name) }}" required>
            </div>

            <div class="mb-3">
                <h3 class="bg-primary text-white p-2 text-center">PHÂN QUYỀN</h3>
                <table class="table table-bordered">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>STT</th>
                            <th>Chức năng</th>
                            <th>
                                <input type="checkbox" id="select-all-show" class="form-check-input" onclick="toggleSelect('show')">
                                Xem
                            </th>
                            <th>
                                <input type="checkbox" id="select-all-create" class="form-check-input" onclick="toggleSelect('create')">
                                Thêm
                            </th>
                            <th>
                                <input type="checkbox" id="select-all-edit" class="form-check-input" onclick="toggleSelect('edit')">
                                Sửa
                            </th>
                            <th>
                                <input type="checkbox" id="select-all-delete" class="form-check-input" onclick="toggleSelect('delete')">
                                Xóa
                            </th>
                            {{-- <th>
                                <input type="checkbox" id="select-all-approve" class="form-check-input" onclick="toggleSelect('approve')">
                                Duyệt
                            </th> --}}
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @php $stt = 1; @endphp
                        @foreach ($groupedPermissions as $group => $permissions)
                            <tr>
                                <td class="text-center">{{ $stt++ }}</td>
                                <td>{{ ucwords(str_replace('_', ' ', $group)) }}</td>

                                @foreach (['show', 'create', 'edit', 'delete'] as $action)
                                    @php
                                        $groupName = str_replace('role:', '', $group);
                                        $permissionName = "{$action}-{$groupName}";
                                        $permission = $permissions->firstWhere('name', $permissionName);
                                        // Kiểm tra xem permission đã được gán cho role chưa
                                        $hasPermission = $role->permissions->contains($permission);
                                    @endphp
                                    <td class="text-center">
                                        @if ($permission)
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                class="form-check-input permission-checkbox {{ $action }}-checkbox" {{ $hasPermission ? 'checked' : '' }}>
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

            <button type="submit" class="btn btn-success">Cập nhật</button>
        </form>
    </div>

    <script>
        function toggleSelect(action) {
            // Lấy tất cả checkbox trong cột tương ứng
            const checkboxes = document.querySelectorAll(`.${action}-checkbox`);
            const selectAll = document.getElementById(`select-all-${action}`);

            // Nếu checkbox "Chọn tất cả" được chọn, thì chọn tất cả checkbox trong cột
            checkboxes.forEach((checkbox) => {
                checkbox.checked = selectAll.checked;
            });
        }
    </script>
@endsection
