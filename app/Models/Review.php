<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'app_id',
        'tester_profile_id',
        'rating',
        'komentar',
    ];

    public function app()
    {
        return $this->belongsTo(App::class);
    }

    public function testerProfile()
    {
        return $this->belongsTo(TesterProfile::class);
    }
}