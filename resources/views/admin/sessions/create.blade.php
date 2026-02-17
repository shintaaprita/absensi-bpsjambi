@extends('layouts.app')

@section('content')
<div class="animate-fade" style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 700;">Tambah Kegiatan</h1>
        <p style="color: var(--text-muted);">Buat sesi presensi baru</p>
    </div>

    <form action="{{ route('admin.sessions.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="form-group">
                <label for="title">Judul Kegiatan</label>
                <input type="text" id="title" name="title" placeholder="Contoh: Rapat Koordinasi" required>
            </div>

            <div class="form-group">
                <label for="description">Deskripsi</label>
                <textarea id="description" name="description" rows="3" placeholder="Informasi tambahan..."></textarea>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="start_time">Waktu Mulai</label>
                    <input type="datetime-local" id="start_time" name="start_time" required>
                </div>
                <div class="form-group">
                    <label for="end_time">Waktu Selesai</label>
                    <input type="datetime-local" id="end_time" name="end_time" required>
                </div>
            </div>

            <div class="form-group">
                <label for="method">Metode Presensi</label>
                <select id="method" name="method" onchange="toggleMethodFields(this.value)" required>
                    <option value="location">Submit Location (Geofencing)</option>
                    <option value="share_qr">Share QR (Admin Tampilkan QR)</option>
                    <option value="scan_qr">Scan QR (Admin Scan QR Pegawai)</option>
                </select>
            </div>

            <div id="locationFields" style="background: var(--bg-main); padding: 1.5rem; border-radius: var(--radius-md); margin-top: 1rem;">
                <h4 style="margin-bottom: 1rem; font-size: 0.95rem;">Konfigurasi Lokasi</h4>
                <div class="form-group">
                    <label for="location_name">Nama Lokasi</label>
                    <input type="text" id="location_name" name="location_name" placeholder="Contoh: Kantor BPS Jambi">
                </div>
                <div class="grid" style="grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" id="latitude" name="latitude" placeholder="-1.6111">
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" id="longitude" name="longitude" placeholder="103.6111">
                    </div>
                    <div class="form-group">
                        <label for="radius">Radius (Meter)</label>
                        <input type="number" id="radius" name="radius" value="100">
                    </div>
                </div>
                <button type="button" class="btn btn-outline" style="font-size: 0.8rem;" onclick="getCurrentLocation()">Dapatkan Lokasi Saya Saat Ini</button>
            </div>

            <div class="flex justify-between items-center" style="margin-top: 2rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">
                <a href="{{ route('admin.sessions.index') }}" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">Simpan Kegiatan</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleMethodFields(val) {
        const locFields = document.getElementById('locationFields');
        if (val === 'location') {
            locFields.style.display = 'block';
        } else {
            locFields.style.display = 'none';
        }
    }

    function getCurrentLocation() {
        if (!navigator.geolocation) {
            alert('Geolocation not supported');
            return;
        }
        navigator.geolocation.getCurrentPosition((pos) => {
            document.getElementById('latitude').value = pos.coords.latitude;
            document.getElementById('longitude').value = pos.coords.longitude;
        }, (err) => {
            alert('Error: ' + err.message);
        });
    }

    // Initialize
    toggleMethodFields(document.getElementById('method').value);
</script>
@endpush
@endsection
