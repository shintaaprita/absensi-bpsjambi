<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class AssignUserRoles extends Command
{
    protected $signature = 'user:assign-roles
                            {identifier : Username, NIP, atau email user}
                            {roles* : Nama role yang akan diberikan (admin, pegawai, pimpinan, operator, magang)}';

    protected $description = 'Assign satu atau lebih role ke user berdasarkan username, NIP, atau email';

    public function handle()
    {
        $identifier = $this->argument('identifier');
        $roleNames  = $this->argument('roles');

        // Find user
        $user = User::where('username', $identifier)
            ->orWhere('nip_lama', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$user) {
            $this->error("User dengan identifier '{$identifier}' tidak ditemukan.");
            return 1;
        }

        $this->info("User ditemukan: {$user->name} (ID: {$user->id})");

        // Find roles
        $roleIds = [];
        foreach ($roleNames as $roleName) {
            $role = Role::whereRaw('LOWER(name) = ?', [strtolower(trim($roleName))])->first();
            if (!$role) {
                $this->warn("  Role '{$roleName}' tidak ditemukan, dilewati.");
                continue;
            }
            $roleIds[] = $role->id;
            $this->line("  ✓ Role <fg=green>{$role->name}</fg=green> (ID: {$role->id}) akan diberikan");
        }

        if (empty($roleIds)) {
            $this->error('Tidak ada role valid yang ditemukan. Pastikan seeder roles sudah dijalankan.');
            return 1;
        }

        // Sync roles (add without removing existing)
        $user->roles()->syncWithoutDetaching($roleIds);
        $user->refresh()->load('roles');

        $this->newLine();
        $this->info("✅ Berhasil! Role sekarang untuk {$user->name}:");
        foreach ($user->roles as $r) {
            $this->line("   - {$r->name} (ID: {$r->id})");
        }

        return 0;
    }
}
