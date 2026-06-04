<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestingReport extends Model
{
    protected $fillable = [
        'application_tester_id',
        'file_bukti',
        'catatan',
        'bug_report',
        'status',
        'alasan_penolakan',
    ];

    public function applicationTester()
    {
        return $this->belongsTo(ApplicationTester::class);
    }

    /**
     * Satu laporan akhir memiliki banyak jawaban evaluasi.
     */
    public function evaluationAnswers()
    {
        return $this->hasMany(EvaluationAnswer::class);
    }

    /**
     * Cek apakah tester sudah mengisi form evaluasi untuk laporan ini.
     */
    public function hasEvaluation(): bool
    {
        return $this->evaluationAnswers()->exists();
    }

    /**
     * Hitung rata-rata rating dari semua jawaban evaluasi.
     */
    public function averageRating(): ?float
    {
        $avg = $this->evaluationAnswers()->avg('rating');
        return $avg ? round($avg, 2) : null;
    }
}
