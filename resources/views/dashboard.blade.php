@extends('layouts.app')

@section('content')
<div class="animate-fade">
    <header style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2rem; font-weight: 700;">Dashboard</h1>
        <p style="color: var(--text-muted);">Selamat datang, {{ Auth::user()->fullname ?? Auth::user()->name }}</p>
    </header>

    <!-- Statistics Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <!-- Total Presensi Bulan Ini -->
        <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Presensi Bulan Ini</p>
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $monthlyAttendanceCount }}</h2>
                    <p style="font-size: 0.8rem; opacity: 0.8;">dari {{ $totalSessionsThisMonth }} kegiatan</p>
                </div>
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Kegiatan Aktif -->
        <div class="card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Kegiatan Aktif</p>
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $activeSessions->count() }}</h2>
                    <p style="font-size: 0.8rem; opacity: 0.8;">sedang berlangsung</p>
                </div>
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Presensi Hari Ini -->
        <div class="card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; border: none;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <p style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.5rem;">Presensi Hari Ini</p>
                    <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.25rem;">{{ $myAttendances->count() }}</h2>
                    <p style="font-size: 0.8rem; opacity: 0.8;">tercatat</p>
                </div>
                <div style="width: 60px; height: 60px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid" style="grid-template-columns: 1.5fr 1fr; gap: 2rem;">
        <!-- Active & Upcoming Activities -->
        <section>
            <h3 style="margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 24px; height: 24px; color: var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                Kegiatan Aktif
            </h3>
            
            @if($activeSessions->isEmpty())
                <div class="card" style="text-align: center; color: var(--text-muted); padding: 3rem;">
                    <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p style="font-size: 1.1rem; font-weight: 500; margin-bottom: 0.5rem;">Tidak ada kegiatan aktif saat ini</p>
                    <p style="font-size: 0.9rem;">Kegiatan akan muncul di sini saat waktunya tiba</p>
                </div>
            @else
                <div class="grid" style="grid-template-columns: 1fr; gap: 1rem;">
                    @foreach($activeSessions as $session)
                        <div class="card" style="border-left: 4px solid var(--primary);">
                            <div class="flex justify-between items-center">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <span style="background: var(--primary); color: white; padding: 0.2rem 0.6rem; border-radius: 100px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">AKTIF</span>
                                        <h4 style="font-size: 1.1rem; font-weight: 600;">{{ $session->title }}</h4>
                                    </div>
                                    <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.75rem;">
                                        <svg style="width: 14px; height: 14px; display: inline; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }}
                                    </p>
                                    <p style="font-size: 0.85rem; color: var(--primary); font-weight: 500;">
                                        Metode: 
                                        @if($session->method == 'location') 
                                            <span style="background: #dbeafe; color: #1e40af; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">📍 Submit Location</span>
                                        @elseif($session->method == 'share_qr') 
                                            <span style="background: #fef3c7; color: #92400e; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">📱 Scan Admin QR</span>
                                        @elseif($session->method == 'scan_qr') 
                                            <span style="background: #dcfce7; color: #166534; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">🔍 Show My QR</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="actions">
                                    @if($session->method == 'location')
                                        <button class="btn btn-primary" onclick="submitLocation({{ $session->id }})">
                                            📍 Kirim Lokasi
                                        </button>
                                    @elseif($session->method == 'share_qr')
                                        <a href="{{ route('employee.scan-qr') }}" class="btn btn-primary">
                                            🔍 Tunjukkan QR
                                        </a>
                                    @elseif($session->method == 'scan_qr')
                                        <button class="btn btn-primary" onclick="showMyQR('{{ Auth::user()->nip_lama }}')">
                                            📱 Scan QR
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Upcoming Sessions -->
            @if($upcomingSessions->isNotEmpty())
                <h3 style="margin-top: 2.5rem; margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 24px; height: 24px; color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Kegiatan Mendatang
                </h3>
                <div class="grid" style="grid-template-columns: 1fr; gap: 1rem;">
                    @foreach($upcomingSessions as $session)
                        <div class="card" style="border-left: 4px solid var(--accent); opacity: 0.9;">
                            <div style="display: flex; justify-content: space-between; align-items: start;">
                                <div>
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <span style="background: var(--accent); color: white; padding: 0.2rem 0.6rem; border-radius: 100px; font-size: 0.7rem; font-weight: 600; text-transform: uppercase;">UPCOMING</span>
                                        <h4 style="font-size: 1rem; font-weight: 600;">{{ $session->title }}</h4>
                                    </div>
                                    <p style="font-size: 0.85rem; color: var(--text-muted);">
                                        <svg style="width: 14px; height: 14px; display: inline; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $session->start_time->format('d M Y, H:i') }} - {{ $session->end_time->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>

        <!-- My Attendance Today -->
        <section>
            <h3 style="margin-bottom: 1.5rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                <svg style="width: 24px; height: 24px; color: var(--secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Riwayat Hari Ini
            </h3>
            <div class="card">
                @if($myAttendances->isEmpty())
                    <div style="text-align: center; padding: 2rem;">
                        <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3; color: var(--text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p style="color: var(--text-muted); font-size: 0.95rem; font-weight: 500; margin-bottom: 0.25rem;">Belum ada presensi</p>
                        <p style="color: var(--text-muted); font-size: 0.85rem;">Presensi Anda akan muncul di sini</p>
                    </div>
                @else
                    <ul style="list-style: none;">
                        @foreach($myAttendances as $attendance)
                            <li style="padding: 1rem 0; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <p style="font-weight: 600; font-size: 0.95rem; margin-bottom: 0.25rem;">{{ $attendance->session->title }}</p>
                                    <p style="font-size: 0.8rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.25rem;">
                                        <svg style="width: 12px; height: 12px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $attendance->captured_at->format('H:i:s') }}
                                    </p>
                                </div>
                                <span style="background: #dcfce7; color: #166534; padding: 0.3rem 0.8rem; border-radius: 100px; font-size: 0.75rem; font-weight: 600;">✓ Hadir</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Quick Info Card -->
            <div class="card" style="margin-top: 1.5rem; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border: none;">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Info Pegawai
                </h4>
                <div style="font-size: 0.85rem; line-height: 1.8;">
                    <p><strong>Nama:</strong> {{ Auth::user()->fullname ?? Auth::user()->name }}</p>
                    <p><strong>NIP:</strong> {{ Auth::user()->nip_lama ?? '-' }}</p>
                    <p><strong>Jabatan:</strong> {{ Auth::user()->jabatan ?? '-' }}</p>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Hidden form for geolocation -->
<form id="locationForm" action="{{ route('presence.submitLocation') }}" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="session_id" id="loc_session_id">
    <input type="hidden" name="latitude" id="loc_lat">
    <input type="hidden" name="longitude" id="loc_lng">
</form>

<div id="qrModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 400px; text-align: center;">
        <h4 style="margin-bottom: 0.5rem; font-size: 1.5rem; font-weight: 700;">QR Code Saya</h4>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;">{{ Auth::user()->fullname ?? Auth::user()->name }}</p>
        
        <div style="background: white; padding: 1.5rem; border-radius: 12px; display: inline-block; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <div id="qrcode" style="margin: 0 auto;"></div>
        </div>
        
        <p style="font-size: 1.1rem; font-weight: 600; margin-top: 1rem; color: var(--primary);">NIP: <span id="userNip"></span></p>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-top: 1rem; line-height: 1.5;">Tunjukkan kode QR ini ke petugas untuk di-scan saat melakukan presensi kegiatan.</p>
        
        <button class="btn btn-outline" style="margin-top: 1.5rem; width: 100%;" onclick="closeQRModal()">Tutup</button>
    </div>
</div>

@push('scripts')
<!-- Include QRCode.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    let qrcodeInstance = null;

    function submitLocation(sessionId) {
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser Anda.');
            return;
        }

        const btn = event.target;
        const originalText = btn.innerText;
        btn.innerText = 'Mencari Lokasi...';
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
                btn.innerText = originalText;
                btn.disabled = false;
            }
        );
    }

    function showMyQR(nip) {
        document.getElementById('userNip').innerText = nip;
        
        // Clear previous QR code if exists
        const qrcodeContainer = document.getElementById('qrcode');
        qrcodeContainer.innerHTML = '';
        
        // Generate new QR code
        qrcodeInstance = new QRCode(qrcodeContainer, {
            text: nip,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        
        document.getElementById('qrModal').style.display = 'flex';
    }

    function closeQRModal() {
        document.getElementById('qrModal').style.display = 'none';
    }
</script>
@endpush
@endsection
