<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    public function index(Request $request)
    {
        // Lấy tất cả người dùng và kèm theo các quyền của họ
        $users = User::with('roles');
        // Lọc theo tên người dùng nếu có giá trị tìm kiếm
        if ($request->has('name_search') && $request->name_search != '') {
            $users->where('name', 'like', '%' . $request->name_search . '%');
        }
        if ($request->has('email_search') && $request->email_search != '') {
            $users->where('email', 'like', '%' . $request->email_search . '%');
        }

        if ($request->has('role_search') && $request->role_search != '') {
            $users->whereHas('roles', function ($query) use ($request) {
                $query->where('name', $request->role_search);
            });
        }

        // Lấy danh sách người dùng sau khi lọc
        $users = $users->paginate(5)->appends($request->all());

        // Trả về view danh sách người dùng
        return view('users.index', compact('users'));
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        $roles = Role::all(); // Lấy tất cả các vai trò
        return view('users.create', compact('roles')); // Truyền danh sách vai trò đến view
    }

    // Xử lý tạo người dùng mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'gender' => 'required|string|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'role' => 'required|string|in:user,staff,admin,superadmin', // Xác nhận role
        ]);

        // Tạo người dùng
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'role' => $validated['role'],
        ]);

        // Gán role cho người dùng (dùng Spatie)
        $user->assignRole($validated['role']);

        // Redirect về danh sách người dùng
        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công!');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user')); // Truyền dữ liệu đến view
    }



    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'required|string|in:male,female,other',
            'password' => 'nullable|string|min:6',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            'role' => 'nullable|string|in:user,staff,admin,superadmin', // Validate vai trò nếu có
        ]);

        // Cập nhật thông tin người dùng
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'address' => $validated['address'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'role' => $validated['role'] ?? $user->role,
        ]);

        // Nếu có password mới thì cập nhật
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        // Nếu có thay đổi về role, gán lại role mới
        if (isset($validated['role'])) {
            $user->syncRoles([$validated['role']]);
        }

        // Lưu lại thông tin người dùng
        $user->save();

        return redirect()->route('users.index')->with('success', 'Thông tin người dùng đã được cập nhật!');
    }

    // Xử lý xóa người dùng
    public function destroy(User $user)
    {
        // Xóa người dùng
        $user->delete();

        // Chuyển hướng về danh sách người dùng và hiển thị thông báo thành công
        return redirect()->route('users.index')->with('success', 'Người dùng đã được xóa!');
    }
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }
    public function updateApi(Request $request, $user_id)
    {
        // Logic cập nhật người dùng thông qua API
        $user = User::findOrFail($user_id);
        $user->update($request->all()); // Cập nhật thông tin người dùng
        return response()->json(['message' => 'User updated successfully']);
    }
}
