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
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tester_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('app_id')->constrained('applications')->cascadeOnDelete(); // Sesuaikan nama tabel aplikasimu, misal 'applications'
            $table->date('report_date'); // Untuk mencatat tanggal lapor
            $table->string('screenshot'); // Bukti foto
            $table->text('notes')->nullable(); // Catatan singkat
            $table->timestamps();
        
        Schema::dropIfExists('daily_reports'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
