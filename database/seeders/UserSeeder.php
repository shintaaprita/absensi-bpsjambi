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
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administrator']
        );

        $employeeRole = Role::firstOrCreate(
            ['name' => 'employee'],
            ['description' => 'Employee']
        );

        // Create admin user
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator',
                'fullname' => 'Administrator System',
                'email' => 'admin@bps.go.id',
                'password' => Hash::make('admin123'),
                'nip_lama' => '199001012015011001',
                'nip_baru' => '199001012015011001',
                'satker_kd' => '1600',
                'jabatan' => 'Administrator',
                'is_active' => true,
            ]
        );
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);

        // Create sample employee based on the barcode image (Roy Pradana)
        // The barcode appears to contain NIP information
        $employee1 = User::firstOrCreate(
            ['nip_lama' => '340057846'],
            [
                'name' => 'Roy Pradana',
                'fullname' => 'Roy Pradana',
                'username' => 'roypradana',
                'email' => 'roy.pradana@bps.go.id',
                'password' => Hash::make('password123'),
                'nip_baru' => '199505152020121001',
                'satker_kd' => '1600',
                'jabatan' => 'Statistisi Ahli Pertama',
                'is_active' => true,
            ]
        );
        $employee1->roles()->syncWithoutDetaching([$employeeRole->id]);

        // Create more sample employees
        $employee2 = User::firstOrCreate(
            ['nip_lama' => '340057847'],
            [
                'name' => 'Budi Santoso',
                'fullname' => 'Budi Santoso',
                'username' => 'budisantoso',
                'email' => 'budi.santoso@bps.go.id',
                'password' => Hash::make('password123'),
                'nip_baru' => '199203102019031002',
                'satker_kd' => '1600',
                'jabatan' => 'Analis Data',
                'is_active' => true,
            ]
        );
        $employee2->roles()->syncWithoutDetaching([$employeeRole->id]);

        $employee3 = User::firstOrCreate(
            ['nip_lama' => '340057848'],
            [
                'name' => 'Siti Nurhaliza',
                'fullname' => 'Siti Nurhaliza',
                'username' => 'sitinurhaliza',
                'email' => 'siti.nurhaliza@bps.go.id',
                'password' => Hash::make('password123'),
                'nip_baru' => '199408202018022001',
                'satker_kd' => '1600',
                'jabatan' => 'Statistisi Ahli Muda',
                'is_active' => true,
            ]
        );
        $employee3->roles()->syncWithoutDetaching([$employeeRole->id]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Admin: username=admin, password=admin123');
        $this->command->info('Employee 1 (Roy Pradana): NIP=340057846, password=password123');
        $this->command->info('Employee 2 (Budi Santoso): NIP=340057847, password=password123');
        $this->command->info('Employee 3 (Siti Nurhaliza): NIP=340057848, password=password123');
    }
}
