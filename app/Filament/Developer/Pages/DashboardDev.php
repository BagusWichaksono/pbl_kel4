<?php

namespace App\Filament\Developer\Pages;

use Filament\Pages\Page;

class DashboardDev extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.developer.pages.dashboard-dev';

    protected static string $routePath = '/'; 
    protected static ?string $title = 'Dashboard'; 
    protected static ?string $navigationLabel = 'Dashboard'; 
}