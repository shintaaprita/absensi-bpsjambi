@extends('layouts.app')

@section('page-title', 'Dashboard Admin')

@section('content')
<div class="animate-fade">
    <!-- Page Header -->
    <div class="page-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <h1>Dashboard Admin</h1>
            <p>Ringkasan aktivitas presensi BPS Jambi &bull; {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
        <a href="{{ route('admin.sessions.create') }}" class="btn btn-primary">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Kegiatan Baru
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, #6366f1, #4f46e5); color:white; border-radius:var(--radius-md); padding:1.25rem; box-shadow: 0 4px 15px rgba(99,102,241,0.35);">
            <p style="font-size:0.75rem; opacity:0.8; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:0.4rem;">Total Pegawai</p>
            <h2 style="font-size:2rem; font-weight:800; line-height:1; margin-bottom:0.25rem;">{{ $totalUsers }}</h2>
            <p style="font-size:0.75rem; opacity:0.7;">terdaftar</p>
        </div>
        <div style="background: linear-gradient(135deg, #f59e0b, #d97706); color:white; border-radius:var(--radius-md); padding:1.25rem; box-shadow: 0 4px 15px rgba(245,158,11,0.35);">
            <p style="font-size:0.75rem; opacity:0.8; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:0.4rem;">Total Kegiatan</p>
            <h2 style="font-size:2rem; font-weight:800; line-height:1; margin-bottom:0.25rem;">{{ $totalSessions }}</h2>
            <p style="font-size:0.75rem; opacity:0.7;">{{ $activeSessions->count() }} aktif sekarang</p>
        </div>
        <div style="background: linear-gradient(135deg, #10b981, #059669); color:white; border-radius:var(--radius-md); padding:1.25rem; box-shadow: 0 4px 15px rgba(16,185,129,0.35);">
            <p style="font-size:0.75rem; opacity:0.8; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:0.4rem;">Presensi Hari Ini</p>
            <h2 style="font-size:2rem; font-weight:800; line-height:1; margin-bottom:0.25rem;">{{ $todayAttendances }}</h2>
            <p style="font-size:0.75rem; opacity:0.7;">tercatat</p>
        </div>
        <div style="background: linear-gradient(135deg, #8b5cf6, #6d28d9); color:white; border-radius:var(--radius-md); padding:1.25rem; box-shadow: 0 4px 15px rgba(139,92,246,0.35);">
            <p style="font-size:0.75rem; opacity:0.8; letter-spacing:0.05em; text-transform:uppercase; margin-bottom:0.4rem;">Presensi Bulan Ini</p>
            <h2 style="font-size:2rem; font-weight:800; line-height:1; margin-bottom:0.25rem;">{{ $monthlyAttendances }}</h2>
            <p style="font-size:0.75rem; opacity:0.7;">total presensi</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card" style="margin-bottom: 1.75rem;">
        <div class="card-header">
            <div class="card-title">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Aksi Cepat
            </div>
        </div>
        <div class="card-body">
            <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(130px, 1fr)); gap: 1rem;">
                @php
                $quickLinks = [
                    ['href' => route('admin.scan-qr'), 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z', 'label' => 'Scan QR', 'sub' => 'Scan QR pegawai', 'gradient' => 'linear-gradient(135deg,#6366f1,#4f46e5)'],
                    ['href' => route('admin.show-qr'), 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0zm-9 0a9 9 0 1118 0A9 9 0 016 12z', 'label' => 'Show QR', 'sub' => 'Tampilkan QR', 'gradient' => 'linear-gradient(135deg,#ec4899,#be185d)'],
                    ['href' => route('admin.sessions.create'), 'icon' => 'M12 4v16m8-8H4', 'label' => 'Buat Kegiatan', 'sub' => 'Kegiatan baru', 'gradient' => 'linear-gradient(135deg,#10b981,#059669)'],
                    ['href' => route('admin.users.index'), 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Anggota', 'sub' => 'Data pegawai', 'gradient' => 'linear-gradient(135deg,#f59e0b,#d97706)'],
                    ['href' => route('admin.reports'), 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'label' => 'Laporan', 'sub' => 'Rekap data', 'gradient' => 'linear-gradient(135deg,#8b5cf6,#6d28d9)'],
                ];
                @endphp

                @foreach($quickLinks as $ql)
                <a href="{{ $ql['href'] }}" style="display:flex;flex-direction:column;align-items:center;text-align:center;padding:1.125rem;border:1px solid var(--border);border-radius:var(--radius-md);text-decoration:none;color:var(--text-main);transition:all 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 20px rgba(0,0,0,0.1)';this.style.borderColor='#c7d2fe';" onmouseout="this.style.transform='';this.style.boxShadow='';this.style.borderColor='var(--border)';">
                    <div style="width:48px;height:48px;border-radius:12px;background:{{ $ql['gradient'] }};display:flex;align-items:center;justify-content:center;margin-bottom:0.75rem;">
                        <svg width="22" height="22" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $ql['icon'] }}"/></svg>
                    </div>
                    <span style="font-weight:600;font-size:0.85rem;margin-bottom:0.2rem;">{{ $ql['label'] }}</span>
                    <span style="font-size:0.72rem;color:var(--text-muted);">{{ $ql['sub'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Bottom Grid -->
    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- Active Sessions -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div style="width:8px;height:8px;background:#22c55e;border-radius:50%;animation:pulse 2s infinite;"></div>
                    Kegiatan Aktif
                </div>
                <span class="badge badge-success">{{ $activeSessions->count() }}</span>
            </div>
            @if($activeSessions->isEmpty())
                <div style="padding:2rem; text-align:center; color:var(--text-muted);">
                    <p style="font-size:0.875rem;">Tidak ada kegiatan aktif saat ini</p>
                </div>
            @else
                @foreach($activeSessions as $session)
                    <div style="padding:0.875rem 1.25rem; border-bottom:1px solid var(--border);">
                        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
                            <div>
                                <p style="font-weight:600; font-size:0.875rem;">{{ $session->title }}</p>
                                <p style="font-size:0.75rem; color:var(--text-muted);">{{ $session->start_time->format('H:i') }} – {{ $session->end_time->format('H:i') }}</p>
                            </div>
                            <div style="display:flex; align-items:center; gap:0.5rem;">
                                <span class="badge badge-primary">{{ $session->attendances_count ?? 0 }} hadir</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <!-- Recent Attendances -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Presensi Terbaru
                </div>
                <a href="{{ route('admin.reports') }}" style="font-size:0.8rem; color:var(--primary); text-decoration:none; font-weight:500;">Lihat Semua →</a>
            </div>
            @if($recentAttendances->isEmpty())
                <div style="padding:2rem; text-align:center; color:var(--text-muted);">
                    <p style="font-size:0.875rem;">Belum ada presensi hari ini</p>
                </div>
            @else
                <div style="max-height:340px; overflow-y:auto;">
                    @foreach($recentAttendances as $a)
                        <div style="padding:0.75rem 1.25rem; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:0.75rem;">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#a855f7);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;flex-shrink:0;">
                                {{ strtoupper(substr($a->user->name ?? 'U', 0, 1)) }}
                            </div>
                            <div style="flex:1;min-width:0;">
                                <p style="font-weight:600; font-size:0.825rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $a->user->fullname ?? $a->user->name }}</p>
                                <p style="font-size:0.72rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $a->session->title }}</p>
                            </div>
                            <p style="font-size:0.72rem; color:var(--text-muted); flex-shrink:0;">{{ $a->captured_at->format('H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }

    @media (max-width: 768px) {
        .grid[style*="grid-template-columns: 1fr 1fr"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush
@endsection
