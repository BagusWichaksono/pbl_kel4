<?php

namespace App\Filament\Tester\Widgets;

use App\Models\ApplicationTester;
use App\Support\AppPalette;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TesterMissionsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Misi Diselesaikan (6 Bulan Terakhir)';
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
                    'label' => 'Misi Selesai',
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
