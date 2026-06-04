<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PointHistory;
use App\Models\User;

class PointHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tester = User::where('role', '=', 'tester', 'and')->first();

        if ($tester) {
            PointHistory::create([
                'tester_id' => $tester->id,
                'amount' => 10,
                'type' => 'credit',
                'description' => 'Mendapatkan poin dari pengujian aplikasi: Mobile Legends',
                'created_at' => now()->subDays(5),
            ]);

            PointHistory::create([
                'tester_id' => $tester->id,
                'amount' => 10,
                'type' => 'credit',
                'description' => 'Mendapatkan poin dari pengujian aplikasi: PUBG Mobile',
                'created_at' => now()->subDays(3),
            ]);

            PointHistory::create([
                'tester_id' => $tester->id,
                'amount' => 10,
                'type' => 'debit',
                'description' => 'Penarikan dana dengan invoice: INV-20260601-ABCD',
                'created_at' => now()->subDays(1),
            ]);
            
            PointHistory::create([
                'tester_id' => $tester->id,
                'amount' => 10,
                'type' => 'credit',
                'description' => 'Pengembalian poin karena penarikan ditolak (Invoice: INV-20260601-ABCD)',
                'created_at' => now()->subHours(5),
            ]);
        }
    }
}
