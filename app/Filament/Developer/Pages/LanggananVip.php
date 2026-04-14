<?php

namespace App\Filament\Developer\Pages;

use Filament\Pages\Page;

class LanggananVip extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Akun & Tagihan';
    protected static ?string $title = 'Berlangganan';
    protected static ?int $navigationSort = 6;
    protected static string $view = 'filament.developer.pages.langganan-vip';
}