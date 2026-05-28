<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── 1. Buat Lembaga ─────────────────────────────────────────────────
        $lembagaData = [
            ['nama' => 'TPQ Darus Sholah',    'jenis' => 'TPQ',   'kepala' => '-'],
            ['nama' => 'KB/TK Darus Sholah',  'jenis' => 'KB_TK', 'kepala' => '-'],
            ['nama' => 'SD Darus Sholah',     'jenis' => 'SD',    'kepala' => '-'],
            ['nama' => 'SMP Darus Sholah',    'jenis' => 'SMP',   'kepala' => '-'],
            ['nama' => 'SMA Darus Sholah',    'jenis' => 'SMA',   'kepala' => '-'],
            ['nama' => 'MA Darus Sholah',     'jenis' => 'MA',    'kepala' => '-'],
        ];

        $lembagaList = [];
        foreach ($lembagaData as $data) {
            $lembagaList[$data['jenis']] = Lembaga::create($data);
        }

        // ─── 2. Super Admin ──────────────────────────────────────────────────
        User::create([
            'name'                 => 'Super Admin',
            'username'             => 'superadmin',
            'password'             => Hash::make('digidas2025'),
            'role'                 => User::ROLE_SUPER_ADMIN,
            'lembaga_id'           => null,
            'must_change_password' => false,
        ]);

        // ─── 3. Admin per Lembaga ────────────────────────────────────────────
        $adminData = [
            ['username' => 'admin.tpq',  'name' => 'Admin TPQ',   'jenis' => 'TPQ'],
            ['username' => 'admin.kbtk', 'name' => 'Admin KB/TK', 'jenis' => 'KB_TK'],
            ['username' => 'admin.sd',   'name' => 'Admin SD',    'jenis' => 'SD'],
            ['username' => 'admin.smp',  'name' => 'Admin SMP',   'jenis' => 'SMP'],
            ['username' => 'admin.sma',  'name' => 'Admin SMA',   'jenis' => 'SMA'],
            ['username' => 'admin.ma',   'name' => 'Admin MA',    'jenis' => 'MA'],
        ];

        foreach ($adminData as $admin) {
            User::create([
                'name'                 => $admin['name'],
                'username'             => $admin['username'],
                'password'             => Hash::make('digidas2025'),
                'role'                 => User::ROLE_ADMIN_LEMBAGA,
                'lembaga_id'           => $lembagaList[$admin['jenis']]->id,
                'must_change_password' => true, // harus ganti password saat login pertama
            ]);
        }

        $this->command->info('✅ Seeder selesai!');
        $this->command->table(
            ['Username', 'Role', 'Lembaga'],
            [
                ['superadmin', 'Super Admin', '-'],
                ['admin.tpq', 'Admin Lembaga', 'TPQ Darus Sholah'],
                ['admin.kbtk', 'Admin Lembaga', 'KB/TK Darus Sholah'],
                ['admin.sd', 'Admin Lembaga', 'SD Darus Sholah'],
                ['admin.smp', 'Admin Lembaga', 'SMP Darus Sholah'],
                ['admin.sma', 'Admin Lembaga', 'SMA Darus Sholah'],
                ['admin.ma', 'Admin Lembaga', 'MA Darus Sholah'],
            ]
        );
        $this->command->comment('Password default semua akun: digidas2025');
    }
}
