<?php

namespace App\Filament\Tester\Resources\MisiSayaResource\Pages;

use App\Filament\Tester\Resources\MisiSayaResource;
use App\Models\ApplicationTester;
use App\Models\DailyReport;
use Carbon\Carbon;
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
                $dailyReportsCount = DailyReport::where('tester_id', Auth::id())
                    ->where('app_id', $mission->application_id)
                    ->count();

                $mission->setAttribute('daily_reports_count_custom', $dailyReportsCount);
                $mission->setAttribute('progress_percentage', min(100, ($dailyReportsCount / 14) * 100));

                return $mission;
            });

        return [
            'missions' => $missions,
        ];
    }
}
