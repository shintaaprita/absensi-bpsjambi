<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists and is not a default/SSO URL
            if ($user->profile_photo && !filter_var($user->profile_photo, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            
            $user->update([
                'profile_photo' => $path
            ]);

            return back()->with('success', 'Foto profil berhasil diperbarui.');
        }

        return back()->with('error', 'Gagal mengunggah foto.');
    }
}
