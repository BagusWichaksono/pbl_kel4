<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationTester extends Model
{
    protected $fillable = [
        'application_id',
        'tester_id',
        'status',
        'proof_image',
        'feedback',
        'completed_at',
        'points_awarded',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'points_awarded' => 'boolean',
    ];

    public function application()
    {
        return $this->belongsTo(App::class, 'application_id');
    }

    public function tester()
    {
        return $this->belongsTo(User::class, 'tester_id');
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class, 'app_id', 'application_id')
            ->where('tester_id', $this->tester_id);
    }
}