<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TesterProfile extends Model
{
    //
    protected $fillable = [
        'user_id',
        'e_wallet_provider',
        'e_wallet_number',
        'points'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
