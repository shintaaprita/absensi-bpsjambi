# Panduan Testing Sistem Presensi QR Code

## 🧪 Skenario Testing

### Test 1: Login dan Akses Halaman Scan QR

**Langkah:**
1. Buka browser dan akses `http://localhost:8000`
2. Login dengan akun admin:
   - Username: `admin`
   - Password: `admin123`
3. Setelah login, klik menu **"Scan QR"** di navigasi atas
4. Halaman scan QR akan terbuka

**Expected Result:**
- ✅ Login berhasil dan redirect ke dashboard
- ✅ Menu "Scan QR" terlihat di navigasi
- ✅ Halaman scan QR menampilkan dropdown kegiatan dan panel riwayat presensi

---

### Test 2: Scan QR Code Pegawai (Simulasi)

**Langkah:**
1. Di halaman scan QR, pilih kegiatan "Apel Pagi" dari dropdown
2. Sistem akan meminta izin akses kamera - klik "Allow"
3. Arahkan kamera ke QR code/barcode pegawai (atau gunakan barcode image yang disediakan)
4. Scanner akan otomatis membaca kode

**Expected Result:**
- ✅ Kamera aktif dan menampilkan preview
- ✅ QR code terbaca otomatis
- ✅ Modal sukses muncul dengan nama dan NIP pegawai
- ✅ Presensi tercatat di panel "Presensi Terbaru"
- ✅ Data tersimpan di database table `attendances`

---

### Test 3: Pegawai Menampilkan QR Code

**Langkah:**
1. Logout dari akun admin
2. Login dengan akun pegawai:
   - Username: `roypradana`
   - Password: `password123`
3. Di dashboard, cari kegiatan dengan metode "Show My QR"
4. Klik tombol **"Tunjukkan QR Saya"**

**Expected Result:**
- ✅ Modal muncul menampilkan QR code
- ✅ QR code berisi NIP pegawai (340057846)
- ✅ Nama pegawai ditampilkan di atas QR code
- ✅ NIP ditampilkan di bawah QR code

---

### Test 4: Validasi Duplikasi Presensi

**Langkah:**
1. Login sebagai admin
2. Buka halaman scan QR
3. Pilih kegiatan "Apel Pagi"
4. Scan QR code pegawai yang sama 2 kali

**Expected Result:**
- ✅ Scan pertama: Berhasil, presensi tercatat
- ✅ Scan kedua: Error modal muncul dengan pesan "Pegawai sudah melakukan presensi untuk kegiatan ini"

---

### Test 5: Validasi Kegiatan Tidak Aktif

**Langkah:**
1. Buat kegiatan baru dengan waktu yang sudah lewat (kemarin)
2. Coba pilih kegiatan tersebut di halaman scan QR
3. Scan QR code pegawai

**Expected Result:**
- ✅ Error modal muncul dengan pesan "Sesi kegiatan tidak aktif"
- ✅ Presensi tidak tercatat

---

### Test 6: Validasi Pegawai Tidak Ditemukan

**Langkah:**
1. Buat QR code dengan NIP yang tidak ada di database (misal: 999999999)
2. Scan QR code tersebut

**Expected Result:**
- ✅ Error modal muncul dengan pesan "Pegawai tidak ditemukan. NIP: 999999999"
- ✅ Presensi tidak tercatat

---

### Test 7: Cek Data di Database

**Langkah:**
1. Buka database client (TablePlus, phpMyAdmin, dll)
2. Query table `attendances`:
   ```sql
   SELECT a.*, u.fullname, u.nip_lama, s.title 
   FROM attendances a
   JOIN users u ON a.user_id = u.id
   JOIN attendance_sessions s ON a.attendance_session_id = s.id
   WHERE a.method_used = 'scan_qr'
   ORDER BY a.captured_at DESC;
   ```

**Expected Result:**
- ✅ Data presensi tersimpan dengan lengkap
- ✅ Field `method_used` bernilai 'scan_qr'
- ✅ Field `captured_at` berisi timestamp yang benar
- ✅ Field `user_id` dan `attendance_session_id` terisi dengan benar

---

### Test 8: Responsive Design

**Langkah:**
1. Buka halaman scan QR di browser
2. Resize browser window ke ukuran mobile (375px width)
3. Test juga di device mobile sebenarnya

**Expected Result:**
- ✅ Layout menyesuaikan dengan baik
- ✅ Scanner tetap berfungsi di mobile
- ✅ Button dan text tetap readable

---

## 📊 Checklist Fitur

- [x] Login admin
- [x] Halaman scan QR dengan camera preview
- [x] Dropdown pilihan kegiatan aktif
- [x] Auto-detect dan scan QR code/barcode
- [x] Validasi kegiatan aktif
- [x] Validasi metode presensi
- [x] Validasi pegawai terdaftar
- [x] Validasi duplikasi presensi
- [x] Modal sukses dengan animasi
- [x] Modal error dengan pesan jelas
- [x] Panel riwayat presensi real-time
- [x] Data tersimpan ke database
- [x] Pegawai dapat menampilkan QR code sendiri
- [x] Generate QR code dengan library qrcode.js
- [x] Responsive design

---

## 🔍 Testing dengan Barcode Sample

Gunakan barcode image yang disediakan (Roy Pradana - NIP: 340057846):
1. Buka image barcode di device lain (smartphone/tablet)
2. Scan menggunakan kamera di halaman scan QR
3. Atau print barcode dan scan secara fisik

---

## 🐛 Bug Report Template

Jika menemukan bug, laporkan dengan format:

```
**Bug:** [Deskripsi singkat]
**Steps to Reproduce:**
1. ...
2. ...
3. ...

**Expected:** [Apa yang seharusnya terjadi]
**Actual:** [Apa yang benar-benar terjadi]
**Browser:** [Chrome/Firefox/Safari/Edge + versi]
**Screenshot:** [Jika ada]
```

---

## ✅ Testing Checklist

Gunakan checklist ini untuk memastikan semua fitur berfungsi:

### Admin Features
- [ ] Login sebagai admin
- [ ] Akses halaman scan QR
- [ ] Pilih kegiatan dari dropdown
- [ ] Kamera aktif dan preview muncul
- [ ] Scan QR code pegawai berhasil
- [ ] Modal sukses muncul dengan data benar
- [ ] Riwayat presensi update real-time
- [ ] Scan duplikat ditolak dengan error message
- [ ] Scan pegawai tidak terdaftar ditolak
- [ ] Logout berhasil

### Employee Features
- [ ] Login sebagai pegawai
- [ ] Lihat kegiatan aktif di dashboard
- [ ] Klik "Tunjukkan QR Saya"
- [ ] QR code muncul dengan benar
- [ ] QR code berisi NIP yang benar
- [ ] Tutup modal QR code
- [ ] Logout berhasil

### Database
- [ ] Data presensi tersimpan di table `attendances`
- [ ] Field `method_used` = 'scan_qr'
- [ ] Field `captured_at` terisi dengan benar
- [ ] Relasi ke `users` dan `attendance_sessions` benar
- [ ] Tidak ada duplikasi data

### UI/UX
- [ ] Design modern dan menarik
- [ ] Animasi smooth
- [ ] Loading state jelas
- [ ] Error message informatif
- [ ] Responsive di mobile
- [ ] Font readable
- [ ] Color contrast baik

---

**Happy Testing! 🚀**
