<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Support\AppPalette;
use Filament\Widgets\ChartWidget;

class AdminUsersChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Pendaftaran Pengguna Baru (6 Bulan Terakhir)';
    protected static ?int $sort = 2;
    
    protected function getData(): array
    {
        $data = [];
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = User::whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Total Pengguna',
                    'data' => $data,
                    'backgroundColor' => AppPalette::ACCENT,
                    'borderColor' => AppPalette::ACCENT,
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
