<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    protected $fillable = [
        'question_text',
        'min_scale',
        'max_scale',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'min_scale'  => 'integer',
        'max_scale'  => 'integer',
        'order'      => 'integer',
    ];

    /**
     * Relasi: satu pertanyaan memiliki banyak jawaban dari berbagai tester.
     */
    public function answers()
    {
        return $this->hasMany(EvaluationAnswer::class);
    }

    /**
     * Scope: hanya pertanyaan yang aktif, diurutkan berdasarkan kolom order.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('order');
    }
}
