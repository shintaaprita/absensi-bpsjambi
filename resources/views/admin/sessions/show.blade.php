@extends('layouts.app')

@section('content')
<div class="animate-fade">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 700;">{{ $session->title }}</h1>
            <p style="color: var(--text-muted);">{{ $session->start_time->format('d F Y, H:i') }} - {{ $session->end_time->format('H:i') }}</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('admin.sessions.index') }}" class="btn btn-outline">Kembali</a>
        </div>
    </div>

    <div class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
        <!-- Attendance List -->
        <section>
            <div class="card" style="padding: 0;">
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-weight: 600;">Daftar Kehadiran</h3>
                    <span style="font-size: 0.9rem; color: var(--text-muted);">Total: {{ $session->attendances->count() }} Orang</span>
                </div>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; background: var(--bg-main); border-bottom: 1px solid var(--border);">
                            <th style="padding: 1rem 1.5rem; font-size: 0.85rem; font-weight: 600;">Nama</th>
                            <th style="padding: 1rem 1.5rem; font-size: 0.85rem; font-weight: 600;">NIP</th>
                            <th style="padding: 1rem 1.5rem; font-size: 0.85rem; font-weight: 600;">Waktu</th>
                            <th style="padding: 1rem 1.5rem; font-size: 0.85rem; font-weight: 600;">Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($session->attendances as $attendance)
                            <tr style="border-bottom: 1px solid var(--border);">
                                <td style="padding: 1rem 1.5rem; font-weight: 500;">{{ $attendance->user->fullname ?? $attendance->user->name }}</td>
                                <td style="padding: 1rem 1.5rem; color: var(--text-muted);">{{ $attendance->user->nip_lama }}</td>
                                <td style="padding: 1rem 1.5rem;">{{ $attendance->captured_at->format('H:i:s') }}</td>
                                <td style="padding: 1rem 1.5rem;">
                                    <span style="font-size: 0.75rem; border: 1px solid var(--border); padding: 0.1rem 0.4rem; border-radius: 4px;">
                                        {{ $attendance->method_used }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 3rem; text-align: center; color: var(--text-muted);">Belum ada yang hadir.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Session Tools -->
        <aside>
            <h3 style="margin-bottom: 1.5rem; font-weight: 600;">Panel Kontrol</h3>
            
            @if($session->method == 'share_qr')
                <div class="card" style="text-align: center;">
                    <h4 style="margin-bottom: 1rem;">QR Code Kehadiran</h4>
                    <div style="background: white; padding: 1rem; border: 1px solid var(--border); border-radius: var(--radius-md); margin-bottom: 1rem; display: flex; flex-direction: column; align-items: center;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={{ urlencode(route('presence.scanQR', $session->qr_token)) }}" alt="QR Code" style="max-width: 100%;">
                        <p style="margin-top: 1rem; font-size: 0.8rem; color: var(--text-muted);">Token: {{ $session->qr_token }}</p>
                    </div>
                    <p style="font-size: 0.85rem;">Minta pegawai untuk men-scan kode di atas menggunakan kamera ponsel mereka.</p>
                </div>
            @elseif($session->method == 'scan_qr')
                <div class="card" style="text-align: center;">
                    <h4 style="margin-bottom: 1rem;">Scan QR Pegawai</h4>
                    <div style="border: 2px dashed var(--primary); padding: 2rem; border-radius: var(--radius-md); background: #f0f4ff; color: var(--primary);">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-bottom: 1rem;">
                            <path d="M4 8V4H8M4 16V20H8M16 4H20V8M16 20H20V16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <rect x="8" y="8" width="8" height="8" rx="1" stroke="currentColor" stroke-width="2"/>
                        </svg>
                        <p style="font-weight: 600;">Gunakan Scanner</p>
                    </div>
                    <div style="margin-top: 1.5rem;">
                        <input type="text" id="adminScanInput" placeholder="Tempel token QR di sini..." style="margin-bottom: 0.5rem;" onkeypress="if(event.key === 'Enter') processAdminScan()">
                        <button class="btn btn-primary" style="width: 100%; justify-content: center;" onclick="processAdminScan()">Kirim</button>
                    </div>
                    <div id="scanMsg" style="margin-top: 1rem; font-size: 0.85rem; font-weight: 600;"></div>
                </div>
            @elseif($session->method == 'location')
                <div class="card">
                    <h4 style="margin-bottom: 1rem;">Radius Geofencing</h4>
                    <div style="background: var(--bg-main); padding: 1rem; border-radius: var(--radius-sm); font-size: 0.9rem;">
                        <p><strong>Lokasi:</strong> {{ $session->location_name }}</p>
                        <p><strong>Koordinat:</strong> {{ $session->latitude }}, {{ $session->longitude }}</p>
                        <p><strong>Radius:</strong> {{ $session->radius }} meter</p>
                    </div>
                    <p style="margin-top: 1rem; font-size: 0.85rem; color: var(--text-muted);">Pegawai hanya bisa mengisi jika berada dalam radius tersebut.</p>
                </div>
            @endif

            <div class="card" style="margin-top: 1.5rem;">
                <h4 style="margin-bottom: 0.5rem;">Status Sesi</h4>
                @php $now = \Carbon\Carbon::now(); @endphp
                @if($now->between($session->start_time, $session->end_time))
                    <span style="background: #dcfce7; color: #166534; padding: 0.2rem 0.6rem; border-radius: 100px; font-size: 0.8rem; font-weight: 600;">SEDANG BERLANGSUNG</span>
                @elseif($now->lt($session->start_time))
                    <span style="background: #fef9c3; color: #854d0e; padding: 0.2rem 0.6rem; border-radius: 100px; font-size: 0.8rem; font-weight: 600;">BELUM DIMULAI</span>
                @else
                    <span style="background: #f1f5f9; color: #475569; padding: 0.2rem 0.6rem; border-radius: 100px; font-size: 0.8rem; font-weight: 600;">SELESAI</span>
                @endif
            </div>
        </aside>
    </div>
</div>

@push('scripts')
<script>
    function processAdminScan() {
        const input = document.getElementById('adminScanInput');
        const token = input.value;
        const msg = document.getElementById('scanMsg');
        
        if (!token) return;

        fetch('{{ route("presence.processQR") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                user_token: token,
                session_id: '{{ $session->id }}'
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                msg.style.color = 'var(--secondary)';
                msg.innerText = '✓ ' + data.message;
                input.value = '';
                setTimeout(() => location.reload(), 1500);
            } else {
                msg.style.color = 'var(--danger)';
                msg.innerText = '✗ ' + data.message;
            }
        });
    }
</script>
@endpush
@endsection
