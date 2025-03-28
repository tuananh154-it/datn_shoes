<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ProfileController extends Controller
{

    // public function index()
    // {
    //     // Logic cho trang profile
    //     return view('profiles.show');
    // }

    public function show(User $user)
    {
        return view('profiles.show', compact('user'));
    }
    public function index()
    {
        $users = User::all(); // Lấy tất cả các tài khoản
        return view('profiles.index', compact('users'));
    }




    public function edit($user_id)
    {
        $user = User::findOrFail($user_id);  // Lấy thông tin người dùng
        return view('profiles.edit', compact('user'));  // Trả về view chỉnh sửa
    }


    // public function update(Request $request, $user_id)
    // {
    //     $validated = $request->validate([
    //         'phone' => 'nullable|string',
    //         'address' => 'nullable|string',
    //         'avatar' => 'nullable|string',
    //     ]);

    //     $profile = Profile::where('user_id', $user_id)->first();
    //     $profile->update($validated);

    //     return redirect()->route('profiles.show', $user_id);
    // }
    public function update(Request $request, User $user)
    {
        // Validate dữ liệu đầu vào
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // 'email' => 'required|email|unique:users,email,' . $user->id,
            'gender' => 'required|string|in:male,female,other',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:15',
            // 'password' => 'nullable|string|min:6',
        ]);

        // Cập nhật thông tin người dùng
        $user->name = $validated['name'];
        // $user->email = $validated['email'];
        $user->gender = $validated['gender'];
        $user->date_of_birth = $validated['date_of_birth'];
        $user->address = $validated['address'];
        $user->phone_number = $validated['phone_number'];

        // Cập nhật mật khẩu nếu có
        // if ($request->filled('password')) {
        //     $user->password = bcrypt($request->password);
        // }

        // Lưu lại thông tin đã cập nhật
        $user->save();

        return redirect()->route('profiles.show', $user->id)->with('success', 'Thông tin người dùng đã được cập nhật!');
    }

    public function create()
    {
        return view('profiles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        // Tạo profile cho người dùng đã đăng nhập
        $profile = new Profile($validated);
        $profile->user_id = Auth::id();  // Gắn user_id cho profile
        $profile->save();

        return redirect()->route('profiles.show', Auth::id())->with('success', 'Profile created successfully!');
    }
}