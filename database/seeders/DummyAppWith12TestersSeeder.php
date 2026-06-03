<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\App;
use App\Models\User;
use App\Models\ApplicationTester;
use Carbon\Carbon;

class DummyAppWith12TestersSeeder extends Seeder
{
    public function run()
    {
        // Cari developer, jika tidak ada buat baru
        $developer = User::where('role', 'developer')->first();
        if (!$developer) {
            $developer = User::create([
                'name' => 'Developer',
                'email' => 'developer_dummy@gmail.com',
                'password' => bcrypt('password123'),
                'role' => 'developer',
            ]);
        }

        // Buat aplikasi dummy
        $app = App::create([
            'developer_id' => $developer->id,
            'title' => 'Aplikasi Dummy 12 Tester',
            'platform' => 'Android',
            'app_link' => 'https://play.google.com/store/apps/details?id=com.dummy',
            'description' => 'Aplikasi dummy untuk mencoba fitur validasi, sudah berisi 12 tester terdaftar.',
            'payment_status' => 'approved',
            'testing_status' => 'in_progress',
            'max_testers' => 12,
            'start_date' => Carbon::now()->subDays(14),
            'end_date' => Carbon::now(),
        ]);

        // Cari 12 tester
        $testers = User::where('role', 'tester')->take(12)->get();
        
        // Buat kekurangannya jika tester kurang dari 12
        if ($testers->count() < 12) {
            for ($i = $testers->count(); $i < 12; $i++) {
                $user = User::create([
                    'name' => 'Dummy Tester ' . ($i + 1),
                    'email' => 'dummy_tester_' . ($i + 1) . '@gmail.com',
                    'password' => bcrypt('password123'),
                    'role' => 'tester',
                ]);
                $user->testerProfile()->create(['points' => 0]);
                $testers->push($user);
            }
        }

        // Daftarkan 12 tester ke aplikasi dan buat 14 laporan harian
        foreach ($testers as $tester) {
            ApplicationTester::create([
                'application_id' => $app->id,
                'tester_id' => $tester->id,
                'status' => 'active',
            ]);

            for ($day = 14; $day >= 1; $day--) {
                \App\Models\DailyReport::create([
                    'tester_id' => $tester->id,
                    'app_id' => $app->id,
                    'report_date' => Carbon::now()->subDays($day)->toDateString(),
                    'screenshot' => 'daily-reports/dummy.jpg', // asumsi ada file ini atau biarkan text
                    'notes' => 'Catatan harian untuk hari ke-' . (15 - $day),
                    'bug_report' => rand(1, 10) > 8 ? 'Ditemukan bug kecil.' : null,
                ]);
            }
        }
    }
}
