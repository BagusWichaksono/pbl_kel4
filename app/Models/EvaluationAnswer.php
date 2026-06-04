<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationAnswer extends Model
{
    protected $fillable = [
        'testing_report_id',
        'evaluation_question_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Jawaban ini milik laporan akhir mana.
     */
    public function testingReport()
    {
        return $this->belongsTo(TestingReport::class);
    }

    /**
     * Jawaban ini menjawab pertanyaan mana.
     */
    public function question()
    {
        return $this->belongsTo(EvaluationQuestion::class, 'evaluation_question_id');
    }
}
