<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ManajemenPengguna extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static string $view = 'filament.admin.pages.manajemen-pengguna';

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && $user->isAdminOrSuperAdmin();
    }
}
