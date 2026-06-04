<?php

namespace App\Filament\Developer\Widgets;

use App\Models\App;
use App\Models\ApplicationTester;
use App\Models\DailyReport;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DeveloperStatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $devId = Auth::id();
        
        $totalApps = App::query()->where('developer_id', $devId)->count();
        $totalTesters = ApplicationTester::query()->whereHas('application', fn($q) => $q->where('developer_id', $devId))
            ->where('status', '!=', 'rejected')
            ->count();
        $totalReports = DailyReport::query()->whereHas('application', fn($q) => $q->where('developer_id', $devId))->count();

        return [
            Stat::make('Total Aplikasi Anda', $totalApps)
                ->description('Aplikasi yang didaftarkan')
                ->descriptionIcon('heroicon-m-rocket-launch')
                ->color('primary'),
                
            Stat::make('Total Tester Terlibat', $totalTesters)
                ->description('Tester yang mengerjakan aplikasi Anda')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
                
            Stat::make('Laporan Harian Masuk', $totalReports)
                ->description('Feedback/bug dari tester')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
        ];
    }
}
