<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // sesuaikan dengan model user kamu
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan role super_admin ada
        $role = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);

        // Cari user pertama (atau user dengan email tertentu)
        $user = User::first(); // bisa juga: User::where('email', 'admin@domain.com')->first();

        if ($user) {
            $user->assignRole($role);
            $this->command->info("✅ User {$user->email} sudah jadi Super Admin.");
        } else {
            $this->command->warn("⚠️ Tidak ada user ditemukan, buat user dulu sebelum assign role.");
        }
    }
}
