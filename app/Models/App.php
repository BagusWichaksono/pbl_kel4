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
        // 'url' — kolom lama, tidak digunakan lagi di form create.
        //         Kolom di DB masih ada (nullable) untuk backward-compat,
        //         tapi input baru tidak mengisinya.
        'description',
        'payment_proof',
        'payment_status',
        'testing_status',
        'max_testers',
        'start_date',
        'end_date',
        // kolom baru: link aplikasi yang dikirim developer ke tester setelah slot penuh
        'app_url',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // ─── Relasi ──────────────────────────────────────────────

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

    // ─── Helper ──────────────────────────────────────────────

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
        if (! $this->start_date || ! $this->end_date) {
            return false;
        }

        return Carbon::now()->between($this->start_date, $this->end_date);
    }

    /**
     * Hitung sisa hari sesi testing.
     */
    public function remainingDays(): int
    {
        if (! $this->end_date) {
            return 0;
        }

        $remaining = Carbon::now()->diffInDays($this->end_date, false);

        return max(0, (int) $remaining);
    }

    /**
     * Cek apakah link aplikasi sudah dikirim ke tester.
     */
    public function hasAppUrl(): bool
    {
        return ! empty($this->app_url);
    }
}
