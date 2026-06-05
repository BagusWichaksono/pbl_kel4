<?php

namespace App\Filament\Developer\Widgets;

use App\Models\ApplicationTester;
use App\Support\AppPalette;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class DeveloperTestersChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Tester Terlibat (6 Bulan Terakhir)';
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
                    'label' => 'Tester Baru',
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
        return 'bar';
    }
}
