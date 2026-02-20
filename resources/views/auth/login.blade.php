@extends('layouts.app', ['title' => 'Login - Presensi BPS'])

@section('content')
<div style="display: flex; justify-content: center; align-items: center; min-height: 70vh;">
    <div class="card animate-fade" style="width: 100%; max-width: 400px; padding: 2.5rem;">
        <div class="text-center" style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.8rem; font-weight: 700; color: var(--primary);">Selamat Datang</h2>
            <p style="color: var(--text-muted);">Silakan login untuk mencatat kehadiran</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Masukkan username admin" required autofocus>
                @error('username')
                    <p style="color: var(--danger); font-size: 0.8rem; margin-top: 0.25rem;">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem; padding: 0.8rem;">
                Login
            </button>
        </form>

        <div style="margin: 2rem 0; display: flex; align-items: center; gap: 1rem;">
            <div style="flex: 1; height: 1px; background: var(--border);"></div>
            <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 500;">ATAU</span>
            <div style="flex: 1; height: 1px; background: var(--border);"></div>
        </div>

        <a href="{{ route('auth.sso') }}" class="btn btn-outline" style="width: 100%; justify-content: center; border-color: var(--primary); color: var(--primary);">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 0.5rem;">
                <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 22 12 22ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM11 16H13V12H16L12 7L8 12H11V16Z" fill="currentColor"/>
            </svg>
            Login SSO SiCakep
        </a>


    </div>
</div>
@endsection
