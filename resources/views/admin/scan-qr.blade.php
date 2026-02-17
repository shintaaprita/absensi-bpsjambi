@extends('layouts.app')

@section('content')
<div class="animate-fade">
    <header style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2rem; font-weight: 700;">Scan QR Code Pegawai</h1>
        <p style="color: var(--text-muted);">Scan barcode/QR code pegawai untuk mencatat presensi kegiatan</p>
    </header>

    @if(session('success'))
        <div class="alert alert-success" style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #16a34a;">
            <strong>✓ Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; border-left: 4px solid #dc2626;">
            <strong>✗ Error!</strong> {{ session('error') }}
        </div>
    @endif

    <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 2rem;">
        <!-- Scanner Section -->
        <section>
            <div class="card" style="padding: 2rem;">
                <h3 style="margin-bottom: 1.5rem; font-weight: 600;">Pilih Kegiatan</h3>
                
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Kegiatan Aktif:</label>
                    <select id="sessionSelect" class="form-control" style="width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 8px; font-size: 1rem;">
                        <option value="">-- Pilih Kegiatan --</option>
                        @foreach($activeSessions as $session)
                            <option value="{{ $session->id }}" data-method="{{ $session->method }}">
                                {{ $session->title }} ({{ $session->start_time->format('H:i') }} - {{ $session->end_time->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="scannerContainer" style="display: none;">
                    <h3 style="margin-bottom: 1rem; font-weight: 600; color: var(--primary);">Scanner QR Code</h3>
                    <div id="reader" style="width: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"></div>
                    
                    <div style="margin-top: 1.5rem; padding: 1rem; background: #f0f9ff; border-radius: 8px; border-left: 4px solid #0284c7;">
                        <p style="font-size: 0.9rem; color: #075985; margin: 0;">
                            <strong>Petunjuk:</strong> Arahkan kamera ke QR code/barcode pada kartu pegawai
                        </p>
                    </div>
                </div>

                <div id="noSessionWarning" style="padding: 2rem; text-align: center; color: var(--text-muted);">
                    <svg style="width: 64px; height: 64px; margin: 0 auto 1rem; opacity: 0.3;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <p>Silakan pilih kegiatan terlebih dahulu</p>
                </div>
            </div>
        </section>

        <!-- Recent Scans Section -->
        <section>
            <div class="card" style="padding: 2rem;">
                <h3 style="margin-bottom: 1.5rem; font-weight: 600;">Presensi Terbaru</h3>
                
                <div id="recentScans" style="max-height: 500px; overflow-y: auto;">
                    @if($recentAttendances->isEmpty())
                        <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Belum ada presensi tercatat</p>
                    @else
                        <ul style="list-style: none; padding: 0; margin: 0;">
                            @foreach($recentAttendances as $attendance)
                                <li style="padding: 1rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <p style="font-weight: 600; margin-bottom: 0.25rem;">{{ $attendance->user->fullname ?? $attendance->user->name }}</p>
                                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 0.25rem;">{{ $attendance->user->nip_lama }}</p>
                                        <p style="font-size: 0.8rem; color: var(--text-muted);">{{ $attendance->session->title }}</p>
                                    </div>
                                    <div style="text-align: right;">
                                        <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.75rem; border-radius: 100px; font-size: 0.75rem; font-weight: 600; display: inline-block; margin-bottom: 0.25rem;">Hadir</span>
                                        <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">{{ $attendance->captured_at->format('H:i:s') }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 400px; text-align: center; animation: slideUp 0.3s ease;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);">
            <svg style="width: 48px; height: 48px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 style="margin-bottom: 0.5rem; font-size: 1.5rem; font-weight: 700; color: #166534;">Presensi Berhasil!</h3>
        <p id="successUserName" style="font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-primary);"></p>
        <p id="successUserNip" style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.5rem;"></p>
        <button class="btn btn-primary" onclick="closeSuccessModal()" style="width: 100%;">Scan Berikutnya</button>
    </div>
</div>

<!-- Error Modal -->
<div id="errorModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
    <div class="card" style="max-width: 400px; text-align: center; animation: slideUp 0.3s ease;">
        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(239, 68, 68, 0.3);">
            <svg style="width: 48px; height: 48px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h3 style="margin-bottom: 1rem; font-size: 1.5rem; font-weight: 700; color: #991b1b;">Gagal!</h3>
        <p id="errorMessage" style="font-size: 1rem; color: var(--text-muted); margin-bottom: 1.5rem;"></p>
        <button class="btn btn-outline" onclick="closeErrorModal()" style="width: 100%;">Tutup</button>
    </div>
</div>

@push('styles')
<style>
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    #reader video {
        border-radius: 8px;
    }

    #reader__scan_region {
        border-radius: 8px !important;
    }
</style>
@endpush

@push('scripts')
<!-- Include html5-qrcode library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let html5QrCode = null;
    let currentSessionId = null;
    let isScanning = false;

    document.getElementById('sessionSelect').addEventListener('change', function() {
        const sessionId = this.value;
        const selectedOption = this.options[this.selectedIndex];
        const method = selectedOption.getAttribute('data-method');

        if (sessionId && method === 'scan_qr') {
            currentSessionId = sessionId;
            document.getElementById('scannerContainer').style.display = 'block';
            document.getElementById('noSessionWarning').style.display = 'none';
            startScanner();
        } else if (sessionId && method !== 'scan_qr') {
            alert('Kegiatan ini tidak menggunakan metode scan QR. Silakan pilih kegiatan dengan metode "Presensi kegiatan via scan QR".');
            this.value = '';
            stopScanner();
            document.getElementById('scannerContainer').style.display = 'none';
            document.getElementById('noSessionWarning').style.display = 'block';
        } else {
            currentSessionId = null;
            stopScanner();
            document.getElementById('scannerContainer').style.display = 'none';
            document.getElementById('noSessionWarning').style.display = 'block';
        }
    });

    function startScanner() {
        if (isScanning) return;

        html5QrCode = new Html5Qrcode("reader");
        
        const config = { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrCode.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            onScanError
        ).then(() => {
            isScanning = true;
        }).catch(err => {
            console.error("Unable to start scanner:", err);
            alert("Tidak dapat mengakses kamera. Pastikan Anda memberikan izin akses kamera.");
        });
    }

    function stopScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                isScanning = false;
                html5QrCode.clear();
            }).catch(err => {
                console.error("Error stopping scanner:", err);
            });
        }
    }

    function onScanSuccess(decodedText, decodedResult) {
        if (!currentSessionId) {
            showError('Silakan pilih kegiatan terlebih dahulu');
            return;
        }

        // Pause scanning while processing
        if (html5QrCode && isScanning) {
            html5QrCode.pause(true);
        }

        // Send to server
        fetch('{{ route("presence.processQR") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_token: decodedText,
                session_id: currentSessionId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.user_name, data.user_nip);
                // Reload recent scans after 2 seconds
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                showError(data.message);
                // Resume scanning after error
                if (html5QrCode && isScanning) {
                    html5QrCode.resume();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Terjadi kesalahan saat memproses QR code');
            // Resume scanning after error
            if (html5QrCode && isScanning) {
                html5QrCode.resume();
            }
        });
    }

    function onScanError(errorMessage) {
        // Ignore scan errors (they happen frequently during scanning)
    }

    function showSuccess(userName, userNip) {
        document.getElementById('successUserName').textContent = userName;
        document.getElementById('successUserNip').textContent = 'NIP: ' + userNip;
        document.getElementById('successModal').style.display = 'flex';
    }

    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorModal').style.display = 'flex';
    }

    function closeSuccessModal() {
        document.getElementById('successModal').style.display = 'none';
        // Resume scanning
        if (html5QrCode && isScanning) {
            html5QrCode.resume();
        }
    }

    function closeErrorModal() {
        document.getElementById('errorModal').style.display = 'none';
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        stopScanner();
    });
</script>
@endpush
@endsection
