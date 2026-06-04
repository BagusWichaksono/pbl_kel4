<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\App;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $developer = User::query()->where('email', 'developer@gmail.com')->first();
        $apps = App::query()->where('developer_id', $developer->id)->get();

        if (!$developer || $apps->isEmpty()) {
            return;
        }

        // DUMMY TRANSAKSI PENDING (MENUNGGU)
        Transaction::create([
            'user_id' => $developer->id,
            'application_id' => $apps->first()->id,
            'amount' => 150000.00,
            'payment_proof' => 'dummy_proof_transaction_1.jpg',
            'status' => 'pending',
            'created_at' => Carbon::now()->subHours(2),
            'updated_at' => Carbon::now()->subHours(2),
        ]);

        // DUMMY TRANSAKSI VALID (LUNAS)
        Transaction::create([
            'user_id' => $developer->id,
            'application_id' => $apps->last() ? $apps->last()->id : $apps->first()->id,
            'amount' => 300000.00,
            'payment_proof' => 'dummy_proof_transaction_2.jpg',
            'status' => 'valid',
            'created_at' => Carbon::now()->subDays(1),
            'updated_at' => Carbon::now()->subDays(1),
        ]);

        // DUMMY TRANSAKSI INVALID (DITOLAK)
        Transaction::create([
            'user_id' => $developer->id,
            'application_id' => $apps->count() > 1 ? $apps[1]->id : $apps->first()->id,
            'amount' => 50000.00,
            'payment_proof' => 'dummy_proof_transaction_3.jpg',
            'status' => 'invalid',
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2),
        ]);
    }
}
