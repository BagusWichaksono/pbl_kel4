<?php

namespace App\Filament\Developer\Widgets;

use App\Models\DailyReport;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class DeveloperReportsChart extends ChartWidget
{
    protected static ?string $heading = 'Laporan Harian';

    protected static ?string $description = 'Jumlah laporan harian yang dikirim tester untuk aplikasi kamu.';

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
            $count = DailyReport::whereHas('application', fn($q) => $q->where('developer_id', '=', $devId, 'and'))
                         ->whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Laporan masuk',
                    'data' => $data,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.72)',
                    'borderColor' => '#d97706',
                    'borderRadius' => 10,
                    'borderSkipped' => false,
                    'maxBarThickness' => 38,
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
