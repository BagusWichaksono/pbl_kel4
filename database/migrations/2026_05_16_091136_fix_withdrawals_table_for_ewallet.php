<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('withdrawals')) {
            Schema::create('withdrawals', function (Blueprint $table) {
                $table->id();

                $table->foreignId('tester_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

                $table->integer('points_withdrawn')->default(0);
                $table->integer('amount_rp')->default(0);

                $table->string('e_wallet_provider')->nullable();
                $table->string('e_wallet_number')->nullable();

                $table->string('status')->default('pending');
                $table->text('notes')->nullable();

                $table->timestamps();
            });

            return;
        }

        Schema::table('withdrawals', function (Blueprint $table) {
            if (! Schema::hasColumn('withdrawals', 'tester_id')) {
                $table->foreignId('tester_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('withdrawals', 'points_withdrawn')) {
                $table->integer('points_withdrawn')->default(0)->after('tester_id');
            }

            if (! Schema::hasColumn('withdrawals', 'amount_rp')) {
                $table->integer('amount_rp')->default(0)->after('points_withdrawn');
            }

            if (! Schema::hasColumn('withdrawals', 'e_wallet_provider')) {
                $table->string('e_wallet_provider')->nullable()->after('amount_rp');
            }

            if (! Schema::hasColumn('withdrawals', 'e_wallet_number')) {
                $table->string('e_wallet_number')->nullable()->after('e_wallet_provider');
            }

            if (! Schema::hasColumn('withdrawals', 'status')) {
                $table->string('status')->default('pending')->after('e_wallet_number');
            }

            if (! Schema::hasColumn('withdrawals', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });

        if (
            Schema::hasColumn('withdrawals', 'user_id') &&
            Schema::hasColumn('withdrawals', 'tester_id')
        ) {
            DB::statement('UPDATE withdrawals SET tester_id = user_id WHERE tester_id IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sengaja dikosongkan agar data penukaran poin tidak hilang.
    }
};