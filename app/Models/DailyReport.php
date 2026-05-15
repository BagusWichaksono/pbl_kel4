<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
    protected $fillable = [
        'tester_id',
        'app_id',
        'report_date',
        'screenshot',
        'notes'
    ];

    public function tester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tester_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(App::class, 'app_id');
    }
}