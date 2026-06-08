<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundRequest extends Model
{
    public const DEFAULT_AMOUNT = 300000;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'developer_id',
        'application_id',
        'amount',
        'reason',
        'bank_name',
        'account_name',
        'account_number',
        'status',
        'admin_note',
        'refund_proof',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(App::class, 'application_id');
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
