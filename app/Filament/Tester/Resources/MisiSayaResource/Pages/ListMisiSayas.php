<?php

namespace App\Filament\Tester\Resources\MisiSayaResource\Pages;

use App\Filament\Tester\Resources\MisiSayaResource;
use App\Models\ApplicationTester;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ListMisiSayas extends Page
{
    protected static string $resource = MisiSayaResource::class;

    protected static string $view = 'filament.tester.pages.list-misi-sayas';

    public function getTitle(): string
    {
        return 'Misi Saya';
    }

    public function getBreadcrumb(): ?string
    {
        return null;
    }

    protected function getViewData(): array
    {
        $missions = ApplicationTester::with(['application.developer'])
            ->where('tester_id', Auth::id())
            ->latest()
            ->get()
            ->map(function (ApplicationTester $mission) {
                $mission->markDroppedIfMissedDailyReport();

                $submittedReportDates = $mission->submittedDailyReportDates();
                $missedReportDates = $mission->missedDailyReportDates();
                $dailyReportsCount = $submittedReportDates->count();
                $isLockedDueMissedReport = $mission->shouldBeDroppedBecauseMissedDailyReport();

                $mission->setAttribute('daily_reports_count_custom', $dailyReportsCount);
                $mission->setAttribute('daily_report_dates_custom', $submittedReportDates);
                $mission->setAttribute('missed_daily_report_dates_custom', $missedReportDates);
                $mission->setAttribute('missed_daily_reports_count_custom', $missedReportDates->count());
                $mission->setAttribute('is_locked_due_missed_report', $isLockedDueMissedReport);
                $mission->setAttribute('progress_percentage', min(100, ($dailyReportsCount / ApplicationTester::DAILY_TESTING_DAYS) * 100));

                return $mission;
            });

        return [
            'missions' => $missions,
        ];
    }
}
