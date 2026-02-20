<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicProfileController extends Controller
{
    public function show($nip)
    {
        $user = \App\Models\User::where('nip_lama', $nip)
            ->orWhere('nip_baru', $nip)
            ->orWhere('username', $nip)
            ->firstOrFail();

        return view('public.profile', compact('user'));
    }
}
