<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'tester_id',
        'subject',
        'status',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function tester()
    {
        return $this->belongsTo(User::class, 'tester_id');
    }

    public function messages()
    {
        return $this->hasMany(SupportMessage::class, 'support_ticket_id');
    }

    public function latestMessage()
    {
        return $this->hasOne(SupportMessage::class, 'support_ticket_id')->latestOfMany();
    }
}