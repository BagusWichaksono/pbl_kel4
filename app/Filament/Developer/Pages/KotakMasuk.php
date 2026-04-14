<?php

namespace App\Filament\Developer\Pages;

use Filament\Pages\Page;

class KotakMasuk extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'Komunikasi';
    protected static ?string $title = 'Kotak Masuk';
    protected static ?int $navigationSort = 5;
    // Tambahkan badge angka (opsional, biar kelihatan ada pesan belum dibaca)
    public static function getNavigationBadge(): ?string
    {
        return '3';
    }
    protected static string $view = 'filament.developer.pages.kotak-masuk';
}