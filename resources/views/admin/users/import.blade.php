@extends('layouts.app')
@section('title', 'Import Anggota CSV')

@section('content')
<div class="animate-fade" style="max-width:680px; margin:0 auto;">
    <header style="margin-bottom:2rem;">
        <a href="{{ route('admin.users.index') }}" style="color:var(--text-muted); text-decoration:none; font-size:0.9rem; display:flex; align-items:center; gap:0.35rem; margin-bottom:1rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Anggota
        </a>
        <h1 style="font-size:1.8rem; font-weight:700;">Import Anggota dari CSV</h1>
        <p style="color:var(--text-muted);">Upload file CSV untuk menambahkan banyak anggota sekaligus</p>
    </header>

    @if($errors->any())
        <div class="card" style="background:#fef2f2; border-color:#f87171; color:#991b1b; margin-bottom:1.5rem; padding:1rem;">
            <strong>Terdapat kesalahan:</strong>
            <ul style="margin-top:0.5rem; padding-left:1.2rem; font-size:0.9rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Panduan Format --}}
    <div class="card" style="padding:1.5rem; margin-bottom:1.5rem; background:linear-gradient(135deg,#eff6ff,#dbeafe); border-color:#93c5fd;">
        <h3 style="font-size:1rem; font-weight:600; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
            <svg width="20" height="20" fill="none" stroke="#3b82f6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Panduan Format CSV
        </h3>
        <p style="font-size:0.85rem; margin-bottom:0.75rem;">File CSV harus memiliki kolom dengan urutan berikut:</p>
        <div style="background:white; border-radius:8px; overflow:hidden; border:1px solid #bfdbfe;">
            <table style="width:100%; border-collapse:collapse; font-size:0.82rem;">
                <thead>
                    <tr style="background:#1e40af; color:white;">
                        <th style="padding:0.5rem 0.75rem; text-align:left;">Kolom</th>
                        <th style="padding:0.5rem 0.75rem; text-align:left;">Keterangan</th>
                        <th style="padding:0.5rem 0.75rem; text-align:left;">Wajib?</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #dbeafe;"><td style="padding:0.5rem 0.75rem;">Nama Lengkap</td><td style="padding:0.5rem 0.75rem;">Nama lengkap pegawai</td><td style="padding:0.5rem 0.75rem; color:#16a34a; font-weight:600;">Ya</td></tr>
                    <tr style="border-bottom:1px solid #dbeafe; background:#f0f9ff;"><td style="padding:0.5rem 0.75rem;">Username</td><td style="padding:0.5rem 0.75rem;">Username untuk login (unik)</td><td style="padding:0.5rem 0.75rem; color:#16a34a; font-weight:600;">Ya</td></tr>
                    <tr style="border-bottom:1px solid #dbeafe;"><td style="padding:0.5rem 0.75rem;">NIP Lama</td><td style="padding:0.5rem 0.75rem;">NIP lama pegawai (opsional)</td><td style="padding:0.5rem 0.75rem; color:#6b7280;">Tidak</td></tr>
                    <tr style="border-bottom:1px solid #dbeafe; background:#f0f9ff;"><td style="padding:0.5rem 0.75rem;">NIP Baru</td><td style="padding:0.5rem 0.75rem;">NIP baru 18 digit (opsional)</td><td style="padding:0.5rem 0.75rem; color:#6b7280;">Tidak</td></tr>
                    <tr style="border-bottom:1px solid #dbeafe;"><td style="padding:0.5rem 0.75rem;">Jabatan</td><td style="padding:0.5rem 0.75rem;">Jabatan pegawai (opsional)</td><td style="padding:0.5rem 0.75rem; color:#6b7280;">Tidak</td></tr>
                    <tr style="background:#f0f9ff;"><td style="padding:0.5rem 0.75rem;">Password</td><td style="padding:0.5rem 0.75rem;">Password awal (default: password123)</td><td style="padding:0.5rem 0.75rem; color:#6b7280;">Tidak</td></tr>
                </tbody>
            </table>
        </div>
        <p style="font-size:0.82rem; color:#6b7280; margin-top:0.75rem;">⚠️ Baris pertama adalah header dan akan diabaikan secara otomatis.</p>
        <a href="{{ route('admin.users.template') }}" style="display:inline-flex; align-items:center; gap:0.4rem; margin-top:0.75rem; font-size:0.85rem; color:#1d4ed8; font-weight:600; text-decoration:none;">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download Template CSV
        </a>
    </div>

    {{-- Upload Form --}}
    <div class="card" style="padding:2rem;">
        <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data">
            @csrf
            <div id="dropzone" style="border:2px dashed var(--border); border-radius:var(--radius-md); padding:3rem 2rem; text-align:center; cursor:pointer; transition:all 0.2s; margin-bottom:1.5rem;">
                <svg width="48" height="48" fill="none" stroke="#9ca3af" viewBox="0 0 24 24" style="margin:0 auto 1rem; display:block;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                <p style="font-size:1rem; font-weight:500; color:var(--text-main); margin-bottom:0.5rem;">Klik atau drag & drop file CSV di sini</p>
                <p style="font-size:0.85rem; color:var(--text-muted);" id="file-name">Format: .csv | Maks. 2MB</p>
                <input type="file" name="csv_file" id="csv_file" accept=".csv,.txt" style="display:none;" onchange="showFileName(this)">
                <label for="csv_file" class="btn btn-outline" style="margin-top:1rem; cursor:pointer; display:inline-block;">Pilih File</label>
            </div>

            <div style="display:flex; gap:1rem; justify-content:flex-end;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="text-decoration:none;">Batal</a>
                <button type="submit" class="btn btn-primary" style="display:flex; align-items:center; gap:0.5rem;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Upload & Import
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showFileName(input) {
    const name = input.files[0]?.name;
    if (name) {
        document.getElementById('file-name').textContent = '✅ File dipilih: ' + name;
        document.getElementById('file-name').style.color = '#16a34a';
    }
}
const dropzone = document.getElementById('dropzone');
dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.style.borderColor = 'var(--primary)'; dropzone.style.background = '#f0f4ff'; });
dropzone.addEventListener('dragleave', () => { dropzone.style.borderColor = 'var(--border)'; dropzone.style.background = ''; });
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.style.borderColor = 'var(--border)';
    dropzone.style.background = '';
    const file = e.dataTransfer.files[0];
    if (file) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('csv_file').files = dt.files;
        showFileName(document.getElementById('csv_file'));
    }
});
</script>
@endsection
