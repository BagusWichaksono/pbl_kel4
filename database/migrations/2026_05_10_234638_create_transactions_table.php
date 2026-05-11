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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel 'users'
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Menghubungkan ke tabel 'applications' (sesuai isi model App kamu)
            $table->foreignId('application_id')->nullable()->constrained('applications')->onDelete('set null');
            
            $table->decimal('amount', 15, 2);
            $table->string('payment_proof')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
