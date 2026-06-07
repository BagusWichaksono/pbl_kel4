<?php

namespace App\Filament\Developer\Widgets;

use App\Models\ApplicationTester;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class DeveloperTestersChart extends ChartWidget
{
    protected static ?string $heading = 'Tester Bergabung';

    protected static ?string $description = 'Jumlah tester yang mengambil misi aplikasi kamu setiap bulan.';

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
            $count = ApplicationTester::whereHas('application', fn($q) => $q->where('developer_id', '=', $devId, 'and'))
                         ->where('status', '!=', 'rejected', 'and')
                         ->whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Tester baru',
                    'data' => $data,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.72)',
                    'borderColor' => '#10b981',
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
