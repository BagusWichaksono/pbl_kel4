<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    protected $table = 'applications';

    // BLOK INI UNTUK MENGIZINKAN SIMPAN DATA
    protected $fillable = [
        'developer_id',
        'title',
        'description',
        'payment_proof',
        'payment_status',
        'testing_status',
    ];

    public function developer()
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}