<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = [
        'tester_id',
        'invoice_code',
        'points_withdrawn',
        'amount_rp',
        'account_name',
        'e_wallet_provider',
        'e_wallet_number',
        'status',
        'payment_proof',
        'notes',
    ];

    protected $casts = [
        'points_withdrawn' => 'integer',
        'amount_rp' => 'integer',
    ];

    public function tester()
    {
        return $this->belongsTo(User::class, 'tester_id');
    }
}