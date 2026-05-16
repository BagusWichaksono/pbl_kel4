<?php

namespace App\Filament\Tester\Resources\MisiSayaResource\Pages;

use App\Filament\Tester\Resources\MisiSayaResource;
use App\Models\ApplicationTester;
use App\Models\DailyReport;
use App\Models\TesterProfile;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListMisiSayas extends Page
{
    protected static string $resource = MisiSayaResource::class;

    protected static string $view = 'filament.tester.pages.list-misi-sayas';

    public function getTitle(): string
    {
        return 'Misi Saya';
    }

    protected function getViewData(): array
    {
        $missions = ApplicationTester::with([
                'application.developer',
            ])
            ->where('tester_id', Auth::id())
            ->latest()
            ->get()
            ->map(function (ApplicationTester $mission) {
                $application = $mission->application;

                $startDate = $application?->start_date
                    ? Carbon::parse($application->start_date)->startOfDay()
                    : null;

                $endDate = $application?->end_date
                    ? Carbon::parse($application->end_date)->startOfDay()
                    : ($startDate ? $startDate->copy()->addDays(14) : null);

                $reportDates = DailyReport::where('tester_id', Auth::id())
                    ->where('app_id', $mission->application_id)
                    ->pluck('report_date')
                    ->map(fn ($date) => Carbon::parse($date)->toDateString())
                    ->unique()
                    ->values()
                    ->toArray();

                $dailyReportsCount = count($reportDates);

                $dailyMissions = [];

                if ($startDate) {
                    for ($i = 0; $i < 14; $i++) {
                        $date = $startDate->copy()->addDays($i);
                        $dateString = $date->toDateString();

                        if (in_array($dateString, $reportDates)) {
                            $status = 'done';
                        } elseif ($date->isToday()) {
                            $status = 'today';
                        } elseif ($date->isPast()) {
                            $status = 'missed';
                        } else {
                            $status = 'locked';
                        }

                        $dailyMissions[] = [
                            'day' => $i + 1,
                            'date' => $date->translatedFormat('d F Y'),
                            'date_raw' => $dateString,
                            'status' => $status,
                        ];
                    }
                }

                $mission->setAttribute('testing_start_date', $startDate);
                $mission->setAttribute('testing_end_date', $endDate);
                $mission->setAttribute('daily_reports_count_custom', $dailyReportsCount);
                $mission->setAttribute('daily_missions_custom', $dailyMissions);
                $mission->setAttribute('progress_percentage', min(100, ($dailyReportsCount / 14) * 100));

                return $mission;
            });

        return [
            'missions' => $missions,
        ];
    }

    public function laporHarianAction(): Action
    {
        return Action::make('laporHarian')
            ->label('Kerjakan Misi')
            ->icon('heroicon-o-camera')
            ->color('success')
            ->button()
            ->modalHeading('Laporan Misi Harian')
            ->modalDescription('Unggah screenshot bukti bahwa kamu sudah membuka, mencoba, atau mengecek aplikasi hari ini.')
            ->form([
                FileUpload::make('screenshot')
                    ->label('Screenshot Bukti Testing Hari Ini')
                    ->image()
                    ->directory('daily-reports')
                    ->required(),

                Textarea::make('notes')
                    ->label('Catatan Singkat')
                    ->placeholder('Contoh: Hari ini saya mencoba login dan membuka beberapa menu utama.')
                    ->rows(3),
            ])
            ->action(function (array $data, array $arguments): void {
                $record = $this->getMissionRecord($arguments['record'] ?? null);

                if (! $record) {
                    Notification::make()
                        ->title('Misi tidak ditemukan.')
                        ->danger()
                        ->send();

                    return;
                }

                if ($record->status !== 'active') {
                    Notification::make()
                        ->title('Misi ini sudah tidak aktif.')
                        ->danger()
                        ->send();

                    return;
                }

                if (! $record->application?->start_date) {
                    Notification::make()
                        ->title('Sesi testing belum dimulai.')
                        ->body('Tunggu developer memulai sesi testing terlebih dahulu.')
                        ->warning()
                        ->send();

                    return;
                }

                if (! $record->application?->app_link) {
                    Notification::make()
                        ->title('Link testing belum tersedia.')
                        ->body('Tunggu developer mengisi link closed testing terlebih dahulu.')
                        ->warning()
                        ->send();

                    return;
                }

                $alreadyReported = DailyReport::where('tester_id', Auth::id())
                    ->where('app_id', $record->application_id)
                    ->whereDate('report_date', Carbon::today()->toDateString())
                    ->exists();

                if ($alreadyReported) {
                    Notification::make()
                        ->title('Kamu sudah mengerjakan misi hari ini.')
                        ->warning()
                        ->send();

                    return;
                }

                DailyReport::create([
                    'tester_id' => Auth::id(),
                    'app_id' => $record->application_id,
                    'report_date' => Carbon::today()->toDateString(),
                    'screenshot' => $data['screenshot'],
                    'notes' => $data['notes'] ?? null,
                ]);

                Notification::make()
                    ->title('Misi harian berhasil dikirim!')
                    ->body('Terima kasih. Kembali lagi besok untuk melanjutkan misi berikutnya.')
                    ->success()
                    ->send();
            });
    }

    public function kirimLaporanAkhirAction(): Action
    {
        return Action::make('kirimLaporanAkhir')
            ->label('Kirim Laporan Akhir')
            ->icon('heroicon-s-paper-airplane')
            ->color('primary')
            ->button()
            ->modalHeading('Kirim Laporan Akhir')
            ->modalDescription('Kirim bukti akhir dan feedback setelah menyelesaikan 14 laporan harian.')
            ->form([
                FileUpload::make('proof_image')
                    ->label('Bukti Screenshot Testing')
                    ->image()
                    ->required()
                    ->directory('proofs')
                    ->maxSize(5120),

                Textarea::make('feedback')
                    ->label('Feedback & Laporan Bug')
                    ->required()
                    ->rows(5)
                    ->placeholder('Jelaskan pengalamanmu menggunakan aplikasi ini, bug yang ditemukan, atau saran perbaikan.')
                    ->minLength(50),
            ])
            ->action(function (array $data, array $arguments): void {
                $record = $this->getMissionRecord($arguments['record'] ?? null);

                if (! $record) {
                    Notification::make()
                        ->title('Misi tidak ditemukan.')
                        ->danger()
                        ->send();

                    return;
                }

                if (! $this->canSubmitFinalReport($record)) {
                    Notification::make()
                        ->title('Laporan akhir belum bisa dikirim.')
                        ->body($this->finalReportTooltip($record))
                        ->warning()
                        ->send();

                    return;
                }

                DB::transaction(function () use ($record, $data) {
                    $record->update([
                        'proof_image' => $data['proof_image'],
                        'feedback' => $data['feedback'],
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);

                    if (! $record->points_awarded) {
                        TesterProfile::firstOrCreate(
                            ['user_id' => Auth::id()],
                            ['points' => 0]
                        )->increment('points', 10);

                        $record->update([
                            'points_awarded' => true,
                        ]);
                    }
                });

                Notification::make()
                    ->title('Laporan akhir berhasil dikirim!')
                    ->body('Misi selesai. Reward 10 poin sudah ditambahkan ke saldo kamu.')
                    ->success()
                    ->send();
            });
    }

    private function getMissionRecord($recordId): ?ApplicationTester
    {
        if (! $recordId) {
            return null;
        }

        return ApplicationTester::with('application')
            ->where('tester_id', Auth::id())
            ->where('id', $recordId)
            ->first();
    }

    public function dailyReportCount(ApplicationTester $record): int
    {
        return DailyReport::where('tester_id', Auth::id())
            ->where('app_id', $record->application_id)
            ->pluck('report_date')
            ->unique()
            ->count();
    }

    public function canSubmitFinalReport(ApplicationTester $record): bool
    {
        if ($record->status !== 'active') {
            return false;
        }

        if (! $record->application?->start_date) {
            return false;
        }

        $startDate = Carbon::parse($record->application->start_date)->startOfDay();

        $hasRunFor14Days = $startDate->diffInDays(Carbon::today()) >= 14;
        $has14DailyReports = $this->dailyReportCount($record) >= 14;

        return $hasRunFor14Days && $has14DailyReports;
    }

    public function finalReportTooltip(ApplicationTester $record): string
    {
        if (! $record->application?->start_date) {
            return 'Sesi testing belum dimulai.';
        }

        $startDate = Carbon::parse($record->application->start_date)->startOfDay();
        $days = $startDate->diffInDays(Carbon::today());
        $reports = $this->dailyReportCount($record);

        if ($days < 14) {
            return 'Laporan akhir hanya bisa dikirim setelah 14 hari masa testing. Saat ini baru ' . $days . ' hari.';
        }

        if ($reports < 14) {
            return 'Kamu perlu mengirim 14 laporan harian. Saat ini baru ' . $reports . '/14 laporan.';
        }

        return 'Klik untuk mengirim laporan akhir dan mendapatkan reward 10 poin.';
    }
}