<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();
        $profile = $user->profile;

        return view('profile.show', compact('user', 'profile'));
    }

    public function edit(Request $request): View
    {
        $profile = $request->user()->profile;

        return view('profile.edit', compact('profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $profile = $request->user()->profile;

        $validated = $request->validate([
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $profile->update($validated);

        return redirect()->route('profile.show')->with('status', 'profile-updated');
    }
}

