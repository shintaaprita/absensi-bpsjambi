@extends('layouts.app', ['title' => 'Login - Presensi BPS'])

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div class="card animate-fade" style="width: 100%; max-width: 420px; border-radius: 20px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); overflow: hidden; background: white;">
        
        <!-- Header Section -->
        <div style="padding: 3rem 2.5rem 2rem; text-align: center;">
            <div style="width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <img src="{{ asset('logobps.png') }}" alt="BPS Logo" style="width: 50px; height: auto;">
            </div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #1e293b; letter-spacing: -0.025em; margin-bottom: 0.5rem;">Presensi BPS Jambi</h1>
            <p style="color: #64748b; font-size: 0.95rem;">Selamat datang, silakan masuk</p>
        </div>

        <!-- SSO Button (Primary) -->
        <div style="padding: 0 2.5rem;">
            <a href="{{ route('auth.sso') }}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem; border-radius: 12px; font-weight: 600; font-size: 1rem; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); transition: all 0.2s;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 0.75rem;">
                    <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 22 12 22ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20ZM11 16H13V12H16L12 7L8 12H11V16Z" fill="currentColor"/>
                </svg>
                Melalui Akun Sicakep
            </a>
        </div>

        <!-- Divider -->
        <div style="margin: 2rem 2.5rem; display: flex; align-items: center; gap: 1rem;">
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
            <span style="font-size: 0.75rem; color: #94a3b8; font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase;">ATAU</span>
            <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
        </div>

        <!-- Local Login Form -->
        <form action="{{ route('login') }}" method="POST" style="padding: 0 2.5rem 3rem;">
            @csrf
            <style>
                .input-group { position: relative; margin-bottom: 1.25rem; }
                .input-field { 
                    width: 100%; 
                    padding: 0.875rem 1rem 0.875rem 2.75rem; 
                    border: 1px solid #e2e8f0; 
                    border-radius: 12px; 
                    outline: none; 
                    transition: all 0.2s;
                    font-size: 0.95rem;
                    background: #f8fafc;
                    color: #334155;
                }
                .input-field:focus { 
                    border-color: #3b82f6; 
                    background: white; 
                    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); 
                }
                .input-icon {
                    position: absolute;
                    left: 1rem;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #94a3b8;
                    pointer-events: none;
                }
                ::placeholder { color: #cbd5e1; }
            </style>

            <div class="input-group">
                <svg class="input-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <input type="text" name="username" class="input-field" placeholder="Username / NIP" required>
            </div>
            @error('username')
                <p style="color: #ef4444; font-size: 0.8rem; margin: -0.75rem 0 1rem;">{{ $message }}</p>
            @enderror

            <div class="input-group">
                <svg class="input-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                <input type="password" name="password" class="input-field" placeholder="Kata Sandi" required>
            </div>

            <button type="submit" class="btn btn-outline" style="width: 100%; justify-content: center; padding: 0.875rem; border-radius: 12px; font-weight: 500; border: 1px solid #e2e8f0; color: #64748b;">
                Masuk
            </button>
        </form>
    </div>
    
    <div style="position: absolute; bottom: 2rem; color: #94a3b8; font-size: 0.85rem;">
        &copy; 2026 BPS Provinsi Jambi
    </div>
</div>
@endsection
