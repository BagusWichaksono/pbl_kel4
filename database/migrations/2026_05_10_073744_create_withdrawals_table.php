<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('withdrawals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tester_id')->constrained('users')->cascadeOnDelete();
        $table->integer('points_withdrawn'); // Berapa poin yang ditukar
        $table->integer('amount_rp'); // Konversi ke Rupiah
        $table->string('e_wallet_provider'); // Dana, GoPay, OVO, ShopeePay
        $table->string('e_wallet_number'); // Nomor HP E-Wallet
        $table->string('status')->default('pending'); // pending, approved, rejected
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
