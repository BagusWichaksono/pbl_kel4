<?php

namespace App\Filament\Tester\Widgets;

use App\Models\PointHistory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TesterPointsChart extends ChartWidget
{
    protected static ?string $heading = 'Poin Masuk';

    protected static ?string $description = 'Poin reward yang kamu dapatkan dari misi testing.';

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
        $testerId = Auth::id();
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $points = PointHistory::where('tester_id', '=', $testerId, 'and')
                         ->where('type', '=', 'credit', 'and')
                         ->whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->sum('amount');
            $data[] = $points;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Poin masuk',
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
