<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttendanceSession;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AttendanceSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample attendance sessions for today
        $now = Carbon::now();

        // 1. All-day session with scan_qr method (ACTIVE NOW - whole day)
        AttendanceSession::create([
            'satker_code' => '1500',
            'title' => 'Presensi Harian Pegawai',
            'description' => 'Presensi harian untuk semua pegawai BPS Jambi',
            'start_time' => Carbon::today()->setTime(6, 0, 0),
            'end_time' => Carbon::today()->setTime(23, 59, 0),
            'method' => 'scan_qr',
            'qr_token' => Str::random(32),
        ]);

        // 2. Current active session (from 1 hour ago to 2 hours from now)
        AttendanceSession::create([
            'satker_code' => '1500',
            'title' => 'Rapat Koordinasi Tim',
            'description' => 'Rapat koordinasi tim bulanan',
            'start_time' => $now->copy()->subHour(),
            'end_time' => $now->copy()->addHours(2),
            'method' => 'scan_qr',
            'qr_token' => Str::random(32),
        ]);

        // 3. Location-based session (active now)
        AttendanceSession::create([
            'satker_code' => '1500',
            'title' => 'Survei Lapangan',
            'description' => 'Kegiatan survei di lapangan',
            'start_time' => $now->copy()->subMinutes(30),
            'end_time' => $now->copy()->addHours(3),
            'method' => 'location',
            'location_name' => 'Kantor BPS Provinsi Jambi',
            'latitude' => -1.6101229,
            'longitude' => 103.6131203,
            'radius' => 50000, // 50km radius for testing (was 100m)
            'qr_token' => Str::random(32),
        ]);

        // 4. Share QR method (active now)
        AttendanceSession::create([
            'satker_code' => '1500',
            'title' => 'Pelatihan Internal',
            'description' => 'Pelatihan penggunaan aplikasi statistik',
            'start_time' => $now->copy()->subMinutes(15),
            'end_time' => $now->copy()->addHours(2),
            'method' => 'share_qr',
            'qr_token' => Str::random(32),
        ]);

        // 5. Upcoming session (1 hour from now)
        AttendanceSession::create([
            'satker_code' => '1500',
            'title' => 'Apel Sore',
            'description' => 'Apel sore evaluasi harian',
            'start_time' => $now->copy()->addHour(),
            'end_time' => $now->copy()->addHours(3),
            'method' => 'scan_qr',
            'qr_token' => Str::random(32),
        ]);

        // 6. Tomorrow's morning session
        AttendanceSession::create([
            'satker_code' => '1500',
            'title' => 'Apel Pagi Besok',
            'description' => 'Apel pagi untuk hari besok',
            'start_time' => Carbon::tomorrow()->setTime(7, 0, 0),
            'end_time' => Carbon::tomorrow()->setTime(14, 0, 0),
            'method' => 'scan_qr',
            'qr_token' => Str::random(32),
        ]);

        $this->command->info('Attendance sessions seeded successfully!');
        $this->command->info('Active sessions created for current time.');
    }
}
