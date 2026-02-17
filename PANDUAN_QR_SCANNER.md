# Panduan Penggunaan Sistem Presensi BPS Jambi

## Fitur Scan QR Code untuk Presensi

Sistem ini mendukung presensi menggunakan scan barcode/QR code pegawai. Berikut adalah panduan lengkapnya:

### 🎯 Cara Kerja Sistem

#### Untuk Admin/Petugas:
1. Login ke sistem menggunakan akun admin
2. Buka menu **"Scan QR"** di navigasi atas
3. Pilih kegiatan yang aktif dari dropdown
4. Sistem akan mengaktifkan kamera untuk scan QR code
5. Arahkan kamera ke QR code/barcode pada kartu pegawai
6. Sistem akan otomatis mencatat presensi pegawai

#### Untuk Pegawai:
1. Login ke sistem menggunakan akun pegawai
2. Pada dashboard, jika ada kegiatan dengan metode "Show My QR"
3. Klik tombol **"Tunjukkan QR Saya"**
4. QR code akan ditampilkan
5. Tunjukkan QR code ke petugas untuk di-scan

### 📋 Metode Presensi yang Tersedia

Sistem mendukung 3 metode presensi:

1. **Submit Location** - Pegawai mengirim lokasi GPS mereka
2. **Scan Admin QR** - Pegawai scan QR code yang ditampilkan admin
3. **Scan Employee QR** - Admin scan QR code/barcode pegawai ⭐ (Fokus utama)

### 🔧 Setup Database

Jalankan perintah berikut untuk setup database:

```bash
# Jalankan migrasi database
php artisan migrate

# Seed data contoh (user dan kegiatan)
php artisan db:seed
```

### 👥 Akun Default

Setelah seeding, Anda dapat login dengan akun berikut:

**Admin:**
- Username: `admin`
- Password: `admin123`

**Pegawai Contoh:**
- Username: `roypradana` (NIP: 340057846)
- Password: `password123`

- Username: `budisantoso` (NIP: 340057847)
- Password: `password123`

- Username: `sitinurhaliza` (NIP: 340057848)
- Password: `password123`

### 📱 Cara Menggunakan Fitur Scan QR

#### Skenario 1: Admin Scan Barcode Pegawai

1. **Persiapan:**
   - Pastikan ada kegiatan aktif dengan metode "scan_qr"
   - Login sebagai admin

2. **Proses Scan:**
   - Buka halaman `/admin/scan-qr`
   - Pilih kegiatan dari dropdown
   - Izinkan akses kamera ketika diminta browser
   - Arahkan kamera ke barcode/QR code pegawai
   - Sistem akan otomatis membaca dan mencatat presensi

3. **Hasil:**
   - Modal sukses akan muncul menampilkan nama dan NIP pegawai
   - Presensi tercatat di database
   - Riwayat presensi muncul di panel kanan

#### Skenario 2: Pegawai Menampilkan QR Code

1. **Persiapan:**
   - Login sebagai pegawai
   - Pastikan ada kegiatan aktif dengan metode "scan_qr"

2. **Menampilkan QR:**
   - Pada dashboard, klik tombol "Tunjukkan QR Saya"
   - QR code akan ditampilkan berisi NIP pegawai
   - Tunjukkan ke petugas untuk di-scan

### 🔍 Format Barcode/QR Code

Sistem membaca barcode/QR code yang berisi **NIP Lama** pegawai. Contoh:
- `340057846` (Roy Pradana)
- `340057847` (Budi Santoso)
- `340057848` (Siti Nurhaliza)

### 📊 Database Schema

**Table: attendances**
- `user_id` - ID pegawai
- `attendance_session_id` - ID kegiatan
- `status` - Status presensi (default: 'present')
- `method_used` - Metode yang digunakan ('scan_qr', 'location', 'share_qr')
- `captured_at` - Waktu presensi dicatat
- `lat`, `lng` - Koordinat (opsional, untuk metode location)

### 🚀 Menjalankan Aplikasi

```bash
# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Setup database
php artisan migrate --seed

# Run development server
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

### 📸 Library yang Digunakan

- **html5-qrcode** (v2.3.8) - Untuk scan QR code/barcode menggunakan kamera
- **qrcode.js** (v1.0.0) - Untuk generate QR code pegawai

### ⚙️ Konfigurasi Scanner

Scanner dikonfigurasi dengan:
- FPS: 10 (frame per second)
- QR Box Size: 250x250 pixels
- Aspect Ratio: 1.0
- Camera: Environment (kamera belakang pada mobile)
- Error Correction Level: High (untuk QR code pegawai)

### 🔒 Validasi Presensi

Sistem melakukan validasi:
1. ✅ Kegiatan harus aktif (waktu sekarang antara start_time dan end_time)
2. ✅ Metode presensi harus sesuai (scan_qr)
3. ✅ Pegawai harus terdaftar di database
4. ✅ Pegawai belum presensi untuk kegiatan yang sama (tidak boleh duplikat)

### 🎨 Fitur UI/UX

- Real-time camera preview
- Auto-detect dan scan QR code
- Modal sukses/error dengan animasi
- Riwayat presensi real-time
- Responsive design
- Glassmorphism effects

### 📝 Catatan Penting

1. Browser harus mendukung getUserMedia API untuk akses kamera
2. Pastikan HTTPS jika deploy ke production (requirement untuk camera access)
3. QR code/barcode harus jelas dan tidak blur untuk hasil scan optimal
4. Pencahayaan yang baik membantu proses scanning

### 🐛 Troubleshooting

**Kamera tidak bisa diakses:**
- Pastikan browser memiliki permission untuk akses kamera
- Gunakan HTTPS (bukan HTTP) di production
- Cek apakah kamera sedang digunakan aplikasi lain

**QR code tidak terbaca:**
- Pastikan pencahayaan cukup
- Jaga jarak optimal (15-30 cm)
- Pastikan QR code tidak blur atau rusak

**Presensi tidak tercatat:**
- Cek apakah kegiatan masih aktif
- Pastikan metode kegiatan adalah "scan_qr"
- Cek apakah NIP pegawai ada di database

---

Dibuat dengan ❤️ untuk BPS Provinsi Jambi
