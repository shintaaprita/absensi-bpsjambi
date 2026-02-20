<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $satkers = [
            ['code' => '1500', 'name' => 'BPS Provinsi Jambi', 'email' => 'bps1500@bps.go.id'],
            ['code' => '1501', 'name' => 'BPS Kabupaten Kerinci', 'email' => 'bps1501@bps.go.id'],
            ['code' => '1502', 'name' => 'BPS Kabupaten Merangin', 'email' => 'bps1502@bps.go.id'],
            ['code' => '1503', 'name' => 'BPS Kabupaten Sarolangun', 'email' => 'bps1503@bps.go.id'],
            ['code' => '1504', 'name' => 'BPS Kabupaten Batang Hari', 'email' => 'bps1504@bps.go.id'],
            ['code' => '1505', 'name' => 'BPS Kabupaten Muaro Jambi', 'email' => 'bps1505@bps.go.id'],
            ['code' => '1506', 'name' => 'BPS Kabupaten Tanjung Jabung Timur', 'email' => 'bps1506@bps.go.id'],
            ['code' => '1507', 'name' => 'BPS Kabupaten Tanjung Jabung Barat', 'email' => 'bps1507@bps.go.id'],
            ['code' => '1508', 'name' => 'BPS Kabupaten Tebo', 'email' => 'bps1508@bps.go.id'],
            ['code' => '1509', 'name' => 'BPS Kabupaten Bungo', 'email' => 'bps1509@bps.go.id'],
            ['code' => '1571', 'name' => 'BPS Kota Jambi', 'email' => 'bps1571@bps.go.id'],
            ['code' => '1572', 'name' => 'BPS Kota Sungai Penuh', 'email' => 'bps1572@bps.go.id'],
        ];

        foreach ($satkers as $satker) {
            \App\Models\Satker::updateOrCreate(['code' => $satker['code']], $satker);
        }
    }
}
