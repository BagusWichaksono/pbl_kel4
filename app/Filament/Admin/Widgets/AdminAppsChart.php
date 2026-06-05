<?php

namespace App\Filament\Admin\Widgets;

use App\Models\App;
use App\Support\AppPalette;
use Filament\Widgets\ChartWidget;

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
            $count = App::whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Aplikasi Baru',
                    'data' => $data,
                    'backgroundColor' => AppPalette::PRIMARY,
                    'borderColor' => AppPalette::PRIMARY,
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
