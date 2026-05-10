<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom app_url ke tabel applications.
     * Kolom ini diisi developer setelah jumlah tester mencapai batas maksimal (default 20).
     * Kolom url lama (yang wajib diisi saat create) dihapus dan digantikan oleh app_url.
     */
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            // app_url: link yang dikirim developer ke tester setelah slot penuh
            // nullable karena belum tentu langsung dikirim saat pertama buat aplikasi
            $table->string('app_url')->nullable()->after('url');
        });
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('app_url');
        });
    }
};
