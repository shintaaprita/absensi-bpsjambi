@extends('layouts.app')
@section('title', 'Kelola Anggota')

@section('content')
<div class="animate-fade">
    <header style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:2rem; font-weight:700;">Kelola Anggota</h1>
            <p style="color:var(--text-muted);">Manajemen data pegawai BPS Provinsi Jambi</p>
        </div>
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <a href="{{ route('admin.users.import.form') }}" class="btn" style="background:linear-gradient(135deg,#10b981,#059669); color:white; text-decoration:none; display:flex; align-items:center; gap:0.5rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import CSV
            </a>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary" style="text-decoration:none; display:flex; align-items:center; gap:0.5rem;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Anggota
            </a>
        </div>
    </header>

    @if(session('success'))
        <div class="card" style="background:#ecfdf5; border-color:#34d399; color:#065f46; margin-bottom:1.5rem; padding:1rem; display:flex; align-items:center; gap:0.5rem;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="card" style="background:#fefce8; border-color:#fbbf24; color:#78350f; margin-bottom:1.5rem; padding:1rem;">
                <strong>Peringatan Import:</strong>
                <ul style="margin-top:0.5rem; padding-left:1.2rem; font-size:0.85rem;">
                    @foreach(session('import_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
    @if(session('error'))
        <div class="card" style="background:#fef2f2; border-color:#f87171; color:#991b1b; margin-bottom:1.5rem; padding:1rem;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search & Filter --}}
    <div class="card" style="margin-bottom:1.5rem; padding:1.25rem;">
        <form method="GET" style="display:flex; gap:1rem; flex-wrap:wrap; align-items:end;">
            <div style="flex:1; min-width:200px;">
                <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, NIP, username, jabatan..."
                    style="width:100%; padding:0.6rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;">
            </div>
            <div style="min-width:150px;">
                <label style="font-size:0.85rem; font-weight:600; display:block; margin-bottom:0.4rem;">Status</label>
                <select name="status" style="width:100%; padding:0.6rem 1rem; border:1px solid var(--border); border-radius:var(--radius-sm); font-size:0.95rem;">
                    <option value="">Semua</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Reset</a>
        </form>
    </div>

    {{-- Table --}}
    <div class="card" style="padding:0; overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
                <thead>
                    <tr style="background:#1e293b; color:white;">
                        <th style="padding:0.85rem 1rem; text-align:left; font-weight:600;">No</th>
                        <th style="padding:0.85rem 1rem; text-align:left; font-weight:600;">Nama Lengkap</th>
                        <th style="padding:0.85rem 1rem; text-align:left; font-weight:600;">Username</th>
                        <th style="padding:0.85rem 1rem; text-align:left; font-weight:600;">NIP Lama</th>
                        <th style="padding:0.85rem 1rem; text-align:left; font-weight:600;">NIP Baru</th>
                        <th style="padding:0.85rem 1rem; text-align:left; font-weight:600;">Jabatan</th>
                        <th style="padding:0.85rem 1rem; text-align:center; font-weight:600;">Status</th>
                        <th style="padding:0.85rem 1rem; text-align:center; font-weight:600;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                        <tr style="border-bottom:1px solid var(--border); {{ $loop->even ? 'background:#f8fafc;' : '' }}">
                            <td style="padding:0.75rem 1rem;">{{ $users->firstItem() + $loop->index }}</td>
                            <td style="padding:0.75rem 1rem; font-weight:600;">{{ $user->fullname ?? $user->name }}</td>
                            <td style="padding:0.75rem 1rem; color:var(--text-muted);">{{ $user->username }}</td>
                            <td style="padding:0.75rem 1rem;">{{ $user->nip_lama ?? '-' }}</td>
                            <td style="padding:0.75rem 1rem;">{{ $user->nip_baru ?? '-' }}</td>
                            <td style="padding:0.75rem 1rem;">{{ $user->jabatan ?? '-' }}</td>
                            <td style="padding:0.75rem 1rem; text-align:center;">
                                @if($user->is_active)
                                    <span style="background:#dcfce7; color:#166534; padding:0.2rem 0.6rem; border-radius:100px; font-size:0.75rem; font-weight:600;">Aktif</span>
                                @else
                                    <span style="background:#fee2e2; color:#991b1b; padding:0.2rem 0.6rem; border-radius:100px; font-size:0.75rem; font-weight:600;">Non-Aktif</span>
                                @endif
                            </td>
                            <td style="padding:0.75rem 1rem; text-align:center;">
                                <div style="display:flex; gap:0.5rem; justify-content:center; align-items:center;">
                                    {{-- Detail --}}
                                    <a href="{{ route('admin.users.show', $user) }}" title="Detail"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#ede9fe; color:#7c3aed; text-decoration:none; transition:background 0.15s;"
                                        onmouseover="this.style.background='#ddd6fe'" onmouseout="this.style.background='#ede9fe'">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.users.edit', $user) }}" title="Edit"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#fef9c3; color:#a16207; text-decoration:none; transition:background 0.15s;"
                                        onmouseover="this.style.background='#fef08a'" onmouseout="this.style.background='#fef9c3'">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    {{-- Hapus --}}
                                    @if($user->username !== 'admin')
                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                            onsubmit="return confirm('Hapus anggota {{ addslashes($user->fullname ?? $user->name) }}?');"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#fee2e2; color:#dc2626; border:none; cursor:pointer; transition:background 0.15s;"
                                                onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#f3f4f6; color:#d1d5db;" title="Tidak bisa dihapus">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" style="text-align:center; padding:3rem; color:var(--text-muted);">Tidak ada data anggota.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div style="padding:1rem 1.5rem; border-top:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                <span style="font-size:0.85rem; color:var(--text-muted);">
                    Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }} anggota
                </span>
                <div style="display:flex; gap:0.4rem;">
                    {{-- Previous --}}
                    @if($users->onFirstPage())
                        <span style="padding:0.4rem 0.7rem; border:1px solid var(--border); border-radius:6px; color:var(--text-muted); font-size:0.85rem;">‹</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}" style="padding:0.4rem 0.7rem; border:1px solid var(--border); border-radius:6px; font-size:0.85rem; text-decoration:none; color:var(--text-main);">‹</a>
                    @endif
                    {{-- Next --}}
                    @if($users->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}" style="padding:0.4rem 0.7rem; border:1px solid var(--border); border-radius:6px; font-size:0.85rem; text-decoration:none; color:var(--text-main);">›</a>
                    @else
                        <span style="padding:0.4rem 0.7rem; border:1px solid var(--border); border-radius:6px; color:var(--text-muted); font-size:0.85rem;">›</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
