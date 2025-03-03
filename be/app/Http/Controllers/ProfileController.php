<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($user_id)
    {
        $profile = Profile::where('user_id', $user_id)->first();
        return view('profiles.show', compact('profile'));
    }

    public function edit($user_id)
    {
        $profile = Profile::where('user_id', $user_id)->first();
        return view('profiles.edit', compact('profile'));
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
