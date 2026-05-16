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
        if (! Schema::hasColumn('applications', 'review_screenshot')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->string('review_screenshot')
                    ->nullable()
                    ->after('testing_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('applications', 'review_screenshot')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('review_screenshot');
            });
        }
    }
};