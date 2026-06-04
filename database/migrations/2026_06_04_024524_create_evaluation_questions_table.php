<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_text');           // Teks pertanyaan
            $table->tinyInteger('min_scale')->default(1);  // Batas bawah skala (default 1)
            $table->tinyInteger('max_scale')->default(10); // Batas atas skala (default 10)
            $table->integer('order')->default(0);      // Urutan tampil di form
            $table->boolean('is_active')->default(true); // Aktif/nonaktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_questions');
    }
};
