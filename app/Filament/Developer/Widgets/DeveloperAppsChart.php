<?php

namespace App\Filament\Developer\Widgets;

use App\Models\App;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class DeveloperAppsChart extends ChartWidget
{
    protected static ?string $heading = 'Aplikasi Anda';

    protected static ?string $description = 'Aplikasi baru yang kamu daftarkan dalam 6 bulan terakhir.';

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
        $devId = Auth::id();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = App::where('developer_id', '=', $devId, 'and')
                         ->whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Aplikasi baru',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.15)',
                    'borderColor' => '#2563eb',
                    'borderWidth' => 3,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'tension' => 0.35,
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
