<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        // Lấy tất cả quyền và nhóm quyền
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy('group'); // Nhóm quyền theo nhóm (ví dụ: role:products, role:orders)
        return view('roles.create', compact('groupedPermissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array',
        ]);

        // Tạo vai trò mới
        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);

        // Lọc các permission hợp lệ
        if ($request->has('permissions')) {
            $validPermissions = Permission::whereIn('id', $request->permissions)
                ->where('guard_name', 'web')
                ->pluck('name')
                ->toArray();

            // Đặt lại guard cho role trước khi gán permission
            $role->guard_name = 'web';
            $role->save();

            $role->syncPermissions($validPermissions);
        }

        return redirect()->route('roles.index')->with('success', 'Vai trò đã được tạo thành công.');
    }

    public function edit(Role $role)
    {
        // Nếu vai trò là superadmin thì không cho chỉnh sửa
        if (strtolower($role->name) === 'super-admin') {
            return redirect()->route('roles.index')->with('error', 'Không thể chỉnh sửa vai trò superadmin!');
        }

        // Lấy tất cả quyền và nhóm quyền
        $permissions = Permission::all();
        $groupedPermissions = $permissions->groupBy('group'); // Nhóm quyền theo nhóm
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Nếu vai trò là superadmin thì không cho cập nhật
        if (strtolower($role->name) === 'superadmin') {
            return redirect()->route('roles.index')->with('error', 'Không thể cập nhật vai trò superadmin!');
        }

        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array|nullable',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Cập nhật tên vai trò và guard_name
        $role->update([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        // Lấy danh sách tên quyền từ ID
        $validPermissions = Permission::whereIn('id', $validated['permissions'] ?? [])
            ->pluck('name')
            ->toArray();

        // Đồng bộ quyền với vai trò
        $role->syncPermissions($validPermissions);

        return redirect()->route('roles.index')->with('success', 'Vai trò đã được cập nhật thành công!');
    }

    public function destroy(Role $role)
    {
        // Nếu vai trò là superadmin thì không cho xóa
        if (strtolower($role->name) === 'super-admin') {
            return redirect()->route('roles.index')->with('error', 'Không thể xóa vai trò superadmin!');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Vai trò đã được xóa thành công!');
    }
}
