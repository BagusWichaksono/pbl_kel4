<?php

namespace App\Filament\Tester\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class PenukaranPoinStats extends BaseWidget
{
    protected function getStats(): array
    {
        $points = Auth::user()?->testerProfile?->points ?? 0;

        return [
            Stat::make('Saldo Poin', $points . ' poin')
                ->description('1 poin = Rp1.000')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('primary'),

            Stat::make('Estimasi Saldo', 'Rp' . number_format($points * 1000, 0, ',', '.'))
                ->description('Estimasi nilai reward yang bisa dicairkan')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('success'),
        ];
    }
}