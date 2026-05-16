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
        Schema::table('application_testers', function (Blueprint $table) {
            if (! Schema::hasColumn('application_testers', 'proof_image')) {
                $table->string('proof_image')->nullable()->after('status');
            }

            if (! Schema::hasColumn('application_testers', 'feedback')) {
                $table->text('feedback')->nullable()->after('proof_image');
            }

            if (! Schema::hasColumn('application_testers', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('feedback');
            }

            if (! Schema::hasColumn('application_testers', 'points_awarded')) {
                $table->boolean('points_awarded')->default(false)->after('completed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sengaja dikosongkan agar data laporan tester tidak hilang.
    }
};