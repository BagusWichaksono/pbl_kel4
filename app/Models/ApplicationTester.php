<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ApplicationTester extends Model
{
    public const DAILY_TESTING_DAYS = 14;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_DROPPED = 'dropped';

    public const STATUS_COMPLETED = 'completed';

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

    public function testingStartDate(): ?Carbon
    {
        $application = $this->relationLoaded('application')
            ? $this->application
            : $this->application()->first();

        if (! $application?->start_date) {
            return null;
        }

        return Carbon::parse($application->start_date)->startOfDay();
    }

    public function submittedDailyReportDates(): Collection
    {
        $startDate = $this->testingStartDate();
        $lastTestingDate = $startDate?->copy()->addDays(self::DAILY_TESTING_DAYS - 1);

        return DailyReport::query()
            ->where('tester_id', $this->tester_id)
            ->where('app_id', $this->application_id)
            ->whereNotNull('screenshot')
            ->pluck('report_date')
            ->filter()
            ->map(fn ($date) => Carbon::parse($date)->toDateString())
            ->filter(function (string $date) use ($startDate, $lastTestingDate): bool {
                if (! $startDate || ! $lastTestingDate) {
                    return true;
                }

                $reportDate = Carbon::parse($date)->startOfDay();

                return $reportDate->betweenIncluded($startDate, $lastTestingDate);
            })
            ->unique()
            ->values();
    }

    public function missedDailyReportDates(?Carbon $today = null): Collection
    {
        $startDate = $this->testingStartDate();

        if (! $startDate) {
            return collect();
        }

        $today = ($today ?? Carbon::today())->copy()->startOfDay();

        if ($today->lessThanOrEqualTo($startDate)) {
            return collect();
        }

        $lastPastDate = $today->copy()->subDay();
        $lastTestingDate = $startDate->copy()->addDays(self::DAILY_TESTING_DAYS - 1);
        $checkUntil = $lastPastDate->lessThan($lastTestingDate)
            ? $lastPastDate
            : $lastTestingDate;

        if ($checkUntil->lessThan($startDate)) {
            return collect();
        }

        $submittedDates = $this->submittedDailyReportDates()->flip();
        $missedDates = collect();

        for ($date = $startDate->copy(); $date->lessThanOrEqualTo($checkUntil); $date->addDay()) {
            $dateString = $date->toDateString();

            if (! $submittedDates->has($dateString)) {
                $missedDates->push($dateString);
            }
        }

        return $missedDates;
    }

    public function hasMissedDailyReport(?Carbon $today = null): bool
    {
        return $this->missedDailyReportDates($today)->isNotEmpty();
    }

    public function shouldBeDroppedBecauseMissedDailyReport(?Carbon $today = null): bool
    {
        if ($this->status === self::STATUS_COMPLETED) {
            return false;
        }

        return $this->hasMissedDailyReport($today);
    }

    public function markDroppedIfMissedDailyReport(?Carbon $today = null): bool
    {
        if (! $this->shouldBeDroppedBecauseMissedDailyReport($today)) {
            return false;
        }

        if ($this->status !== self::STATUS_DROPPED) {
            $this->forceFill(['status' => self::STATUS_DROPPED])->save();
        }

        $this->setAttribute('status', self::STATUS_DROPPED);

        return true;
    }
}
