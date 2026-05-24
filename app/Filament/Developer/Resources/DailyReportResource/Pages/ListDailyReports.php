<?php

namespace App\Filament\Developer\Resources\DailyReportResource\Pages;

use App\Filament\Developer\Resources\DailyReportResource;
use Filament\Resources\Pages\ListRecords;
use App\Models\DailyReport;
use Illuminate\Support\Collection;

class ListDailyReports extends ListRecords
{
    protected static string $resource = DailyReportResource::class;

    protected static string $view = 'filament.developer.pages.list-daily-reports';

        public function getGroupedReportsProperty(): Collection
    {
        return DailyReport::query()
            ->with(['application', 'tester'])
            // Jika hanya ingin menampilkan application milik developer login, aktifkan ini:
            ->whereHas('application', function ($query) {
            $query->where('developer_id', auth()->id());
             })
            ->orderBy('app_id')
            ->orderBy('report_date')
            ->orderBy('tester_id')
            ->get()
            ->groupBy(function ($report) {
                return $report->application?->title ?? 'Aplikasi Tidak Diketahui';
            })
            ->map(function ($reportsByApp) {
                return $reportsByApp->groupBy(function ($report) {
                    return $report->report_date;
                });
            });
    }
}
