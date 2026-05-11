<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $fillable = [
        'tester_id',
        'points_withdrawn',
        'amount_rp',
        'e_wallet_provider',
        'e_wallet_number',
        'status',
    ];

    // Relasi ke user (tester)
    public function tester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tester_id');
    }
}
