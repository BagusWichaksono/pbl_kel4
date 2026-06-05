<?php

namespace App\Filament\Tester\Widgets;

use App\Models\PointHistory;
use App\Support\AppPalette;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TesterPointsOutChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Poin Keluar (6 Bulan Terakhir)';
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
                    'label' => 'Poin Ditarik',
                    'data' => $data,
                    'backgroundColor' => AppPalette::PRIMARY,
                    'borderColor' => AppPalette::PRIMARY,
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
