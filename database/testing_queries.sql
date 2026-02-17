-- Query untuk Testing Database Presensi QR Code

-- 1. Lihat semua presensi dengan metode scan_qr
SELECT 
    a.id,
    u.fullname AS nama_pegawai,
    u.nip_lama,
    s.title AS kegiatan,
    a.method_used AS metode,
    a.captured_at AS waktu_presensi,
    a.created_at
FROM attendances a
JOIN users u ON a.user_id = u.id
JOIN attendance_sessions s ON a.attendance_session_id = s.id
WHERE a.method_used = 'scan_qr'
ORDER BY a.captured_at DESC;

-- 2. Lihat semua kegiatan aktif dengan metode scan_qr
SELECT 
    id,
    title,
    description,
    start_time,
    end_time,
    method,
    CASE 
        WHEN NOW() BETWEEN start_time AND end_time THEN 'AKTIF'
        WHEN NOW() < start_time THEN 'BELUM MULAI'
        ELSE 'SUDAH SELESAI'
    END AS status
FROM attendance_sessions
WHERE method = 'scan_qr'
ORDER BY start_time DESC;

-- 3. Lihat jumlah presensi per kegiatan
SELECT 
    s.title AS kegiatan,
    s.method AS metode,
    COUNT(a.id) AS jumlah_presensi,
    s.start_time,
    s.end_time
FROM attendance_sessions s
LEFT JOIN attendances a ON s.id = a.attendance_session_id
GROUP BY s.id, s.title, s.method, s.start_time, s.end_time
ORDER BY s.start_time DESC;

-- 4. Lihat presensi hari ini
SELECT 
    u.fullname AS nama_pegawai,
    u.nip_lama,
    s.title AS kegiatan,
    a.method_used AS metode,
    TIME(a.captured_at) AS jam_presensi
FROM attendances a
JOIN users u ON a.user_id = u.id
JOIN attendance_sessions s ON a.attendance_session_id = s.id
WHERE DATE(a.captured_at) = CURDATE()
ORDER BY a.captured_at DESC;

-- 5. Lihat pegawai yang belum presensi untuk kegiatan tertentu
-- (Ganti 1 dengan ID kegiatan yang ingin dicek)
SELECT 
    u.id,
    u.fullname,
    u.nip_lama,
    u.jabatan
FROM users u
WHERE u.id NOT IN (
    SELECT user_id 
    FROM attendances 
    WHERE attendance_session_id = 1
)
AND u.is_active = 1
ORDER BY u.fullname;

-- 6. Statistik presensi per pegawai
SELECT 
    u.fullname AS nama_pegawai,
    u.nip_lama,
    COUNT(a.id) AS total_presensi,
    COUNT(CASE WHEN a.method_used = 'scan_qr' THEN 1 END) AS presensi_scan_qr,
    COUNT(CASE WHEN a.method_used = 'location' THEN 1 END) AS presensi_location,
    COUNT(CASE WHEN a.method_used = 'share_qr' THEN 1 END) AS presensi_share_qr
FROM users u
LEFT JOIN attendances a ON u.id = a.user_id
WHERE u.is_active = 1
GROUP BY u.id, u.fullname, u.nip_lama
ORDER BY total_presensi DESC;

-- 7. Cek duplikasi presensi (seharusnya tidak ada)
SELECT 
    user_id,
    attendance_session_id,
    COUNT(*) AS jumlah
FROM attendances
GROUP BY user_id, attendance_session_id
HAVING COUNT(*) > 1;

-- 8. Lihat detail lengkap satu presensi
SELECT 
    a.*,
    u.fullname AS nama_pegawai,
    u.nip_lama,
    u.nip_baru,
    u.jabatan,
    s.title AS kegiatan,
    s.description AS deskripsi_kegiatan,
    s.method AS metode_kegiatan,
    s.start_time,
    s.end_time
FROM attendances a
JOIN users u ON a.user_id = u.id
JOIN attendance_sessions s ON a.attendance_session_id = s.id
ORDER BY a.created_at DESC
LIMIT 10;

-- 9. Lihat semua user yang bisa di-scan (pegawai aktif)
SELECT 
    id,
    fullname,
    nip_lama,
    nip_baru,
    jabatan,
    email,
    is_active
FROM users
WHERE is_active = 1
ORDER BY fullname;

-- 10. Testing: Insert presensi manual (untuk testing)
-- INSERT INTO attendances (user_id, attendance_session_id, status, method_used, captured_at, created_at, updated_at)
-- VALUES (
--     2, -- user_id (Roy Pradana)
--     1, -- attendance_session_id (Apel Pagi)
--     'present',
--     'scan_qr',
--     NOW(),
--     NOW(),
--     NOW()
-- );

-- 11. Testing: Hapus semua presensi (untuk reset testing)
-- DELETE FROM attendances;

-- 12. Testing: Hapus presensi tertentu
-- DELETE FROM attendances WHERE id = 1;

-- 13. Lihat log presensi dengan informasi waktu relatif
SELECT 
    a.id,
    u.fullname AS nama_pegawai,
    s.title AS kegiatan,
    a.captured_at AS waktu_presensi,
    TIMESTAMPDIFF(MINUTE, a.captured_at, NOW()) AS menit_yang_lalu,
    CASE 
        WHEN TIMESTAMPDIFF(MINUTE, a.captured_at, NOW()) < 60 THEN 
            CONCAT(TIMESTAMPDIFF(MINUTE, a.captured_at, NOW()), ' menit yang lalu')
        WHEN TIMESTAMPDIFF(HOUR, a.captured_at, NOW()) < 24 THEN 
            CONCAT(TIMESTAMPDIFF(HOUR, a.captured_at, NOW()), ' jam yang lalu')
        ELSE 
            CONCAT(TIMESTAMPDIFF(DAY, a.captured_at, NOW()), ' hari yang lalu')
    END AS waktu_relatif
FROM attendances a
JOIN users u ON a.user_id = u.id
JOIN attendance_sessions s ON a.attendance_session_id = s.id
ORDER BY a.captured_at DESC
LIMIT 20;

-- 14. Export data presensi untuk laporan (format CSV-friendly)
SELECT 
    DATE(a.captured_at) AS tanggal,
    TIME(a.captured_at) AS jam,
    u.nip_lama AS nip,
    u.fullname AS nama,
    u.jabatan,
    s.title AS kegiatan,
    a.method_used AS metode,
    a.status
FROM attendances a
JOIN users u ON a.user_id = u.id
JOIN attendance_sessions s ON a.attendance_session_id = s.id
WHERE a.method_used = 'scan_qr'
ORDER BY a.captured_at DESC;
