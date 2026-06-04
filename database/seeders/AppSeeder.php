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
        $developer = User::query()->where('email', 'developer@gmail.com')->first();

        if (!$developer) {
            return;
        }

        App::create([
            'developer_id' => $developer->id,
            'title' => 'Aplikasi Testing Selesai',
            'platform' => 'Android',
            'app_link' => 'https://google.com',
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
            'app_link' => 'https://google.com',
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
            'app_link' => 'https://google.com',
            'description' => 'Aplikasi ini digunakan untuk simulasi closed testing yang baru dimulai.',
            'payment_proof' => null,
            'payment_status' => 'approved',
            'testing_status' => 'active',
            'review_screenshot' => null,
            'max_testers' => 12,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(14),
        ]);

        // DUMMY DATA UNTUK TRANSAKSI PEMBAYARAN DEVELOPER
        App::create([
            'developer_id' => $developer->id,
            'title' => 'Aplikasi Menunggu Validasi Pembayaran',
            'platform' => 'Web',
            'app_link' => 'https://example.com',
            'description' => 'Aplikasi ini baru saja dibayar dan sedang menunggu konfirmasi admin.',
            'payment_proof' => 'dummy_proof_1.jpg',
            'payment_status' => 'pending',
            'testing_status' => 'pending_approval',
            'max_testers' => 10,
            'start_date' => null,
            'end_date' => null,
        ]);

        App::create([
            'developer_id' => $developer->id,
            'title' => 'Aplikasi Pembayaran Ditolak',
            'platform' => 'iOS',
            'app_link' => 'https://apple.com',
            'description' => 'Aplikasi ini pembayarannya ditolak karena bukti transfer buram.',
            'payment_proof' => 'dummy_proof_2.jpg',
            'payment_status' => 'invalid',
            'testing_status' => 'pending_approval',
            'max_testers' => 20,
            'start_date' => null,
            'end_date' => null,
        ]);

        App::create([
            'developer_id' => $developer->id,
            'title' => 'Aplikasi Pembayaran Berhasil',
            'platform' => 'Android',
            'app_link' => 'https://google.com',
            'description' => 'Aplikasi ini pembayarannya berhasil divalidasi.',
            'payment_proof' => 'dummy_proof_3.jpg',
            'payment_status' => 'valid',
            'testing_status' => 'open',
            'max_testers' => 15,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(14),
        ]);
    }
}
