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
        $appId = request()->input('tableFilters.app_id.value')
            ?? data_get($this->tableFilters ?? [], 'app_id.value');

        $search = request()->input('tableSearch')
            ?? ($this->tableSearch ?? null);

        return DailyReport::query()
            ->with(['application', 'tester'])
            ->whereHas('application', function ($query) {
                $query->where('developer_id', auth()->id());
            })
            ->when(filled($appId), function ($query) use ($appId) {
                $query->where('app_id', $appId);
            })
            ->when(filled($search), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->whereHas('tester', function ($testerQuery) use ($search) {
                            $testerQuery->where('name', 'like', "%{$search}%");
                        })
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhere('bug_report', 'like', "%{$search}%");
                });
            })
            ->orderBy('app_id')
            ->orderByDesc('report_date')
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
