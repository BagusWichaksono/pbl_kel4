<?php

namespace App\Filament\Admin\Widgets;

use App\Models\App;
use App\Models\RefundRequest;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Schema;

class AdminRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Bersih';

    protected static ?string $description = 'Estimasi pendapatan valid dikurangi refund yang sudah disetujui. 1 aplikasi = Rp300.000.';

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
            $paidCount = App::whereIn('payment_status', ['valid', 'approved', 'refunded'])
                ->whereYear('created_at', '=', $date->year, 'and')
                ->whereMonth('created_at', '=', $date->month, 'and')
                ->count();

            $refundTotal = 0;

            if (Schema::hasTable('refund_requests')) {
                $refundTotal = (int) RefundRequest::where('status', RefundRequest::STATUS_APPROVED)
                    ->whereHas('application', function ($query) use ($date): void {
                        $query
                            ->whereYear('created_at', '=', $date->year, 'and')
                            ->whereMonth('created_at', '=', $date->month, 'and');
                    })
                    ->sum('amount');
            }

            $data[] = max(0, ($paidCount * 300000) - $refundTotal);
            $labels[] = $date->translatedFormat('M Y');
        }
 
        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan bersih',
                    'data' => $data,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.16)',
                    'borderColor' => '#10b981',
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
