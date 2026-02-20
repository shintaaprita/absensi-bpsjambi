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
            'username' => ['required'], // Can be username or NIP Lama
            'password' => ['required'],
        ]);

        // Prioritize finding by username, then by nip_lama
        $user = User::where('username', $credentials['username'])
            ->orWhere('nip_lama', $credentials['username'])
            ->first();

        // If user is Employee (Pegawai), verify username IS NIP Lama
        if ($user && $user->roles()->whereRaw('LOWER(name) = ?', ['pegawai'])->exists()) {
             if ($user->nip_lama !== $credentials['username'] && $user->username !== $credentials['username']) {
                  return back()->withErrors(['username' => 'Pegawai harus login menggunakan Username atau NIP.'])->onlyInput('username');
             }
        }

        if ($user && Hash::check($credentials['password'], $user->password)) {
            if (!$user->is_active) {
                return back()->withErrors(['username' => 'Akun Anda tidak aktif.'])->onlyInput('username');
            }

            Auth::login($user);
            $this->setSessionRoles($user);
            
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => 'Identitas atau kata sandi salah.',
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
            $ssoUser = $validationResult['user'];
            $user = $this->loginUserSSO($ssoUser);

            // Authenticate the user in Laravel
            Auth::login($user);
            // Regenerate FIRST, then set session so data is not wiped
            $request->session()->regenerate();

            // Now set session roles (after regenerate so they persist)
            $this->setSessionRoles(
                $user,
                $ssoUser['nip_lama_user'] ?? $user->nip_lama,
                $ssoUser['fullname'] ?? $user->fullname,
                $ssoUser['satker_kd'] ?? $user->satker_kd
            );

            \Illuminate\Support\Facades\Log::info('SSO Login Success', ['user' => $user->username]);
            return redirect()->to('/dashboard');
        } else {
            \Illuminate\Support\Facades\Log::error('SSO Login Failed', [
                'token' => $token,
                'validationResult' => $validationResult
            ]);
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
        $user = User::where('nip_lama', $userData['nip_lama_user'])
            ->orWhere('username', $userData['username'])
            ->first();

        // Check for 'pegawai' and 'magang' roles
        $pegawaiRole = Role::whereRaw('LOWER(name) = ?', ['pegawai'])->first();
        $magangRole  = Role::whereRaw('LOWER(name) = ?', ['magang'])->first();

        // Determine base role from NIP presence
        $hasNip = !empty($userData['nip_lama_user'])
            && $userData['nip_lama_user'] !== '-'
            && is_numeric($userData['nip_lama_user']);

        $baseRole = $hasNip ? $pegawaiRole : $magangRole;

        if (!$user) {
            // Brand new user — create with base role
            $user = User::create([
                'name'          => $userData['fullname'],
                'username'      => $userData['username'],
                'fullname'      => $userData['fullname'],
                'email'         => $userData['email'],
                'nip_lama'      => $userData['nip_lama_user'],
                'nip_baru'      => $userData['nip_baru'] ?? null,
                'satker_kd'     => $userData['satker_kd'],
                'jabatan'       => $userData['fungsional'] ?? $userData['jabatan'] ?? null,
                'is_active'     => $userData['is_active'] === 'Y',
                'profile_photo' => $userData['image'] ?? null,
                'roles_json'    => null, // roles_json is set by system, not SSO
                'password'      => Hash::make(str()->random(24)),
            ]);

            // Attach base role for new users
            if ($baseRole) {
                $user->roles()->attach($baseRole->id);
            }
        } else {
            // Update existing user with latest SSO profile data only
            // Do NOT overwrite roles_json — it is managed by the system
            $user->update([
                'name'          => $userData['fullname'],
                'fullname'      => $userData['fullname'],
                'email'         => $userData['email'],
                'nip_baru'      => $userData['nip_baru'] ?? $user->nip_baru,
                'satker_kd'     => $userData['satker_kd'],
                'jabatan'       => $userData['fungsional'] ?? $userData['jabatan'] ?? $user->jabatan,
                'is_active'     => $userData['is_active'] === 'Y',
                'profile_photo' => $userData['image'] ?? $user->profile_photo,
            ]);

            // If user somehow has NO roles, assign base role as fallback
            $user->load('roles');
            if ($user->roles->isEmpty() && $baseRole) {
                $user->roles()->attach($baseRole->id);
            }
            // NOTE: We intentionally do NOT overwrite manually-assigned roles
            // Admins can use `php artisan user:assign-roles` to manage roles
        }

        // Return user (session will be set in ssoCallback after regenerate)
        return $user;
    }

    private function setSessionRoles(User $user, $nip = null, $fullname = null, $satker = null)
    {
        $user->load('roles');
        $roles = $user->roles->pluck('id')->toArray();
        $roleNames = $user->roles->pluck('name', 'id')->toArray();
        $firstRoleId = $roles[0] ?? null;

        Session::put([
            'roles' => $roles,
            'role_names' => $roleNames,
            'role' => $firstRoleId,
            'role_name' => $firstRoleId ? ($roleNames[$firstRoleId] ?? null) : null,
            'nip_lama' => $nip ?? $user->nip_lama,
            'fullname' => $fullname ?? $user->fullname,
            'satker_kd' => $satker ?? $user->satker_kd,
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
