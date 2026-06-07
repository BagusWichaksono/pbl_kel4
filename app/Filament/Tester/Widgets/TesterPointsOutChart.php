<?php

namespace App\Filament\Tester\Widgets;

use App\Models\PointHistory;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TesterPointsOutChart extends ChartWidget
{
    protected static ?string $heading = 'Poin Dicairkan';

    protected static ?string $description = 'Poin yang kamu tukarkan ke e-wallet.';

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
                         ->where('type', '=', 'debit', 'and')
                         ->whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->sum('amount');
            $data[] = $points;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Poin dicairkan',
                    'data' => $data,
                    'backgroundColor' => 'rgba(245, 158, 11, 0.72)',
                    'borderColor' => '#d97706',
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
