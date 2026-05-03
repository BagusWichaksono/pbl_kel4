<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestingReport extends Model
{
    protected $fillable = [
        'application_tester_id',
        'file_bukti',
        'catatan',
        'status',
        'alasan_penolakan',
    ];

    public function applicationTester()
    {
        return $this->belongsTo(ApplicationTester::class);
    }
}
