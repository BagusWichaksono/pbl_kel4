<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\App;
use App\Models\User;
use Carbon\Carbon;

class AppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = User::where('email', 'developer@gmail.com')->first();

        if (!$developer) {
            return;
        }

        App::create([
            'developer_id' => $developer->id,
            'title' => 'Aplikasi Testing Selesai',
            'platform' => 'Android',
            'description' => 'Aplikasi ini digunakan untuk simulasi closed testing yang sudah selesai.',
            'payment_proof' => null,
            'payment_status' => 'approved',
            'testing_status' => 'completed',
            'review_screenshot' => null,
            'max_testers' => 12,
            'start_date' => Carbon::now()->subDays(14),
            'end_date' => Carbon::now(),
        ]);

        App::create([
            'developer_id' => $developer->id,
            'title' => 'Aplikasi Testing Berjalan',
            'platform' => 'Android',
            'description' => 'Aplikasi ini digunakan untuk simulasi closed testing yang sedang berjalan.',
            'payment_proof' => null,
            'payment_status' => 'approved',
            'testing_status' => 'active',
            'review_screenshot' => null,
            'max_testers' => 12,
            'start_date' => Carbon::now()->subDay(),
            'end_date' => Carbon::now()->subDay()->addDays(14),
        ]);

        App::create([
            'developer_id' => $developer->id,
            'title' => 'Aplikasi Testing Baru',
            'platform' => 'Android',
            'description' => 'Aplikasi ini digunakan untuk simulasi closed testing yang baru dimulai.',
            'payment_proof' => null,
            'payment_status' => 'approved',
            'testing_status' => 'active',
            'review_screenshot' => null,
            'max_testers' => 12,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(14),
        ]);
    }
}
