<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Enums\RoleEnum;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Hiển thị danh sách người dùng
    public function index()
    {
        // Lấy tất cả người dùng và kèm theo các quyền của họ
        $users = User::with('roles')->get();
        return view('users.index', compact('users'));  // Trả về view danh sách người dùng
    }

    // Hiển thị form tạo người dùng mới
    public function create()
    {
        return view('users.create');  // Trả về form tạo người dùng
    }

    // Xử lý tạo người dùng mới
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Tạo người dùng mới
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        // Gán quyền cho người dùng (có thể thay đổi quyền sau này)
        $user->syncRoles(RoleEnum::ADMIN);  // Gán quyền ADMIN, nếu muốn thêm nhiều quyền, sử dụng syncRoles(['admin', 'editor'])

        // Chuyển hướng về danh sách người dùng và hiển thị thông báo thành công
        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công!');
    }

    // Hiển thị form chỉnh sửa người dùng
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));  // Trả về form chỉnh sửa người dùng
    }

    // Xử lý cập nhật thông tin người dùng
    public function update(Request $request, User $user)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),  // Kiểm tra trùng email, ngoại trừ chính người dùng hiện tại
            ],
            'password' => 'nullable|string|min:6',  // Mật khẩu có thể bỏ qua nếu không muốn thay đổi
        ]);

        // Cập nhật thông tin người dùng
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Nếu có thay đổi mật khẩu, thì cập nhật mật khẩu mới
        if ($validated['password']) {
            $user->password = bcrypt($validated['password']);
        }

        // Lưu lại thay đổi
        $user->save();

        // Chuyển hướng về danh sách người dùng và hiển thị thông báo thành công
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
}
