@extends('layouts.app')

@section('page-title', 'Profil Saya')

@section('content')
<div class="animate-fade">
    <div class="page-header">
        <h1>Profil Saya</h1>
        <p>Kelola informasi dan foto profil Anda</p>
    </div>

    <div class="grid" style="grid-template-columns: 320px 1fr; gap: 1.75rem; align-items: start;">
        <!-- Photo Card -->
        <div class="card">
            <div class="card-body" style="text-align:center;">
                <div style="position:relative; width:120px; height:120px; margin:0 auto 1.25rem; border-radius:50%; overflow:hidden; border:4px solid white; box-shadow: 0 0 0 3px var(--primary), var(--shadow-lg);">
                    @if($user->profile_photo)
                        @if(filter_var($user->profile_photo, FILTER_VALIDATE_URL))
                            <img src="{{ $user->profile_photo }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
                        @endif
                    @else
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;font-size:2.5rem;font-weight:800;color:white;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h2 style="font-size:1.1rem; font-weight:700; margin-bottom:0.25rem;">{{ $user->name }}</h2>
                <p style="color:var(--text-muted); font-size:0.85rem; margin-bottom:0.3rem;">{{ $user->nip_lama }}</p>
                <p style="color:var(--text-muted); font-size:0.82rem; margin-bottom:1.25rem;">{{ $user->jabatan ?? 'Tidak ada jabatan' }}</p>

                <span class="badge badge-primary" style="margin-bottom:1.5rem; display:inline-block;">{{ ucfirst(session('role_name', 'Pegawai')) }}</span>

                <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                    @csrf
                    <input type="file" name="photo" id="photoInput" style="display:none;" accept="image/jpeg,image/png,image/gif" onchange="document.getElementById('photoForm').submit()">
                    <button type="button" class="btn btn-primary" style="width:100%; justify-content:center;" onclick="document.getElementById('photoInput').click()">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Ganti Foto
                    </button>
                </form>
                <p style="font-size:0.73rem; color:var(--text-muted); margin-top:0.75rem;">JPG, PNG, GIF &bull; Maks 2MB</p>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Informasi Akun
                </div>
            </div>
            <div class="card-body">
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Nama Lengkap</label>
                        <div style="padding: 0.65rem 0.875rem; background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--text-main);">{{ $user->fullname ?? $user->name }}</div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Username</label>
                        <div style="padding: 0.65rem 0.875rem; background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--text-main);">{{ $user->username }}</div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Email</label>
                        <div style="padding: 0.65rem 0.875rem; background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--text-main);">{{ $user->email ?? '-' }}</div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>NIP Lama</label>
                        <div style="padding: 0.65rem 0.875rem; background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--text-main);">{{ $user->nip_lama ?? '-' }}</div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>NIP Baru</label>
                        <div style="padding: 0.65rem 0.875rem; background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--text-main);">{{ $user->nip_baru ?? '-' }}</div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Satuan Kerja</label>
                        <div style="padding: 0.65rem 0.875rem; background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--text-main);">{{ $user->satker_kd ?? '-' }}</div>
                    </div>
                    <div class="form-group" style="margin-bottom:0; grid-column: 1 / -1;">
                        <label>Jabatan</label>
                        <div style="padding: 0.65rem 0.875rem; background: var(--bg-main); border: 1px solid var(--border); border-radius: var(--radius-sm); font-size: 0.9rem; color: var(--text-main);">{{ $user->jabatan ?? '-' }}</div>
                    </div>
                </div>

                <div style="margin-top:1.5rem; padding: 0.875rem 1rem; background: #eff6ff; border-radius: var(--radius-sm); border: 1px solid #bfdbfe; display:flex; align-items:start; gap:0.75rem;">
                    <svg width="18" height="18" fill="none" stroke="#1e40af" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:0.1rem;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <p style="font-size:0.82rem; color:#1e40af; line-height:1.5;">Data profil diambil langsung dari SSO SICAKep dan diperbarui otomatis setiap login. Hubungi admin untuk perubahan data.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media (max-width: 768px) {
        .profile-layout { grid-template-columns: 1fr !important; }
        .profile-info-grid { grid-template-columns: 1fr !important; }
    }
</style>
@endpush
@endsection
