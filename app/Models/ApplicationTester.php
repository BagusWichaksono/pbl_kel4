<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationTester extends Model
{
    protected $table = 'application_testers';

    protected $fillable = [
        'application_id',
        'tester_id',
        'status',
    ];

    public function application()
    {
        return $this->belongsTo(App::class, 'application_id');
    }

    public function tester()
    {
        return $this->belongsTo(User::class, 'tester_id');
    }
}
