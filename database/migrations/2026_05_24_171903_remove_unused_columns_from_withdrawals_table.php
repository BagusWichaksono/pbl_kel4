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
        Schema::table('withdrawals', function (Blueprint $table) {
            if (Schema::hasColumn('withdrawals', 'user_id')) {
                // Ignore error if foreign key doesn't exist but column does
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Do nothing
                }
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('withdrawals', 'amount')) {
                $table->dropColumn('amount');
            }
            if (Schema::hasColumn('withdrawals', 'qris_image')) {
                $table->dropColumn('qris_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('qris_image')->nullable();
        });
    }
};
