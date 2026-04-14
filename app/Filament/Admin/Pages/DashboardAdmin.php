<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;

class DashboardAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.admin.pages.dashboard-admin';

    protected static string $routePath = '/'; 
    protected static ?string $title = 'Dashboard'; 
    protected static ?string $navigationLabel = 'Dashboard'; 
}