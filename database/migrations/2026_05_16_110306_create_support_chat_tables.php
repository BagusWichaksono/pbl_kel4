<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('support_tickets')) {
            Schema::create('support_tickets', function (Blueprint $table) {
                $table->id();

                $table->foreignId('tester_id')
                    ->constrained('users')
                    ->cascadeOnDelete();

                $table->string('subject')->default('Bantuan Tester');
                $table->string('status')->default('open');
                $table->timestamp('last_message_at')->nullable();

                $table->timestamps();
            });
        }

        if (! Schema::hasTable('support_messages')) {
            Schema::create('support_messages', function (Blueprint $table) {
                $table->id();

                $table->foreignId('support_ticket_id')
                    ->constrained('support_tickets')
                    ->cascadeOnDelete();

                $table->foreignId('sender_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->string('sender_role');
                $table->text('message');
                $table->boolean('is_read')->default(false);

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('support_messages');
        Schema::dropIfExists('support_tickets');
    }
};