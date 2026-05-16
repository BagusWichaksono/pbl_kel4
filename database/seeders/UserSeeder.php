<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('password123'), // Password default
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Admin Sistem',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Developer',
            'email' => 'developer@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'developer',
        ]);

        User::create([
            'name' => 'Tester',
            'email' => 'tester@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'tester',
        ]);

        // Generate 20 tester
        for ($i = 1; $i <= 20; $i++) {

            $user = User::create([
                'name' => 'Tester ' . $i,
                'email' => 'tester' . $i . '@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'tester',
            ]);

            // Otomatis buat tester_profile
            $user->testerProfile()->create([
                'points' => rand(5, 50), // 5 - 50 poin
            ]);
        }
    }
}
