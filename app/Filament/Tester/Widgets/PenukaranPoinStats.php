<?php

namespace App\Filament\Tester\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PenukaranPoinStats extends BaseWidget
{
    // BARIS INI WAJIB: Agar widget ini tidak otomatis muncul di dashboard
    public static bool $isDiscovered = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Saldo Poin Saat Ini', Auth::user()->points . ' pts')
                ->description('Gunakan poinmu untuk menukar hadiah menarik')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),
        ];
    }
}