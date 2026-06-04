<?php

namespace App\Filament\Admin\Widgets;

use App\Models\App;
use App\Models\User;
use App\Models\Withdrawal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $totalRevenue = App::query()->where('payment_status', 'valid')->count() * 300000;
        
        return [
            Stat::make('Total Pendapatan (Rp)', number_format($totalRevenue, 0, ',', '.'))
                ->description('Dari pembayaran developer')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
                
            Stat::make('Total Aplikasi (Valid)', App::query()->where('payment_status', 'valid')->count())
                ->description('Aplikasi yang sudah divalidasi')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),
                
            Stat::make('Tester Aktif', User::query()->where('role', 'tester')->count())
                ->description('Total tester terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
                
            Stat::make('Pending Penarikan Dana', Withdrawal::query()->where('status', 'pending')->count())
                ->description('Permintaan withdraw belum diproses')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
