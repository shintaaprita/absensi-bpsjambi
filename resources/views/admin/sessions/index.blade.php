@extends('layouts.app')
@section('title', 'Manajemen Kegiatan')

@section('content')
<div class="animate-fade">
    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1 style="font-size:2rem; font-weight:700;">Manajemen Kegiatan</h1>
            <p style="color:var(--text-muted);">Kelola sesi presensi dan pantau kehadiran</p>
        </div>
        <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem;">
            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kegiatan
        </a>
    </div>

    <div class="card" style="padding:0; overflow:hidden;">
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; text-align:left;">
                <thead>
                    <tr style="background:#1e293b; color:white;">
                        <th style="padding:0.9rem 1.25rem; font-weight:600;">Judul</th>
                        <th style="padding:0.9rem 1.25rem; font-weight:600;">Waktu</th>
                        <th style="padding:0.9rem 1.25rem; font-weight:600;">Metode</th>
                        <th style="padding:0.9rem 1.25rem; font-weight:600; text-align:center;">Hadir</th>
                        <th style="padding:0.9rem 1.25rem; font-weight:600; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $session)
                        @php
                            $now = \Carbon\Carbon::now();
                            $isActive = $session->start_time <= $now && $session->end_time >= $now;
                            $isPast   = $session->end_time < $now;
                        @endphp
                        <tr style="border-bottom:1px solid var(--border); {{ $loop->even ? 'background:#f8fafc;' : '' }}">
                            {{-- Judul --}}
                            <td style="padding:1rem 1.25rem;">
                                <div style="display:flex; align-items:center; gap:0.5rem;">
                                    @if($isActive)
                                        <span style="width:8px; height:8px; border-radius:50%; background:#22c55e; display:inline-block; flex-shrink:0;" title="Sedang berlangsung"></span>
                                    @elseif($isPast)
                                        <span style="width:8px; height:8px; border-radius:50%; background:#9ca3af; display:inline-block; flex-shrink:0;" title="Selesai"></span>
                                    @else
                                        <span style="width:8px; height:8px; border-radius:50%; background:#f59e0b; display:inline-block; flex-shrink:0;" title="Akan datang"></span>
                                    @endif
                                    <div>
                                        <div style="font-weight:600;">{{ $session->title }}</div>
                                        <div style="font-size:0.8rem; color:var(--text-muted);">{{ Str::limit($session->description, 45) }}</div>
                                    </div>
                                </div>
                            </td>
                            {{-- Waktu --}}
                            <td style="padding:1rem 1.25rem; white-space:nowrap;">
                                <div style="font-size:0.9rem; font-weight:500;">{{ $session->start_time->format('d M Y') }}</div>
                                <div style="font-size:0.8rem; color:var(--text-muted);">
                                    {{ $session->start_time->format('H:i') }} – {{ $session->end_time->format('H:i') }}
                                </div>
                            </td>
                            {{-- Metode --}}
                            <td style="padding:1rem 1.25rem;">
                                @if($session->method === 'scan_qr')
                                    <span style="background:#dcfce7; color:#166534; padding:0.25rem 0.6rem; border-radius:6px; font-size:0.78rem; font-weight:600;">🔍 Scan QR</span>
                                @elseif($session->method === 'share_qr')
                                    <span style="background:#fef3c7; color:#92400e; padding:0.25rem 0.6rem; border-radius:6px; font-size:0.78rem; font-weight:600;">📱 Share QR</span>
                                @elseif($session->method === 'location')
                                    <span style="background:#dbeafe; color:#1e40af; padding:0.25rem 0.6rem; border-radius:6px; font-size:0.78rem; font-weight:600;">📍 Lokasi</span>
                                @else
                                    <span style="background:#f3f4f6; color:#374151; padding:0.25rem 0.6rem; border-radius:6px; font-size:0.78rem;">{{ strtoupper(str_replace('_',' ',$session->method)) }}</span>
                                @endif
                            </td>
                            {{-- Hadir --}}
                            <td style="padding:1rem 1.25rem; text-align:center;">
                                <span style="font-weight:700; font-size:1.1rem; color:var(--primary);">
                                    {{ $session->attendances_count ?? $session->attendances()->count() }}
                                </span>
                            </td>
                            {{-- Aksi --}}
                            <td style="padding:1rem 1.25rem;">
                                <div style="display:flex; gap:0.5rem; justify-content:center; align-items:center;">
                                    {{-- Detail --}}
                                    <a href="{{ route('admin.sessions.show', $session) }}" title="Detail"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#ede9fe; color:#7c3aed; text-decoration:none; transition:background 0.15s;"
                                        onmouseover="this.style.background='#ddd6fe'" onmouseout="this.style.background='#ede9fe'">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.sessions.edit', $session) }}" title="Edit"
                                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#fef9c3; color:#a16207; text-decoration:none; transition:background 0.15s;"
                                        onmouseover="this.style.background='#fef08a'" onmouseout="this.style.background='#fef9c3'">
                                        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.sessions.destroy', $session) }}" method="POST"
                                        onsubmit="return confirm('Hapus kegiatan \'{{ addslashes($session->title) }}\'?')"
                                        style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" title="Hapus"
                                            style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; background:#fee2e2; color:#dc2626; border:none; cursor:pointer; transition:background 0.15s;"
                                            onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:3rem; text-align:center; color:var(--text-muted);">
                                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 1rem; display:block; opacity:0.3;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Belum ada kegiatan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="margin-top:1.5rem;">
        {{ $sessions->links() }}
    </div>
</div>
@endsection
