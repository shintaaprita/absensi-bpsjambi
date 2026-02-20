<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * API Login
     * Returns a long-lived Sanctum token
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->first();

        // Optional logging for debugging
        // \Illuminate\Support\Facades\Log::info('Login attempt', [
        //     'username' => $request->username,
        //     'user_found' => (bool)$user,
        //     'password_match' => $user ? Hash::check($request->password, $user->password) : false
        // ]);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah.'
            ], 401);
        }

        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif.'
            ], 403);
        }

        // Create token (non-expiring by default in sanctum.php configuration)
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->fullname ?? $user->name,
                'username' => $user->username,
                'nip' => $user->nip_lama,
            ]
        ]);
    }

    /**
     * Logout (Revoke current token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Token berhasil dicabut.'
        ]);
    }

    /**
     * Get Current User Info
     */
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }
}
