<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'user_id', 
        'application_id', 
        'amount', 
        'payment_proof', 
        'status'
    ];

    // Relasi ke Developer
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Aplikasi (Model App)
    public function application(): BelongsTo
    {
        // Kita panggil class App::class langsung
        return $this->belongsTo(App::class, 'application_id');
    }
}