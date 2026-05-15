<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class App extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = [
        'developer_id',
        'title',
        'platform',
        'url',
        'description',
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

    // Pivot table untuk langsung mengambil data User tester (opsional tapi bagus dipertahankan)
    public function testerUsers()
    {
        return $this->belongsToMany(User::class, 'application_testers', 'application_id', 'tester_id');
    }

    // ─── TAMBAHAN RELASI BARU (AGAR NYAMBUNG KE MODEL LAIN) ───

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

    /**
     * Cek apakah jumlah tester sudah mencapai batas maksimal.
     */
    public function isFull(): bool
    {
        return $this->testers()->count() >= $this->max_testers;
    }

    /**
     * Cek apakah sesi testing masih berlangsung.
     */
    public function isTestingActive(): bool
    {
        if (!$this->start_date || !$this->end_date) {
            return false;
        }

        return Carbon::now()->between($this->start_date, $this->end_date);
    }

    /**
     * Hitung sisa hari sesi testing.
     */
    public function remainingDays(): int
    {
        if (!$this->end_date) {
            return 0;
        }

        $remaining = Carbon::now()->diffInDays($this->end_date, false);
        return max(0, (int) $remaining);
    }
}