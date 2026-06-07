<?php

namespace App\Filament\Developer\Widgets;

use App\Models\App;
use App\Models\ApplicationTester;
use App\Models\DailyReport;
use App\Models\TestingReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DeveloperDashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan Testing';

    protected ?string $description = 'Pantau aplikasi, tester, dan laporan yang perlu ditindaklanjuti.';

    protected function getStats(): array
    {
        $developerId = (int) Auth::id();
        $totalApps = App::where('developer_id', $developerId)->count();
        $validApps = App::where('developer_id', $developerId)
            ->where('payment_status', 'valid')
            ->count();
        $testerCount = ApplicationTester::whereHas(
            'application',
            fn ($query) => $query->where('developer_id', $developerId)
        )->count();
        $activeTesterCount = ApplicationTester::whereHas(
            'application',
            fn ($query) => $query->where('developer_id', $developerId)
        )->where('status', 'active')->count();
        $dailyReports = DailyReport::whereHas(
            'application',
            fn ($query) => $query->where('developer_id', $developerId)
        )->count();
        $reportsThisMonth = DailyReport::whereHas(
            'application',
            fn ($query) => $query->where('developer_id', $developerId)
        )->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $pendingFinalReports = TestingReport::whereHas(
            'applicationTester.application',
            fn ($query) => $query->where('developer_id', $developerId)
        )->where('status', 'pending')->count();

        return [
            Stat::make('Aplikasi Saya', number_format($totalApps, 0, ',', '.'))
                ->description($validApps . ' aplikasi sudah valid')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('primary')
                ->chart($this->monthlyAppCounts($developerId)),

            Stat::make('Tester Bergabung', number_format($testerCount, 0, ',', '.'))
                ->description($activeTesterCount . ' tester sedang aktif')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart($this->monthlyTesterCounts($developerId)),

            Stat::make('Laporan Harian', number_format($dailyReports, 0, ',', '.'))
                ->description($reportsThisMonth . ' laporan masuk bulan ini')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('warning')
                ->chart($this->monthlyReportCounts($developerId)),

            Stat::make('Laporan Akhir Pending', number_format($pendingFinalReports, 0, ',', '.'))
                ->description('Menunggu validasi developer')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingFinalReports > 0 ? 'warning' : 'success'),
        ];
    }

    private function monthlyAppCounts(int $developerId): array
    {
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $values[] = App::where('developer_id', $developerId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return $values;
    }

    private function monthlyTesterCounts(int $developerId): array
    {
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $values[] = ApplicationTester::whereHas(
                'application',
                fn ($query) => $query->where('developer_id', $developerId)
            )->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return $values;
    }

    private function monthlyReportCounts(int $developerId): array
    {
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $values[] = DailyReport::whereHas(
                'application',
                fn ($query) => $query->where('developer_id', $developerId)
            )->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return $values;
    }
}
