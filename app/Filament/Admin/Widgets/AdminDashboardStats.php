<?php

namespace App\Filament\Admin\Widgets;

use App\Models\App;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminDashboardStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan Platform';

    protected ?string $description = 'Angka utama untuk membaca kondisi TesYuk! dengan cepat.';

    protected function getStats(): array
    {
        $validApps = App::where('payment_status', 'valid')->count();
        $totalRevenue = $validApps * 300000;
        $totalApps = App::count();
        $openApps = App::where('testing_status', 'open')->count();
        $totalUsers = User::count();
        $developerCount = User::where('role', 'developer')->count();
        $testerCount = User::where('role', 'tester')->count();

        return [
            Stat::make('Pendapatan Valid', $this->rupiah($totalRevenue))
                ->description($validApps . ' aplikasi sudah tervalidasi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart($this->monthlyRevenue()),

            Stat::make('Aplikasi Terdaftar', number_format($totalApps, 0, ',', '.'))
                ->description($openApps . ' aplikasi masih membuka slot tester')
                ->descriptionIcon('heroicon-m-device-phone-mobile')
                ->color('primary')
                ->chart($this->monthlyAppCounts()),

            Stat::make('Pengguna', number_format($totalUsers, 0, ',', '.'))
                ->description($developerCount . ' developer · ' . $testerCount . ' tester')
                ->descriptionIcon('heroicon-m-users')
                ->color('gray')
                ->chart($this->monthlyUserCounts()),
        ];
    }

    private function monthlyRevenue(): array
    {
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $values[] = App::where('payment_status', 'valid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count() * 300000;
        }

        return $values;
    }

    private function monthlyAppCounts(): array
    {
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $values[] = App::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return $values;
    }

    private function monthlyUserCounts(): array
    {
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $values[] = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return $values;
    }

    private function rupiah(int $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
