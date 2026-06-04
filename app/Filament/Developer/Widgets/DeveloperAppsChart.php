<?php

namespace App\Filament\Developer\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\App;
use Illuminate\Support\Facades\Auth;

class DeveloperAppsChart extends ChartWidget
{
    protected static ?string $heading = 'Statistik Aplikasi Anda (6 Bulan Terakhir)';
    protected static ?int $sort = 2;
    
    protected function getData(): array
    {
        $data = [];
        $labels = [];
        $devId = Auth::id();
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = App::where('developer_id', '=', $devId, 'and')
                         ->whereYear('created_at', $date->year)
                         ->whereMonth('created_at', $date->month)
                         ->count();
            $data[] = $count;
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Aplikasi Terdaftar',
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
        return 'line';
    }
}
