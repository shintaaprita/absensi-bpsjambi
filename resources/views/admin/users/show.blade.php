@extends('layouts.app')
@section('title', 'Detail Anggota')

@section('content')
<div class="animate-fade">
    {{-- Header --}}
    <header style="margin-bottom:2rem;">
        <a href="{{ route('admin.users.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:0.9rem; display:inline-flex; align-items:center; gap:0.35rem; margin-bottom:1rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Anggota
        </a>
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
            <div>
                <h1 style="font-size:1.8rem; font-weight:700;">Detail Anggota</h1>
                <p style="color:var(--text-muted);">{{ $user->fullname ?? $user->name }}</p>
            </div>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary" style="text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit Anggota
            </a>
        </div>
    </header>

    <div class="grid" style="grid-template-columns:1fr 1.5fr; gap:2rem;">
        {{-- Left: Profile Info --}}
        <div>
            {{-- Profile Card --}}
            <div class="card" style="padding:2rem; text-align:center; margin-bottom:1.5rem; background:linear-gradient(135deg,#667eea,#764ba2); color:white; border:none;">
                <div style="width:80px; height:80px; background:rgba(255,255,255,0.2); border-radius:50%; margin:0 auto 1rem; display:flex; align-items:center; justify-content:center; font-size:2rem; font-weight:700;">
                    {{ strtoupper(substr($user->fullname ?? $user->name, 0, 1)) }}
                </div>
                <h2 style="font-size:1.25rem; font-weight:700; margin-bottom:0.25rem;">{{ $user->fullname ?? $user->name }}</h2>
                <p style="font-size:0.9rem; opacity:0.85;">{{ $user->jabatan ?? 'Jabatan belum diatur' }}</p>
                <div style="margin-top:1rem;">
                    @if($user->is_active)
                        <span style="background:rgba(255,255,255,0.25); padding:0.3rem 0.8rem; border-radius:100px; font-size:0.8rem; font-weight:600;">✅ Aktif</span>
                    @else
                        <span style="background:rgba(0,0,0,0.2); padding:0.3rem 0.8rem; border-radius:100px; font-size:0.8rem; font-weight:600;">❌ Non-Aktif</span>
                    @endif
                </div>
            </div>

            {{-- Data Pegawai --}}
            <div class="card" style="padding:1.5rem; margin-bottom:1.5rem;">
                <h3 style="font-size:1rem; font-weight:600; margin-bottom:1.25rem; display:flex; align-items:center; gap:0.5rem; border-bottom:1px solid var(--border); padding-bottom:0.75rem;">
                    <svg width="18" height="18" fill="none" stroke="var(--primary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Data Pegawai
                </h3>
                <table style="width:100%; font-size:0.9rem; border-collapse:collapse;">
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:0.6rem 0; color:var(--text-muted); width:45%;">Username</td>
                        <td style="padding:0.6rem 0; font-weight:500;">{{ $user->username }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:0.6rem 0; color:var(--text-muted);">Email</td>
                        <td style="padding:0.6rem 0;">{{ $user->email ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:0.6rem 0; color:var(--text-muted);">NIP Lama</td>
                        <td style="padding:0.6rem 0;">{{ $user->nip_lama ?? '-' }}</td>
                    </tr>
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:0.6rem 0; color:var(--text-muted);">NIP Baru</td>
                        <td style="padding:0.6rem 0; font-size:0.82rem;">{{ $user->nip_baru ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:0.6rem 0; color:var(--text-muted);">Terdaftar</td>
                        <td style="padding:0.6rem 0;">{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>

            {{-- Statistik Presensi --}}
            <div class="grid" style="grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="card" style="padding:1.25rem; text-align:center; background:linear-gradient(135deg,#4facfe,#00f2fe); color:white; border:none;">
                    <p style="font-size:0.8rem; opacity:0.9; margin-bottom:0.25rem;">Total Presensi</p>
                    <h3 style="font-size:2rem; font-weight:700;">{{ $totalAttendances }}</h3>
                    <p style="font-size:0.75rem; opacity:0.8;">semua waktu</p>
                </div>
                <div class="card" style="padding:1.25rem; text-align:center; background:linear-gradient(135deg,#43e97b,#38f9d7); color:white; border:none;">
                    <p style="font-size:0.8rem; opacity:0.9; margin-bottom:0.25rem;">Bulan Ini</p>
                    <h3 style="font-size:2rem; font-weight:700;">{{ $monthlyAttendances }}</h3>
                    <p style="font-size:0.75rem; opacity:0.8;">{{ now()->translatedFormat('F Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Right: Riwayat Presensi --}}
        <div>
            <div class="card" style="padding:0; overflow:hidden;">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:0.5rem;">
                    <svg width="20" height="20" fill="none" stroke="var(--secondary)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <h3 style="font-size:1rem; font-weight:600;">Riwayat Presensi Terbaru</h3>
                </div>

                @if($recentAttendances->isEmpty())
                    <div style="text-align:center; padding:3rem; color:var(--text-muted);">
                        <svg width="56" height="56" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 1rem; display:block; opacity:0.3;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p style="font-weight:500;">Belum ada riwayat presensi</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse; font-size:0.88rem;">
                            <thead>
                                <tr style="background:#f8fafc; border-bottom:1px solid var(--border);">
                                    <th style="padding:0.75rem 1rem; text-align:left; font-weight:600; color:var(--text-muted);">Kegiatan</th>
                                    <th style="padding:0.75rem 1rem; text-align:left; font-weight:600; color:var(--text-muted);">Waktu</th>
                                    <th style="padding:0.75rem 1rem; text-align:left; font-weight:600; color:var(--text-muted);">Metode</th>
                                    <th style="padding:0.75rem 1rem; text-align:center; font-weight:600; color:var(--text-muted);">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAttendances as $attendance)
                                <tr style="border-bottom:1px solid var(--border); {{ $loop->even ? 'background:#f8fafc;' : '' }}">
                                    <td style="padding:0.7rem 1rem; font-weight:500;">
                                        {{ $attendance->session->title ?? '-' }}
                                    </td>
                                    <td style="padding:0.7rem 1rem; color:var(--text-muted); font-size:0.82rem;">
                                        {{ $attendance->captured_at->format('d M Y') }}<br>
                                        <strong>{{ $attendance->captured_at->format('H:i:s') }}</strong>
                                    </td>
                                    <td style="padding:0.7rem 1rem;">
                                        @php $method = $attendance->method_used ?? $attendance->session->method ?? '-'; @endphp
                                        @if($method === 'scan_qr')
                                            <span style="background:#dcfce7; color:#166534; padding:0.2rem 0.5rem; border-radius:4px; font-size:0.75rem;">🔍 Scan QR</span>
                                        @elseif($method === 'share_qr')
                                            <span style="background:#fef3c7; color:#92400e; padding:0.2rem 0.5rem; border-radius:4px; font-size:0.75rem;">📱 Share QR</span>
                                        @elseif($method === 'location')
                                            <span style="background:#dbeafe; color:#1e40af; padding:0.2rem 0.5rem; border-radius:4px; font-size:0.75rem;">📍 Lokasi</span>
                                        @else
                                            <span style="background:#f3f4f6; color:#374151; padding:0.2rem 0.5rem; border-radius:4px; font-size:0.75rem;">{{ $method }}</span>
                                        @endif
                                    </td>
                                    <td style="padding:0.7rem 1rem; text-align:center;">
                                        <span style="background:#dcfce7; color:#166534; padding:0.2rem 0.6rem; border-radius:100px; font-size:0.75rem; font-weight:600;">
                                            {{ $attendance->status ?? 'Hadir' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($totalAttendances > 10)
                        <div style="padding:0.75rem 1rem; border-top:1px solid var(--border); text-align:center; font-size:0.85rem; color:var(--text-muted);">
                            Menampilkan 10 presensi terbaru dari total {{ $totalAttendances }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
