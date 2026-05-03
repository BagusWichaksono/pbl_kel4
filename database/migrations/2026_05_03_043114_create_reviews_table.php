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
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('app_id')->constrained('applications')->cascadeOnDelete(); // Relasi ke model App
        $table->foreignId('tester_profile_id')->constrained('tester_profiles')->cascadeOnDelete(); // Relasi ke profil tester
        $table->integer('rating'); // Menyimpan rating 1-5
        $table->text('komentar')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
