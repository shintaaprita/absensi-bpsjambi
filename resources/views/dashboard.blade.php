@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="animate-fade">
    <!-- Page Header -->
    <div class="page-header">
        <h1>👋 Halo, {{ $user->name ?? Auth::user()->name }}!</h1>
        <p>{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y') }} &bull; Selamat beraktivitas</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
        <div style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color:white; border-radius: var(--radius-md); padding: 1.25rem; border: none; box-shadow: 0 4px 15px rgba(99,102,241,0.35);">
            <div style="display:flex; justify-content:space-between; align-items:start; gap:1rem;">
                <div>
                    <p style="font-size:0.8rem; opacity:0.85; margin-bottom:0.35rem; text-transform:uppercase; letter-spacing:0.05em;">Presensi Bulan Ini</p>
                    <h2 style="font-size:2.25rem; font-weight:800; line-height:1;">{{ $monthlyAttendanceCount }}</h2>
                    <p style="font-size:0.75rem; opacity:0.75; margin-top:0.25rem;">dari {{ $totalSessionsThisMonth }} kegiatan</p>
                </div>
                <div style="width:48px; height:48px; background:rgba(255,255,255,0.2); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color:white; border-radius: var(--radius-md); padding: 1.25rem; border: none; box-shadow: 0 4px 15px rgba(16,185,129,0.35);">
            <div style="display:flex; justify-content:space-between; align-items:start; gap:1rem;">
                <div>
                    <p style="font-size:0.8rem; opacity:0.85; margin-bottom:0.35rem; text-transform:uppercase; letter-spacing:0.05em;">Kegiatan Aktif</p>
                    <h2 style="font-size:2.25rem; font-weight:800; line-height:1;">{{ $activeSessions->count() }}</h2>
                    <p style="font-size:0.75rem; opacity:0.75; margin-top:0.25rem;">sedang berlangsung</p>
                </div>
                <div style="width:48px; height:48px; background:rgba(255,255,255,0.2); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color:white; border-radius: var(--radius-md); padding: 1.25rem; border: none; box-shadow: 0 4px 15px rgba(245,158,11,0.35);">
            <div style="display:flex; justify-content:space-between; align-items:start; gap:1rem;">
                <div>
                    <p style="font-size:0.8rem; opacity:0.85; margin-bottom:0.35rem; text-transform:uppercase; letter-spacing:0.05em;">Presensi Hari Ini</p>
                    <h2 style="font-size:2.25rem; font-weight:800; line-height:1;">{{ $myAttendances->count() }}</h2>
                    <p style="font-size:0.75rem; opacity:0.75; margin-top:0.25rem;">tercatat</p>
                </div>
                <div style="width:48px; height:48px; background:rgba(255,255,255,0.2); border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid" style="grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start;">
        <!-- Left: Active & Upcoming Sessions -->
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            <!-- Active Sessions -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <div style="width:8px; height:8px; background:#22c55e; border-radius:50%; animation: pulse 2s infinite;"></div>
                        Kegiatan Aktif Sekarang
                    </div>
                    <span class="badge badge-success">{{ $activeSessions->count() }} aktif</span>
                </div>
                <div class="card-body" style="padding-top: 0.875rem;">
                    @if($activeSessions->isEmpty())
                        <div style="text-align:center; padding: 2.5rem 1rem; color:var(--text-muted);">
                            <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 0.75rem; opacity:0.3;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            <p style="font-weight:600; margin-bottom:0.25rem;">Tidak ada kegiatan aktif</p>
                            <p style="font-size:0.82rem;">Kegiatan akan muncul di sini saat waktunya tiba</p>
                        </div>
                    @else
                        <div style="display:flex; flex-direction:column; gap:0.75rem;">
                            @foreach($activeSessions as $session)
                                <div style="border: 1px solid var(--border); border-left: 4px solid #6366f1; border-radius: var(--radius-sm); padding: 1rem;">
                                    <div style="display:flex; justify-content:space-between; align-items:start; gap:1rem; flex-wrap:wrap;">
                                        <div style="flex:1;">
                                            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.4rem; flex-wrap:wrap;">
                                                <span class="badge badge-primary">AKTIF</span>
                                                <span style="font-weight:600; font-size:0.95rem;">{{ $session->title }}</span>
                                            </div>
                                            <p style="font-size:0.8rem; color:var(--text-muted); margin-bottom:0.5rem;">🕐 {{ $session->start_time->format('H:i') }} – {{ $session->end_time->format('H:i') }}</p>
                                            <div>
                                                @if($session->method == 'location')
                                                    <span class="badge badge-gray">📍 Submit Lokasi</span>
                                                @elseif($session->method == 'share_qr')
                                                    <span class="badge badge-warning">📱 Scan Admin QR</span>
                                                @elseif($session->method == 'scan_qr')
                                                    <span class="badge badge-success">🔍 Show My QR</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            @if($session->method == 'location')
                                                <button class="btn btn-primary btn-sm" onclick="submitLocation({{ $session->id }}, event)">
                                                    📍 Kirim Lokasi
                                                </button>
                                            @elseif($session->method == 'share_qr')
                                                <a href="{{ route('employee.scan-qr') }}" class="btn btn-primary btn-sm">
                                                    🔍 Tunjukkan QR
                                                </a>
                                            @elseif($session->method == 'scan_qr')
                                                <button class="btn btn-primary btn-sm" onclick="showMyQR('{{ Auth::user()->nip_lama }}')">
                                                    📱 Scan QR
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Upcoming Sessions -->
            @if($upcomingSessions->isNotEmpty())
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Kegiatan Mendatang
                    </div>
                    <span class="badge badge-warning">{{ $upcomingSessions->count() }} upcoming</span>
                </div>
                <div style="divide-y: 1px solid var(--border);">
                    @foreach($upcomingSessions as $session)
                        <div style="padding: 0.875rem 1.5rem; border-bottom: 1px solid var(--border); display:flex; align-items:center; gap:1rem;">
                            <div style="width:40px; height:40px; background:linear-gradient(135deg,#fef3c7,#fde68a); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <svg width="18" height="18" fill="none" stroke="#92400e" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <p style="font-weight:600; font-size:0.9rem;">{{ $session->title }}</p>
                                <p style="font-size:0.78rem; color:var(--text-muted);">{{ $session->start_time->format('d M Y, H:i') }} – {{ $session->end_time->format('H:i') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Sidebar -->
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            <!-- User Info Card -->
            <div class="card" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); border: none; color: white;">
                <div class="card-body" style="text-align:center;">
                    <div style="width:72px; height:72px; border-radius:50%; margin: 0 auto 1rem; overflow:hidden; border:3px solid rgba(255,255,255,0.3);">
                        @if(Auth::user()->profile_photo)
                            @if(filter_var(Auth::user()->profile_photo, FILTER_VALIDATE_URL))
                                <img src="{{ Auth::user()->profile_photo }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" style="width:100%;height:100%;object-fit:cover;">
                            @endif
                        @else
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,#6366f1,#a855f7);display:flex;align-items:center;justify-content:center;font-size:1.75rem;font-weight:800;color:white;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <p style="font-weight:700; font-size:1rem; margin-bottom:0.25rem;">{{ Auth::user()->fullname ?? Auth::user()->name }}</p>
                    <p style="font-size:0.78rem; opacity:0.7;">{{ Auth::user()->nip_lama ?? '-' }}</p>
                    <p style="font-size:0.78rem; opacity:0.7; margin-top:0.1rem;">{{ Auth::user()->jabatan ?? '-' }}</p>
                    <div style="margin-top:1rem; padding-top:1rem; border-top:1px solid rgba(255,255,255,0.15);">
                        <span class="badge" style="background:rgba(255,255,255,0.15); color:white;">{{ ucfirst(session('role_name','Pegawai')) }}</span>
                    </div>
                </div>
            </div>

            <!-- My Attendance Today -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Riwayat Hari Ini
                    </div>
                </div>
                @if($myAttendances->isEmpty())
                    <div style="padding: 1.5rem; text-align:center; color:var(--text-muted);">
                        <svg width="36" height="36" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin:0 auto 0.5rem; opacity:0.35;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p style="font-size:0.85rem;">Belum ada presensi hari ini</p>
                    </div>
                @else
                    <ul style="list-style:none;">
                        @foreach($myAttendances as $attendance)
                            <li style="padding: 0.75rem 1.25rem; border-bottom: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <p style="font-weight:600; font-size:0.875rem; margin-bottom:0.1rem;">{{ $attendance->session->title }}</p>
                                    <p style="font-size:0.75rem; color:var(--text-muted);">🕐 {{ $attendance->captured_at->format('H:i:s') }}</p>
                                </div>
                                <span class="badge badge-success">✓ Hadir</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms & Modals -->
<form id="locationForm" action="{{ route('presence.submitLocation') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="session_id" id="loc_session_id">
    <input type="hidden" name="latitude" id="loc_lat">
    <input type="hidden" name="longitude" id="loc_lng">
</form>

<div id="qrModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.65); z-index:1000; justify-content:center; align-items:center; backdrop-filter:blur(6px);">
    <div class="card" style="max-width:380px; width:92%; text-align:center;">
        <div class="card-header" style="justify-content:center; flex-direction:column; gap:0.25rem; text-align:center; border-bottom:none; padding-bottom:0;">
            <h4 style="font-size:1.25rem; font-weight:700;">QR Code Saya</h4>
            <p style="font-size:0.85rem; color:var(--text-muted);">{{ Auth::user()->fullname ?? Auth::user()->name }}</p>
        </div>
        <div class="card-body" style="padding-top:1rem;">
            <div style="background:white; padding:1.25rem; border-radius:12px; display:inline-block; box-shadow: 0 4px 16px rgba(0,0,0,0.12);">
                <div id="qrcode" style="margin:0 auto;"></div>
            </div>
            <p style="font-size:1rem; font-weight:600; margin-top:1rem; color:var(--primary);">NIP: <span id="userNip"></span></p>
            <p style="font-size:0.8rem; color:var(--text-muted); margin-top:0.75rem; line-height:1.5;">Tunjukkan QR ini ke petugas saat absensi kegiatan.</p>
            <button class="btn btn-outline" style="margin-top:1.25rem; width:100%;" onclick="closeQRModal()">Tutup</button>
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
        .dashboard-main-grid {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    let qrcodeInstance = null;

    function submitLocation(sessionId, e) {
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser Anda.');
            return;
        }
        const btn = e.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '⏳ Mencari Lokasi...';
        btn.disabled = true;
        navigator.geolocation.getCurrentPosition(
            (position) => {
                document.getElementById('loc_session_id').value = sessionId;
                document.getElementById('loc_lat').value = position.coords.latitude;
                document.getElementById('loc_lng').value = position.coords.longitude;
                document.getElementById('locationForm').submit();
            },
            (error) => {
                alert('Gagal mendapatkan lokasi: ' + error.message);
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        );
    }

    function showMyQR(nip) {
        document.getElementById('userNip').innerText = nip;
        const qrcodeContainer = document.getElementById('qrcode');
        qrcodeContainer.innerHTML = '';
        qrcodeInstance = new QRCode(qrcodeContainer, {
            text: nip, width: 200, height: 200,
            colorDark: "#000000", colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        document.getElementById('qrModal').style.display = 'flex';
    }

    function closeQRModal() {
        document.getElementById('qrModal').style.display = 'none';
    }

    // Responsive grid
    const mainGrid = document.querySelector('[data-dashboard-grid]');
    function handleResize() {
        if (window.innerWidth <= 768 && mainGrid) {
            mainGrid.style.gridTemplateColumns = '1fr';
        }
    }
    window.addEventListener('resize', handleResize);
    handleResize();
</script>
@endpush
@endsection
