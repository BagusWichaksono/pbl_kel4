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
        Schema::table('testing_reports', function (Blueprint $table) {
            $table->text('bug_report')->nullable()->after('catatan');
        });
    }

    public function down(): void
    {
        Schema::table('testing_reports', function (Blueprint $table) {
            $table->dropColumn('bug_report');
        });
    }
};
