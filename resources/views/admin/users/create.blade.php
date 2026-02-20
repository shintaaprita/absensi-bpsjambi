@extends('layouts.app')
@section('title', 'Tambah Anggota')

@section('content')
<div class="animate-fade" style="max-width:700px; margin:0 auto;">
    <header style="margin-bottom:2rem;">
        <a href="{{ route('admin.users.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:0.9rem; display:flex; align-items:center; gap:0.35rem; margin-bottom:1rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Anggota
        </a>
        <h1 style="font-size:1.8rem; font-weight:700;">Tambah Anggota Baru</h1>
        <p style="color:var(--text-muted);">Isi data lengkap pegawai di bawah ini</p>
    </header>

    @if($errors->any())
        <div class="card" style="background:#fef2f2; border-color:#f87171; color:#991b1b; margin-bottom:1.5rem; padding:1rem;">
            <strong>Terdapat kesalahan:</strong>
            <ul style="margin-top:0.5rem; padding-left:1.2rem; font-size:0.9rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="card" style="padding:2rem; margin-bottom:1.5rem;">
            <h3 style="font-size:1.1rem; font-weight:600; margin-bottom:1.5rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border);">Data Pribadi</h3>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                <div style="grid-column:1/-1;">
                    <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">Nama Lengkap <span style="color:red;">*</span></label>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" placeholder="Contoh: Budi Santoso, S.ST."
                        style="width:100%; padding:0.7rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;" required>
                </div>
                <div>
                    <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">NIP Lama</label>
                    <input type="text" name="nip_lama" value="{{ old('nip_lama') }}" placeholder="Contoh: 340012345"
                        style="width:100%; padding:0.7rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;">
                </div>
                <div>
                    <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">NIP Baru (18 digit)</label>
                    <input type="text" name="nip_baru" value="{{ old('nip_baru') }}" placeholder="Contoh: 199001012020011001"
                        style="width:100%; padding:0.7rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;">
                </div>
                <div style="grid-column:1/-1;">
                    <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}" placeholder="Contoh: Statistisi Ahli Muda"
                        style="width:100%; padding:0.7rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;">
                </div>
            </div>
        </div>

        <div class="card" style="padding:2rem; margin-bottom:1.5rem;">
            <h3 style="font-size:1.1rem; font-weight:600; margin-bottom:1.5rem; padding-bottom:0.75rem; border-bottom:1px solid var(--border);">Akun Login</h3>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
                <div>
                    <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">Username <span style="color:red;">*</span></label>
                    <input type="text" name="username" value="{{ old('username') }}" placeholder="Contoh: budi.santoso"
                        style="width:100%; padding:0.7rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;" required>
                </div>
                <div>
                    <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Otomatis jika kosong"
                        style="width:100%; padding:0.7rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;">
                </div>
                <div>
                    <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">Password <span style="color:red;">*</span></label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter"
                        style="width:100%; padding:0.7rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;" required>
                </div>
                <div>
                    <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">Konfirmasi Password <span style="color:red;">*</span></label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                        style="width:100%; padding:0.7rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;" required>
                </div>
                <div style="grid-column:1/-1; display:flex; align-items:center; gap:0.75rem;">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}
                        style="width:18px; height:18px; cursor:pointer; accent-color:var(--primary);">
                    <label for="is_active" style="font-size:0.9rem; cursor:pointer;">Akun Aktif (dapat login)</label>
                </div>
            </div>
        </div>

        <div style="display:flex; gap:1rem; justify-content:flex-end;">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="text-decoration:none;">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Anggota</button>
        </div>
    </form>
</div>
@endsection
