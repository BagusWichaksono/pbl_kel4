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
        ];
    }
}
