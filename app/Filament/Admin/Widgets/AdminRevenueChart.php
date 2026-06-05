<?php

namespace App\Filament\Admin\Widgets;

use App\Models\App;
use App\Support\AppPalette;
use Filament\Widgets\ChartWidget;

class AdminRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Pendapatan (6 Bulan Terakhir)';
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
                    'label' => 'Total Pendapatan (Rp)',
                    'data' => $data,
                    'backgroundColor' => AppPalette::ACCENT,
                    'borderColor' => AppPalette::ACCENT,
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
