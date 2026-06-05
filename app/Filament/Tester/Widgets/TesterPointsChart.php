<?php

namespace App\Filament\Tester\Widgets;

use App\Models\PointHistory;
use App\Support\AppPalette;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TesterPointsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Poin Didapatkan (6 Bulan Terakhir)';
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
                    'label' => 'Poin Masuk',
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
