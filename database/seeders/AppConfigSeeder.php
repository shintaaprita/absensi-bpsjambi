<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SatkerSetting;
use App\Models\WorkingSchedule;
use App\Models\Holiday;

class AppConfigSeeder extends Seeder
{
    public function run(): void
    {
        $satkerCode = '1500'; // BPS Provinsi Jambi

        // 1. Satker Settings
        $settings = [
            ['key' => 'folder_foto', 'value' => '1cYii-7kblAcrIZhouaIDYBDSQAR1mTzf', 'description' => 'ID folder foto profil'],
            ['key' => 'folder_presensi', 'value' => '1M4PIRvixOkMl8pf84VJ2u5J30HJ_QTts', 'description' => 'ID folder hasil foto presensi'],
            ['key' => 'email_admin', 'value' => 'admin@example.com', 'description' => 'Email admin'],
            ['key' => 'timezone', 'value' => 'Asia/Jakarta', 'description' => 'Timezone'],
            ['key' => 'lokasi_kantor_lat', 'value' => '-1.6067516', 'description' => 'Latitude kantor'],
            ['key' => 'lokasi_kantor_lon', 'value' => '103.5829975', 'description' => 'Longitude kantor'],
            ['key' => 'radius_validasi', 'value' => '100', 'description' => 'Radius validasi (meter)'],
            ['key' => 'foto_requirement', 'value' => 'TRUE', 'description' => 'Wajib foto?'],
            ['key' => 'global_wfa', 'value' => 'FALSE', 'description' => 'WFA untuk seluruh pegawai?'],
        ];

        foreach ($settings as $setting) {
            SatkerSetting::updateOrCreate(
                ['satker_code' => $satkerCode, 'key' => $setting['key']],
                $setting
            );
        }

        // 2. Working Schedules
        $days = [
            ['day' => 'Monday', 'in' => '07:30', 'out' => '16:00', 'overtime' => '18:00'],
            ['day' => 'Tuesday', 'in' => '07:30', 'out' => '16:00', 'overtime' => '18:00'],
            ['day' => 'Wednesday', 'in' => '07:30', 'out' => '16:00', 'overtime' => '18:00'],
            ['day' => 'Thursday', 'in' => '07:30', 'out' => '16:00', 'overtime' => '18:00'],
            ['day' => 'Friday', 'in' => '07:30', 'out' => '16:30', 'overtime' => '18:30'],
            ['day' => 'Saturday', 'in' => null, 'out' => null, 'overtime' => null, 'working' => false],
            ['day' => 'Sunday', 'in' => null, 'out' => null, 'overtime' => null, 'working' => false],
        ];

        foreach ($days as $day) {
            WorkingSchedule::updateOrCreate(
                ['satker_code' => $satkerCode, 'day_name' => $day['day']],
                [
                    'clock_in' => $day['in'],
                    'clock_out' => $day['out'],
                    'overtime_limit' => $day['overtime'],
                    'is_working_day' => $day['working'] ?? true,
                ]
            );
        }

        // 3. Holidays 2025
        $holidays = [
            ['date' => '2025-01-01', 'desc' => 'Tahun Baru Masehi'],
            ['date' => '2025-01-27', 'desc' => 'Isra Mi’raj Nabi Muhammad SAW'],
            ['date' => '2025-01-29', 'desc' => 'Tahun Baru Imlek 2576 Kongzili'],
            ['date' => '2025-03-29', 'desc' => 'Hari Suci Nyepi (Tahun Baru Saka 1947)'],
            ['date' => '2025-03-31', 'desc' => 'Idul Fitri 1446 H (Hari Pertama)'],
            ['date' => '2025-04-01', 'desc' => 'Idul Fitri 1446 H (Hari Kedua)'],
            ['date' => '2025-04-18', 'desc' => 'Wafat Yesus Kristus (Jumat Agung)'],
            ['date' => '2025-04-20', 'desc' => 'Kebangkitan Yesus Kristus (Paskah)'],
            ['date' => '2025-05-01', 'desc' => 'Hari Buruh Internasional'],
            ['date' => '2025-05-12', 'desc' => 'Hari Raya Waisak 2569 BE'],
            ['date' => '2025-05-29', 'desc' => 'Kenaikan Yesus Kristus'],
            ['date' => '2025-06-01', 'desc' => 'Hari Lahir Pancasila'],
            ['date' => '2025-06-06', 'desc' => 'Idul Adha 1446 H'],
            ['date' => '2025-06-27', 'desc' => '1 Muharram 1447 H (Tahun Baru Islam)'],
            ['date' => '2025-08-17', 'desc' => 'Hari Kemerdekaan Republik Indonesia'],
            ['date' => '2025-09-05', 'desc' => 'Maulid Nabi Muhammad SAW'],
            ['date' => '2025-12-25', 'desc' => 'Hari Raya Natal'],
        ];

        foreach ($holidays as $h) {
            Holiday::updateOrCreate(
                ['holiday_date' => $h['date']],
                ['description' => $h['desc']]
            );
        }
    }
}
