<?php

namespace App\Filament\Developer\Pages;

use Filament\Pages\Page;

class KatalogMisi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Manajemen Pengujian';
    protected static ?string $title = 'Katalog Misi Testing';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.developer.pages.katalog-misi';
}