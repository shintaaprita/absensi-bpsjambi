@extends('layouts.app')

@section('content')
<div class="animate-fade">
    <header style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2rem; font-weight: 700;">Tampilkan QR Code Kegiatan</h1>
        <p style="color: var(--text-muted);">Tampilkan QR code ini kepada pegawai untuk di-scan</p>
    </header>

    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 2rem;">
        <!-- QR Code Display -->
        <section>
            <div class="card" style="text-align: center; padding: 2.5rem;">
                <h3 style="margin-bottom: 1.5rem; font-weight: 600; color: var(--primary);">QR Code Kegiatan</h3>
                
                @if($session)
                    <div style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 1.5rem;">
                        <div id="qrcode" style="display: flex; justify-content: center; margin-bottom: 1rem;"></div>
                        <p style="font-size: 0.85rem; color: var(--text-muted); word-break: break-all;">Token: {{ $session->qr_token }}</p>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 12px; text-align: left;">
                        <h4 style="font-size: 1.2rem; font-weight: 600; margin-bottom: 0.75rem;">{{ $session->title }}</h4>
                        <p style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 0.5rem;">{{ $session->description }}</p>
                        <p style="font-size: 0.85rem; opacity: 0.8;">
                            <svg style="width: 14px; height: 14px; display: inline; margin-right: 4px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $session->start_time->format('d M Y, H:i') }} - {{ $session->end_time->format('H:i') }}
                        </p>
                    </div>
                @else
                    <div style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <p style="font-size: 1.1rem; font-weight: 500;">Pilih kegiatan terlebih dahulu</p>
                    </div>
                @endif
            </div>

            <div style="margin-top: 1.5rem; text-align: center;">
                <a href="{{ route('dashboard') }}" class="btn btn-outline">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </section>

        <!-- Session Selection -->
        <section>
            <div class="card">
                <h3 style="margin-bottom: 1.5rem; font-weight: 600;">Pilih Kegiatan</h3>
                
                @if($activeSessions->isEmpty())
                    <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                        <p>Tidak ada kegiatan aktif dengan metode "Share QR"</p>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @foreach($activeSessions as $s)
                            <a href="{{ route('admin.show-qr', $s->id) }}" 
                               class="card" 
                               style="text-decoration: none; border: 2px solid {{ $session && $session->id == $s->id ? 'var(--primary)' : 'var(--border)' }}; transition: all 0.2s; cursor: pointer;"
                               onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateX(4px)';"
                               onmouseout="this.style.borderColor='{{ $session && $session->id == $s->id ? 'var(--primary)' : 'var(--border)' }}'; this.style.transform='translateX(0)';">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <svg style="width: 24px; height: 24px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                    </div>
                                    <div style="flex: 1;">
                                        <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--text);">{{ $s->title }}</h4>
                                        <p style="font-size: 0.85rem; color: var(--text-muted);">
                                            {{ $s->start_time->format('H:i') }} - {{ $s->end_time->format('H:i') }}
                                        </p>
                                    </div>
                                    @if($session && $session->id == $s->id)
                                        <div style="width: 24px; height: 24px; background: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <svg style="width: 16px; height: 16px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Instructions -->
            <div class="card" style="margin-top: 1.5rem; background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%); border: none;">
                <h4 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg style="width: 20px; height: 20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Cara Penggunaan
                </h4>
                <ol style="font-size: 0.85rem; line-height: 1.8; padding-left: 1.25rem; margin: 0;">
                    <li>Pilih kegiatan dari daftar di atas</li>
                    <li>QR code akan ditampilkan di sebelah kiri</li>
                    <li>Pegawai membuka halaman "Scan QR" di dashboard mereka</li>
                    <li>Pegawai mengarahkan kamera ke QR code ini</li>
                    <li>Presensi otomatis tercatat!</li>
                </ol>
            </div>
        </section>
    </div>
</div>

@push('scripts')
<!-- Include QRCode.js library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    @if($session)
        // Generate QR code
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $session->qr_token }}",
            width: 256,
            height: 256,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    @endif
</script>
@endpush
@endsection
