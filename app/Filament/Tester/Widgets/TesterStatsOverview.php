<?php

namespace App\Filament\Tester\Widgets;

use App\Models\ApplicationTester;
use App\Models\TesterProfile;
use App\Models\Withdrawal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TesterStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $userId = Auth::id();
        
        $totalPoints = TesterProfile::query()->where('user_id', $userId)->value('points') ?? 0;
        
        $completedMissions = ApplicationTester::query()->where('tester_id', $userId)
            ->where('status', 'completed')
            ->count();
            
        $pendingWithdrawals = Withdrawal::query()->where('tester_id', $userId)
            ->where('status', 'pending')
            ->sum('amount_rp');

        return [
            Stat::make('Saldo Poin Anda', number_format($totalPoints, 0, ',', '.'))
                ->description('Poin yang bisa ditarik')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
                
            Stat::make('Misi Selesai', $completedMissions)
                ->description('Total testing yang berhasil')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary'),
                
            Stat::make('Penarikan Diproses', 'Rp ' . number_format($pendingWithdrawals, 0, ',', '.'))
                ->description('Menunggu admin')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
        ];
    }
}
