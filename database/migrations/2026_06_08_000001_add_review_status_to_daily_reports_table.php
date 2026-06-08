<?php

use App\Models\DailyReport;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            if (! Schema::hasColumn('daily_reports', 'status')) {
                $table->string('status')->default(DailyReport::STATUS_PENDING)->after('bug_report');
            }

            if (! Schema::hasColumn('daily_reports', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('status');
            }

            if (! Schema::hasColumn('daily_reports', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('rejection_reason');
            }

            if (! Schema::hasColumn('daily_reports', 'reviewed_by')) {
                $table->foreignId('reviewed_by')
                    ->nullable()
                    ->after('reviewed_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('daily_reports', function (Blueprint $table) {
            if (Schema::hasColumn('daily_reports', 'reviewed_by')) {
                $table->dropConstrainedForeignId('reviewed_by');
            }

            foreach (['reviewed_at', 'rejection_reason', 'status'] as $column) {
                if (Schema::hasColumn('daily_reports', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
