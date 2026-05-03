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
    Schema::create('testing_reports', function (Blueprint $table) {
        $table->id();
        // Mengaitkan bukti ini dengan tugas tester di aplikasi tertentu
        $table->foreignId('application_tester_id')->constrained('application_testers')->cascadeOnDelete();
        $table->string('file_bukti'); // Path untuk file screenshot/video
        $table->text('catatan')->nullable(); // Penjelasan hasil tes
        $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending'); // Untuk di-acc Developer
        $table->text('alasan_penolakan')->nullable(); // Jika developer menolak bukti
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testing_reports');
    }
};
