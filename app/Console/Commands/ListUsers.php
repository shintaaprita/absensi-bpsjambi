<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class ListUsers extends Command
{
    protected $signature = 'user:list';
    protected $description = 'List semua user beserta rolnya';

    public function handle()
    {
        $this->info('=== ROLES TERSEDIA ===');
        $roles = Role::all();
        foreach ($roles as $r) {
            $this->line("  ID: {$r->id} | {$r->name}");
        }

        $this->newLine();
        $this->info('=== DAFTAR USER ===');

        $headers = ['ID', 'Username', 'Nama', 'NIP Lama', 'Roles', 'Aktif'];
        $rows = User::with('roles')->get()->map(function ($u) {
            return [
                $u->id,
                $u->username,
                substr($u->name ?? $u->fullname, 0, 30),
                $u->nip_lama ?? '-',
                $u->roles->pluck('name')->join(', ') ?: '(none)',
                $u->is_active ? 'Ya' : 'Tidak',
            ];
        })->toArray();

        $this->table($headers, $rows);
    }
}
