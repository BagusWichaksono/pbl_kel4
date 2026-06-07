<?php

namespace App\Filament\Tester\Widgets;

use App\Models\ApplicationTester;
use App\Models\PointHistory;
use App\Models\Withdrawal;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class TesterDashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan Akun Tester';

    protected ?string $description = 'Saldo, misi, dan pencairan poin ditampilkan dalam satu tempat.';

    protected function getStats(): array
    {
        $testerId = (int) Auth::id();
        $points = (int) (Auth::user()?->testerProfile?->points ?? 0);
        $pointsIn = (int) PointHistory::where('tester_id', $testerId)
            ->where('type', 'credit')
            ->sum('amount');
        $pointsOut = (int) PointHistory::where('tester_id', $testerId)
            ->where('type', 'debit')
            ->sum('amount');
        $activeMissions = ApplicationTester::where('tester_id', $testerId)
            ->where('status', 'active')
            ->count();
        $completedMissions = ApplicationTester::where('tester_id', $testerId)
            ->where('status', 'completed')
            ->count();
        $approvedWithdrawals = (int) Withdrawal::where('tester_id', $testerId)
            ->where('status', 'approved')
            ->sum('amount_rp');
        $pendingWithdrawals = Withdrawal::where('tester_id', $testerId)
            ->where('status', 'pending')
            ->count();

        return [
            Stat::make('Saldo Poin', number_format($points, 0, ',', '.') . ' poin')
                ->description('Setara ' . $this->rupiah($points * 1000))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart($this->monthlyPointCredits($testerId)),

            Stat::make('Poin Masuk', number_format($pointsIn, 0, ',', '.') . ' poin')
                ->description('Total reward yang sudah masuk')
                ->descriptionIcon('heroicon-m-arrow-down-circle')
                ->color('primary')
                ->chart($this->monthlyPointCredits($testerId)),

            Stat::make('Penarikan Poin', number_format($pointsOut, 0, ',', '.') . ' poin')
                ->description($this->rupiah($approvedWithdrawals) . ' cair · ' . $pendingWithdrawals . ' pending')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('warning')
                ->chart($this->monthlyPointDebits($testerId)),

            Stat::make('Misi Selesai', number_format($completedMissions, 0, ',', '.'))
                ->description($activeMissions . ' misi sedang berjalan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart($this->monthlyCompletedMissions($testerId)),
        ];
    }

    private function monthlyPointCredits(int $testerId): array
    {
        return $this->monthlyPointSums($testerId, 'credit');
    }

    private function monthlyPointDebits(int $testerId): array
    {
        return $this->monthlyPointSums($testerId, 'debit');
    }

    private function monthlyPointSums(int $testerId, string $type): array
    {
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $values[] = (int) PointHistory::where('tester_id', $testerId)
                ->where('type', $type)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');
        }

        return $values;
    }

    private function monthlyCompletedMissions(int $testerId): array
    {
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $values[] = ApplicationTester::where('tester_id', $testerId)
                ->where('status', 'completed')
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->count();
        }

        return $values;
    }

    private function rupiah(int $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
