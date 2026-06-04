<?php

namespace App\Filament\Developer\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\ApplicationTester;
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
                         ->whereYear('created_at', $date->year)
                         ->whereMonth('created_at', $date->month)
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Tester Baru',
                    'data' => $data,
                    'backgroundColor' => '#5374ac',
                    'borderColor' => '#5374ac',
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
