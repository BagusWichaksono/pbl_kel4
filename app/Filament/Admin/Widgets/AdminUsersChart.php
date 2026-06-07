<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;

class AdminUsersChart extends ChartWidget
{
    protected static ?string $heading = 'Pengguna Baru';

    protected static ?string $description = 'Jumlah akun baru yang mendaftar ke platform setiap bulan.';

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
            $count = User::whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Pengguna baru',
                    'data' => $data,
                    'backgroundColor' => 'rgba(139, 92, 246, 0.15)',
                    'borderColor' => '#8b5cf6',
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
