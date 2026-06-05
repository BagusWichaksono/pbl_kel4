<?php

namespace App\Filament\Tester\Widgets;

use App\Models\Withdrawal;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PenukaranPoinStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        $points = (int) ($user?->testerProfile?->points ?? 0);
        $estimatedBalance = $points * 1000;

        $pendingWithdrawals = 0;
        $completedWithdrawals = 0;

        if (Schema::hasTable('withdrawals')) {
            $pendingWithdrawals = Withdrawal::query()
                ->where('tester_id', $user?->id)
                ->where('status', 'pending')
                ->count();

            $completedWithdrawals = Withdrawal::query()
                ->where('tester_id', $user?->id)
                ->whereIn('status', ['approved', 'completed'])
                ->count();
        }

        return [
            Stat::make('Saldo Poin', "{$points} poin")
                ->description('1 poin = Rp1.000')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color($points > 0 ? 'primary' : 'gray'),

            Stat::make('Estimasi Saldo', 'Rp' . number_format($estimatedBalance, 0, ',', '.'))
                ->description('Reward yang bisa dicairkan')
                ->descriptionIcon('heroicon-o-wallet')
                ->color($estimatedBalance > 0 ? 'success' : 'gray'),

            Stat::make('Diproses', "{$pendingWithdrawals} pengajuan")
                ->description('Menunggu pembayaran admin')
                ->descriptionIcon('heroicon-o-clock')
                ->color($pendingWithdrawals > 0 ? 'warning' : 'gray'),

            Stat::make('Selesai', "{$completedWithdrawals} pencairan")
                ->description('Sudah dibayar admin')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color($completedWithdrawals > 0 ? 'success' : 'gray'),
        ];
    }
}