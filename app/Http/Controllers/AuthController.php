<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if (!$user->is_active) {
                return back()->withErrors(['username' => 'Akun Anda tidak aktif.'])->onlyInput('username');
            }

            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function ssoLogin()
    {
        $redirectUrl = urlencode(route('auth.ssoCallback'));
        return redirect()->to('https://bpsjambi.id/sso/public/login?redirect_url=' . $redirectUrl);
    }

    public function ssoCallback(Request $request)
    {
        $token = $request->get('token');
        $validationResult = $this->validateToken($token);

        if ($validationResult && isset($validationResult['status']) && $validationResult['status'] == 200) {
            $this->loginUserSSO($validationResult['user']);
            return redirect()->to('/dashboard');
        } else {
            return redirect()->route('login')->with('error', 'SSO Login Failed');
        }
    }

    private function validateToken($token)
    {
        $secret_key = 'rahasianegara!';
        // Menggunakan domain .id sesuai instruksi dan menambahkan index.php jika diperlukan
        $url = 'https://bpsjambi.id/sso/public/index.php/auth/validate-token';

        try {
            $response = Http::withoutVerifying()->asForm()->post($url, [
                'token' => $token,
                'secret_key' => $secret_key
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    private function loginUserSSO($userData)
    {
        $user = User::where('nip_lama', $userData['nip_lama_user'])->first();

        if (!$user) {
            // Default role ID 4 in CI code was "Employee"
            // In my seeder it might be different, but I'll use Role Name
            $employeeRole = Role::where('name', 'Employee')->first();

            $user = User::create([
                'name' => $userData['fullname'], // Tambahkan ini untuk memperbaiki error 'name' no default value
                'username' => $userData['username'],
                'email' => $userData['email'],
                'nip_lama' => $userData['nip_lama_user'],
                'fullname' => $userData['fullname'],
                'satker_kd' => $userData['satker_kd'],
                'is_active' => true,
                'password' => Hash::make(str()->random(24)), // Random password for SSO users
            ]);

            if ($employeeRole) {
                $user->roles()->attach($employeeRole);
            }
        }

        // Set roles in session for switchRole logic (as per CI snippet)
        $roles = $user->roles->pluck('id')->toArray();
        $roleNames = $user->roles->pluck('name', 'id')->toArray();

        Auth::login($user);

        Session::put([
            'roles' => $roles,
            'role_names' => $roleNames,
            'role' => $roles[0] ?? null,
            'role_name' => $roleNames[$roles[0]] ?? null,
            'nip_lama' => $userData['nip_lama_user'],
            'fullname' => $userData['fullname'],
            'satker_kd' => $userData['satker_kd'],
        ]);
    }

    public function switchRole($roleId)
    {
        $roles = Session::get('roles', []);
        $roleNames = Session::get('role_names', []);

        if (in_array($roleId, $roles)) {
            Session::put('role', $roleId);
            Session::put('role_name', $roleNames[$roleId]);
        }

        return redirect()->to('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $ssoLogoutUrl = 'https://bpsjambi.id/sso/public/logout?redirect_url=' . urlencode(url('/'));
        return redirect()->to($ssoLogoutUrl);
    }
}
