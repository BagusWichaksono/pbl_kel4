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
            // Menghapus kolom yang tidak terpakai
            if (Schema::hasColumn('withdrawals', 'user_id')) {
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

            // Menambah kolom baru
            $table->string('invoice_code')->unique()->nullable()->after('id');
            $table->string('payment_proof')->nullable()->after('notes');

            // Memodifikasi kolom yang ada
            $table->string('account_name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            // Kembalikan kolom yang dihapus
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('qris_image')->nullable();

            // Hapus kolom yang baru ditambahkan
            $table->dropColumn(['invoice_code', 'payment_proof']);

            // Kembalikan modifikasi account_name
            $table->string('account_name')->nullable(false)->change();
        });
    }
};
