<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        $profile = Profile::where('user_id', $user_id)->first();
        $profile->update($validated);

        return redirect()->route('profiles.show', $user_id);
    }
}
