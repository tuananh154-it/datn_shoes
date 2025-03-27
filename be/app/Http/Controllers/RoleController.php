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
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
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

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array|nullable',
            'permissions.*' => 'exists:permissions,id',
        ]);

        // Cập nhật tên vai trò và guard_name
        $role->update([
            'name' => $validated['name'],
            'guard_name' => 'web', // Đặt guard_name mặc định là 'web'
        ]);

        // Lấy danh sách tên quyền từ ID
        $validPermissions = Permission::whereIn('id', $validated['permissions'] ?? [])
            ->pluck('name') // Lấy tên quyền
            ->toArray();

        // Đồng bộ quyền với vai trò
        $role->syncPermissions($validPermissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}
