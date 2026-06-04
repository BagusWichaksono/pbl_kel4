<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\App;

class AdminAppsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Aplikasi Terdaftar (6 Bulan Terakhir)';
    protected static ?int $sort = 2;
    
    protected function getData(): array
    {
        $data = [];
        $labels = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = App::whereYear('created_at', $date->year)
                         ->whereMonth('created_at', $date->month)
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Aplikasi Baru',
                    'data' => $data,
                    'backgroundColor' => '#2f456f',
                    'borderColor' => '#2f456f',
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
