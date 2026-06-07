<?php

namespace App\Filament\Admin\Widgets;

use App\Models\App;
use Filament\Widgets\ChartWidget;

class AdminAppsChart extends ChartWidget
{
    protected static ?string $heading = 'Aplikasi Baru';

    protected static ?string $description = 'Jumlah aplikasi yang didaftarkan developer setiap bulan.';

    protected static ?string $maxHeight = '290px';

    protected static ?array $options = [
        'maintainAspectRatio' => false,
        'plugins' => [
            'legend' => [
                'display' => true,
                'labels' => [
                    'usePointStyle' => true,
                ],
            ],
            'tooltip' => [
                'displayColors' => false,
            ],
        ],
        'scales' => [
            'x' => [
                'grid' => [
                    'display' => false,
                ],
            ],
            'y' => [
                'beginAtZero' => true,
                'ticks' => [
                    'precision' => 0,
                ],
            ],
        ],
    ];

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
                    'backgroundColor' => 'rgba(59, 130, 246, 0.72)',
                    'borderColor' => '#2563eb',
                    'borderRadius' => 10,
                    'borderSkipped' => false,
                    'maxBarThickness' => 38,
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
