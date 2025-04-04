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
    //     return view('profiles.index');
    // }

    public function show()
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();
        return view('profiles.show', compact('user'));
    }

    public function edit()
    {
        // Lấy thông tin người dùng hiện tại
        $user = Auth::user();
        return view('profiles.edit', compact('user'));
    }

    public function update(Request $request, $user_id)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'gender' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
        ]);

        $user = User::findOrFail($user_id);
        $user->update($validated);


        return redirect()->route('profiles.show', $user->id)->with('success', 'Thông tin người dùng đã được cập nhật.');
    }
}
