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
        $users = $users->get();

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
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'gender' => 'required|string|in:male,female,other', // Kiểm tra giới tính
            'password' => 'nullable|string|min:6',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
        ]);

        // Tạo người dùng mới
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'address' => $validated['address'],
            'phone_number' => $validated['phone_number'],
        ]);
        // Gán vai trò cho người dùng
        if ($request->has('roles')) {
            $user->syncRoles($request->roles);
        }

        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công!');
    }
    
    public function edit(User $user)
    {
        $roles = Role::all(); // Lấy tất cả các vai trò
        return view('users.edit', compact('user', 'roles')); // Truyền dữ liệu đến view
    }

 
    public function update(Request $request, User $user)
    {
        // Validate chỉ các trường bắt buộc
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'required|string|in:male,female,other',
        ]);
    
        // Cập nhật thông tin người dùng
        $user->name = $validated['name'];
        $user->email = $validated['email'];
    
        // Nếu có thay đổi mật khẩu, thì cập nhật mật khẩu mới
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
    
        // Cập nhật các trường bổ sung (không cần validate)
        if ($request->has('date_of_birth')) {
            $user->date_of_birth = $request->input('date_of_birth');
        }
    
        if ($request->has('address')) {
            $user->address = $request->input('address');
        }
    
        if ($request->has('phone_number')) {
            $user->phone_number = $request->input('phone_number');
        }
    
        // Lưu lại thay đổi
        $user->save();
    
        // Cập nhật vai trò cho người dùng (không cần validate)
        if ($request->has('roles')) {
            $user->syncRoles($request->input('roles'));
        } else {
            $user->syncRoles([]); // Xóa tất cả vai trò nếu không chọn vai trò nào
        }
    
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
