<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // KITA HAPUS/COMMENT BAWAAN LARAVEL INI
        // User::factory(10)->create();
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // KITA BUAT AKUN CUSTOM SESUAI ROLE KITA:

        // 1. Akun SuperAdmin
        User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('superadmin'),
            'role' => 'super_admin',
        ]);

        // 2. Akun Admin
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        // 3. Akun Developer
        User::create([
            'name' => 'developer',
            'email' => 'developer@gmail.com',
            'password' => Hash::make('developer'),
            'role' => 'developer',
        ]);

        // 4. Akun Tester
        User::create([
            'name' => 'tester',
            'email' => 'tester@gmail.com',
            'password' => Hash::make('tester'),
            'role' => 'tester',
        ]);
    }
}