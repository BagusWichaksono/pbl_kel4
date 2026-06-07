<?php

namespace App\Filament\Tester\Widgets;

use App\Models\ApplicationTester;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TesterMissionsChart extends ChartWidget
{
    protected static ?string $heading = 'Misi Selesai';

    protected static ?string $description = 'Misi yang sudah selesai dan divalidasi dalam 6 bulan terakhir.';

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
            $count = ApplicationTester::where('tester_id', '=', $testerId, 'and')
                         ->where('status', '=', 'completed', 'and')
                         ->whereYear('updated_at', '=', $date->year, 'and')
                         ->whereMonth('updated_at', '=', $date->month, 'and')
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Misi selesai',
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
