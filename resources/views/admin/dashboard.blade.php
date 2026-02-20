@extends('layouts.app')

@section('content')
<div class="animate-fade">
    <header style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2rem; font-weight: 700;">Dashboard Admin</h1>
        <p style="color: var(--text-muted);">Selamat datang, {{ Auth::user()->fullname ?? Auth::user()->name }}</p>
    </header>

    <!-- Statistics Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Total Pegawai -->
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Pegawai</p>
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $totalUsers }}</h2>
                    <p style="font-size: 0.8rem; opacity: 0.8;">terdaftar</p>
                </div>
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Kegiatan -->
        <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Total Kegiatan</p>
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $totalSessions }}</h2>
                    <p style="font-size: 0.8rem; opacity: 0.8;">{{ $activeSessions->count() }} aktif sekarang</p>
                </div>
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Presensi Hari Ini -->
        <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Presensi Hari Ini</p>
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $todayAttendances }}</h2>
                    <p style="font-size: 0.8rem; opacity: 0.8;">tercatat</p>
                </div>
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Presensi Bulan Ini -->
        <div class="card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Presensi Bulan Ini</p>
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $monthlyAttendances }}</h2>
                    <p style="font-size: 0.8rem; opacity: 0.8;">total presensi</p>
                </div>
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions - dipindah ke atas agar langsung terlihat -->
    <div style="margin-bottom: 2.5rem;">
        <h3 style="margin-bottom: 1.25rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
            <svg style="width: 22px; height: 22px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Aksi Cepat
        </h3>
        <div class="grid" style="grid-template-columns: repeat(4, 1fr); gap: 1rem;">
            <a href="{{ route('admin.scan-qr') }}" class="card" style="text-decoration: none; text-align: center; padding: 1.5rem; transition: all 0.2s; cursor: pointer; border: 2px solid var(--border);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-4px)';" onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateY(0)';">
                <div style="width: 52px; height: 52px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 26px; height: 26px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                    </svg>
                </div>
                <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">Scan QR</h4>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Scan QR pegawai</p>
            </a>

            <a href="{{ route('admin.show-qr') }}" class="card" style="text-decoration: none; text-align: center; padding: 1.5rem; transition: all 0.2s; cursor: pointer; border: 2px solid var(--border);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-4px)';" onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateY(0)';">
                <div style="width: 52px; height: 52px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 26px; height: 26px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </div>
                <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">Show QR</h4>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Tampilkan QR kegiatan</p>
            </a>

            <a href="{{ route('admin.sessions.create') }}" class="card" style="text-decoration: none; text-align: center; padding: 1.5rem; transition: all 0.2s; cursor: pointer; border: 2px solid var(--border);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-4px)';" onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateY(0)';">
                <div style="width: 52px; height: 52px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 26px; height: 26px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">Buat Kegiatan</h4>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Kegiatan baru</p>
            </a>

            <a href="{{ route('admin.users.index') }}" class="card" style="text-decoration: none; text-align: center; padding: 1.5rem; transition: all 0.2s; cursor: pointer; border: 2px solid var(--border);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-4px)';" onmouseout="this.style.borderColor='var(--border)'; this.style.transform='translateY(0)';">
                <div style="width: 52px; height: 52px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); border-radius: 50%; margin: 0 auto 1rem; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 26px; height: 26px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h4 style="font-size: 0.95rem; font-weight: 600; color: var(--text); margin-bottom: 0.25rem;">Kelola Anggota</h4>
                <p style="font-size: 0.8rem; color: var(--text-muted);">Data pegawai</p>
            </a>
        </div>
    </div>

    <div class="grid" style="grid-template-columns: 1.5fr 1fr; gap: 2rem;">
        <!-- Active Sessions -->
        <section>
            <h3 style="margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 24px; height: 24px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Kegiatan Aktif Sekarang
            </h3>
            
            @if($activeSessions->isEmpty())
                <div class="card" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                    <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Tidak ada kegiatan aktif</p>
                    <p style="font-size: 0.9rem;">Kegiatan akan muncul di sini saat waktunya tiba</p>
                </div>
            @else
                <div class="grid" style="grid-template-columns: 1fr; gap: 1rem;">
                    @foreach($activeSessions as $session)
                        <div class="card" style="border-left: 4px solid var(--primary);">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <span style="background: var(--primary); color: white; padding: 0.2rem 0.6rem; border-radius: 100px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">AKTIF</span>
                                        <h4 style="font-size: 1.1rem; font-weight: 600;">{{ $session->title }}</h4>
                                    </div>
                                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                                        <svg style="width: 14px; height: 14px; display: inline; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}
                                    </p>
                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                        <span style="font-size: 0.85rem; color: var(--text-muted);">Metode:</span>
                                        @if($session->method == 'location') 
                                            <span style="background: #dbeafe; color: #1e40af; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">📍 Submit Location</span>
                                        @elseif($session->method == 'share_qr') 
                                            <span style="background: #fef3c7; color: #92400e; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">📱 Scan Admin QR</span>
                                        @elseif($session->method == 'scan_qr') 
                                            <span style="background: #dcfce7; color: #166534; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">🔍 Show User QR</span>
                                        @endif
                                        <span style="background: #f3f4f6; color: #374151; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem; margin-left: auto;">
                                            {{ $session->attendances_count ?? 0 }} presensi
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </section>


        <!-- Recent Attendances & Stats -->
        <section>
            <h3 style="margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 24px; height: 24px; color: var(--secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Presensi Terbaru
            </h3>
            
            <div class="card">
                @if($recentAttendances->isEmpty())
                    <div style="text-align: center; padding: 2rem;">
                        <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3; color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p style="color: var(--text-muted); font-size: 0.95rem; font-weight: 500;">Belum ada presensi</p>
                    </div>
                @else
                    <ul style="list-style: none; max-height: 400px; overflow-y: auto;">
                        @foreach($recentAttendances as $attendance)
                            <li style="padding: 0.75rem 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: start;">
                                <div style="flex: 1;">
                                    <p style="font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem;">{{ $attendance->user->fullname ?? $attendance->user->name }}</p>
                                    <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">{{ $attendance->session->title }}</p>
                                    <p style="font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.25rem;">
                                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $attendance->captured_at->format('H:i:s') }}
                                    </p>
                                </div>
                                <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.6rem; border-radius: 100px; font-size: 0.7rem; font-weight: 600;">✓</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- System Info -->
            <div class="card" style="margin-top: 1.5rem; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border: none;">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Info Sistem
                </h4>
                <div style="font-size: 0.85rem; line-height: 1.8;">
                    <p><strong>Role:</strong> Administrator</p>
                    <p><strong>Total Kegiatan:</strong> {{ $totalSessions }}</p>
                    <p><strong>Kegiatan Aktif:</strong> {{ $activeSessions->count() }}</p>
                    <p><strong>Presensi Hari Ini:</strong> {{ $todayAttendances }}</p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
