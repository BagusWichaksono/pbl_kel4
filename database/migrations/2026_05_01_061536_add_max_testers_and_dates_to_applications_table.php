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
        if (! Schema::hasColumn('applications', 'max_testers')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->unsignedInteger('max_testers')
                    ->default(20)
                    ->after('testing_status');
            });
        }

        if (! Schema::hasColumn('applications', 'start_date')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->date('start_date')
                    ->nullable()
                    ->after('max_testers');
            });
        }

        if (! Schema::hasColumn('applications', 'end_date')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->date('end_date')
                    ->nullable()
                    ->after('start_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('applications', 'end_date')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('end_date');
            });
        }

        if (Schema::hasColumn('applications', 'start_date')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('start_date');
            });
        }

        if (Schema::hasColumn('applications', 'max_testers')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('max_testers');
            });
        }
    }
};