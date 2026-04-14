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
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            
            // Kolom-kolom untuk form Filament
            $table->string('name');
            $table->string('platform');
            $table->string('app_link');
            $table->text('description')->nullable(); // Menambahkan nullable() agar aman jika kosong
            $table->string('status')->default('draft');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apps');
    }
};