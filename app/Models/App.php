<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        'developer_id',
        'title',
        'platform',
        'description',
        'payment_proof',
        'payment_status',
        'testing_status',
        'max_testers',
        'start_date',
        'end_date',
        'review_screenshot',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // ─── RELASI KE AKTOR ───

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function testers()
    {
        return $this->hasMany(ApplicationTester::class, 'application_id');
    }

    public function testerUsers()
    {
        return $this->belongsToMany(User::class, 'application_testers', 'application_id', 'tester_id');
    }

    // ─── RELASI KE MODEL LAIN ───

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'application_id');
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class, 'app_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'app_id');
    }

    // ─── HELPER METHODS ───

    public function isFull(): bool
    {
        return $this->testers()->count() >= $this->max_testers;
    }

    public function isTestingActive(): bool
    {
        if (!$this->start_date || !$this->end_date) {
            return false;
        }

        return Carbon::now()->between($this->start_date, $this->end_date);
    }

    public function remainingDays(): int
    {
        if (!$this->end_date) {
            return 0;
        }

        $remaining = Carbon::now()->diffInDays($this->end_date, false);

        return max(0, (int) $remaining);
    }
}