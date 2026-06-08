<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    public const MIN_TESTERS_TO_START = 12;

    protected $table = 'applications';

    protected $fillable = [
        'developer_id',
        'title',
        'app_icon',
        'platform',
        'app_link',
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

    public function isFull(): bool
    {
        return $this->testers()->count() >= $this->max_testers;
    }

    public function activeTesterCount(): int
    {
        if ($this->relationLoaded('testers')) {
            return $this->testers
                ->whereIn('status', [ApplicationTester::STATUS_ACTIVE, ApplicationTester::STATUS_COMPLETED])
                ->count();
        }

        return $this->testers()
            ->whereIn('status', [ApplicationTester::STATUS_ACTIVE, ApplicationTester::STATUS_COMPLETED])
            ->count();
    }

    public function hasMinimumTestersToStart(): bool
    {
        return $this->activeTesterCount() >= self::MIN_TESTERS_TO_START;
    }

    public function remainingTestersToStart(): int
    {
        return max(0, self::MIN_TESTERS_TO_START - $this->activeTesterCount());
    }

    public function isTestingActive(): bool
    {
        if (! $this->start_date || ! $this->end_date) {
            return false;
        }

        return Carbon::now()->between($this->start_date, $this->end_date);
    }

    public function remainingDays(): int
    {
        if (! $this->end_date) {
            return 0;
        }

        $remaining = Carbon::now()->diffInDays($this->end_date, false);

        return max(0, (int) $remaining);
    }
}
