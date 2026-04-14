<?php

namespace App\Filament\Developer\Pages;

use Filament\Pages\Page;

class LaporanBug extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';
    protected static ?string $navigationGroup = 'Manajemen Pengujian';
    protected static ?string $title = 'Laporan Bug & Feedback';
    protected static ?int $navigationSort = 4;
    protected static string $view = 'filament.developer.pages.laporan-bug';
}