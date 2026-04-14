<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    use HasFactory;

    // BLOK INI UNTUK MENGIZINKAN SIMPAN DATA
    protected $fillable = [
        'name',
        'platform',
        'app_link',
        'description',
        'status',
    ];
}