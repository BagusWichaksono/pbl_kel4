<?php

namespace App\Filament\Admin\Widgets;

use App\Models\App;
use Filament\Widgets\ChartWidget;

class AdminRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Valid';

    protected static ?string $description = 'Estimasi pendapatan dari aplikasi yang pembayarannya sudah valid. 1 aplikasi = Rp300.000.';

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
            $count = App::where('payment_status', '=', 'valid', 'and')
                         ->whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->count();
            $data[] = $count * 300000;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan valid',
                    'data' => $data,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.16)',
                    'borderColor' => '#10b981',
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
