<?php

namespace App\Filament\Tester\Pages;

use Filament\Pages\Page;

class DashboardTester extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.tester.pages.dashboard-tester';
}
