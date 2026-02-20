<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if not exists
        $adminRole = Role::updateOrCreate(['name' => 'admin'], ['description' => 'Administrator']);
        $pimpinanRole = Role::updateOrCreate(['name' => 'pimpinan'], ['description' => 'Pimpinan / Pemantau']);
        $operatorRole = Role::updateOrCreate(['name' => 'operator'], ['description' => 'Operator']);
        $pegawaiRole = Role::updateOrCreate(['name' => 'pegawai'], ['description' => 'Pegawai']);
        $magangRole = Role::updateOrCreate(['name' => 'magang'], ['description' => 'Magang']);

        // Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@bps.go.id'],
            [
                'username' => 'admin',
                'name' => 'Administrator',
                'fullname' => 'Administrator System',
                'password' => Hash::make('admin123'),
                'nip_lama' => '199001012015011001',
                'nip_baru' => '199001012015011001',
                'satker_kd' => '1500',
                'jabatan' => 'Administrator',
                'is_active' => true,
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Sample employees are removed as per request to clear users.
        // Users will be populated via SSO login.
        
        $this->command->info('Users cleared. Admin account created: username=admin, password=admin123');
    }
}
