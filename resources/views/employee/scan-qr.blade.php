@extends('layouts.app')

@section('content')
<div class="animate-fade">
    <header style="margin-bottom: 2.5rem;">
        <h1 style="font-size: 2rem; font-weight: 700;">Scan QR Code Admin</h1>
        <p style="color: var(--text-muted);">Scan QR code yang ditampilkan oleh admin untuk mencatat presensi</p>
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

    <div class="card" style="max-width: 600px; margin: 0 auto; padding: 2rem;">
        <h3 style="margin-bottom: 1rem; font-weight: 600; color: var(--primary); text-align: center;">Scanner QR Code</h3>
        <p style="text-align: center; color: var(--text-muted); margin-bottom: 2rem;">Arahkan kamera ke QR code yang ditampilkan admin</p>
        
        <div id="reader" style="width: 100%; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"></div>
        
        <div style="margin-top: 1.5rem; padding: 1rem; background: #f0f9ff; border-radius: 8px; border-left: 4px solid #0284c7;">
            <p style="font-size: 0.9rem; color: #075985; margin: 0;">
                <strong>Petunjuk:</strong> Pastikan QR code terlihat jelas di kamera. Scanner akan otomatis membaca QR code.
            </p>
        </div>

        <div style="margin-top: 1.5rem; text-align: center;">
            <a href="{{ route('dashboard') }}" class="btn btn-outline">
                ← Kembali ke Dashboard
            </a>
        </div>
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
        <p id="successMessage" style="font-size: 1rem; color: var(--text-muted); margin-bottom: 1.5rem;"></p>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('dashboard') }}'" style="width: 100%;">Kembali ke Dashboard</button>
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
        <button class="btn btn-outline" onclick="closeErrorModal()" style="width: 100%;">Coba Lagi</button>
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
    let isScanning = false;
    let isProcessing = false;

    // Start scanner automatically
    window.addEventListener('load', function() {
        startScanner();
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
        if (isProcessing) return;
        
        isProcessing = true;

        // Pause scanning while processing
        if (html5QrCode && isScanning) {
            html5QrCode.pause(true);
        }

        // The QR code should contain the session token
        // Redirect to the scan endpoint
        window.location.href = '/presence/scan-qr/' + decodedText;
    }

    function onScanError(errorMessage) {
        // Ignore scan errors (they happen frequently during scanning)
    }

    function showSuccess(message) {
        document.getElementById('successMessage').textContent = message;
        document.getElementById('successModal').style.display = 'flex';
    }

    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorModal').style.display = 'flex';
    }

    function closeErrorModal() {
        document.getElementById('errorModal').style.display = 'none';
        isProcessing = false;
        // Resume scanning
        if (html5QrCode && isScanning) {
            html5QrCode.resume();
        }
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        stopScanner();
    });
</script>
@endpush
@endsection
