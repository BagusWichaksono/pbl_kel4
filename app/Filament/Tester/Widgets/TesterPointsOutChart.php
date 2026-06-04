<?php

namespace App\Filament\Tester\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\PointHistory;
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
                         ->whereYear('created_at', $date->year)
                         ->whereMonth('created_at', $date->month)
                         ->sum('amount');
            $data[] = $points;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Poin Ditarik',
                    'data' => $data,
                    'backgroundColor' => '#2f456f',
                    'borderColor' => '#2f456f',
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
