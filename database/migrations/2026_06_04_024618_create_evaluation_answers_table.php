<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('testing_report_id')
                ->constrained('testing_reports')
                ->cascadeOnDelete();
            $table->foreignId('evaluation_question_id')
                ->constrained('evaluation_questions')
                ->cascadeOnDelete();
            $table->tinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            // Nama constraint diperpendek agar tidak melebihi batas 64 karakter MySQL
            $table->unique(
                ['testing_report_id', 'evaluation_question_id'],
                'eval_answers_report_question_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_answers');
    }
};
