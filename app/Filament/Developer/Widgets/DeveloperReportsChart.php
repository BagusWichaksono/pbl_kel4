<?php

namespace App\Filament\Developer\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\DailyReport;
use Illuminate\Support\Facades\Auth;

class DeveloperReportsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Laporan Harian Masuk (6 Bulan Terakhir)';
    protected static ?int $sort = 2;
    
    protected function getData(): array
    {
        $data = [];
        $labels = [];
        $devId = Auth::id();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = DailyReport::whereHas('application', fn($q) => $q->where('developer_id', '=', $devId, 'and'))
                         ->whereYear('created_at', '=', $date->year, 'and')
                         ->whereMonth('created_at', '=', $date->month, 'and')
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Laporan Masuk',
                    'data' => $data,
                    'backgroundColor' => '#5374ac',
                    'borderColor' => '#5374ac',
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
