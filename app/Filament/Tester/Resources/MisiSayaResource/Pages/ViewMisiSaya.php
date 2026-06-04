<?php

namespace App\Filament\Tester\Resources\MisiSayaResource\Pages;

use App\Filament\Tester\Resources\MisiSayaResource;
use App\Models\ApplicationTester;
use App\Models\DailyReport;
use App\Models\EvaluationAnswer;
use App\Models\EvaluationQuestion;
use App\Models\TesterProfile;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ViewMisiSaya extends Page
{
    protected static string $resource = MisiSayaResource::class;

    protected static string $view = 'filament.tester.pages.view-misi-saya';

    public ?int $recordId = null;

    public function mount(int $record): void
    {
        $this->recordId = $record;

        // Pastikan misi ini milik tester yang sedang login
        ApplicationTester::query()
            ->where('id', $record)
            ->where('tester_id', Auth::id())
            ->firstOrFail();
    }

    public function getTitle(): string
    {
        $mission = $this->getMission();
        return $mission?->application?->title ?? 'Detail Misi';
    }

    protected function getMission(): ?ApplicationTester
    {
        return ApplicationTester::with(['application.developer'])
            ->where('id', $this->recordId)
            ->where('tester_id', Auth::id())
            ->first();
    }

    protected function getViewData(): array
    {
        $mission = $this->getMission();

        if (! $mission) {
            return ['mission' => null];
        }

        $application = $mission->application;

        $startDate = $application?->start_date
            ? Carbon::parse($application->start_date)->startOfDay()
            : null;

        $endDate = $application?->end_date
            ? Carbon::parse($application->end_date)->startOfDay()
            : ($startDate ? $startDate->copy()->addDays(14) : null);

        $reportDates = DailyReport::query()->where('tester_id', Auth::id())
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
                $date       = $startDate->copy()->addDays($i);
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
                    'day'      => $i + 1,
                    'date'     => $date->translatedFormat('d F Y'),
                    'date_raw' => $dateString,
                    'status'   => $status,
                ];
            }
        }

        $mission->setAttribute('testing_start_date', $startDate);
        $mission->setAttribute('testing_end_date', $endDate);
        $mission->setAttribute('daily_reports_count_custom', $dailyReportsCount);
        $mission->setAttribute('daily_missions_custom', $dailyMissions);
        $mission->setAttribute('progress_percentage', min(100, ($dailyReportsCount / 14) * 100));

        $testingReport = \App\Models\TestingReport::query()
            ->where('application_tester_id', $mission->id)
            ->latest()
            ->first();

        return [
            'mission'       => $mission,
            'testingReport' => $testingReport,
        ];
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ACTION: Laporan Harian
    // ──────────────────────────────────────────────────────────────────────────

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

                Textarea::make('bug_report')
                    ->label('Lapor Bug')
                    ->placeholder('Contoh: Tombol "Simpan" pada halaman profil tidak merespons saat diklik.')
                    ->helperText('Isi jika kamu menemukan bug atau masalah pada aplikasi hari ini.')
                    ->rows(3),
            ])
            ->action(function (array $data): void {
                $record = $this->getMission();

                if (! $record) {
                    Notification::make()->title('Misi tidak ditemukan.')->danger()->send();
                    return;
                }

                if ($record->status !== 'active') {
                    Notification::make()->title('Misi ini sudah tidak aktif.')->danger()->send();
                    return;
                }

                if (! $record->application?->start_date) {
                    Notification::make()
                        ->title('Sesi testing belum dimulai.')
                        ->body('Tunggu developer memulai sesi testing terlebih dahulu.')
                        ->warning()->send();
                    return;
                }

                if (! $record->application?->app_link) {
                    Notification::make()
                        ->title('Link testing belum tersedia.')
                        ->body('Tunggu developer mengisi link closed testing terlebih dahulu.')
                        ->warning()->send();
                    return;
                }

                $alreadyReported = DailyReport::query()
                    ->where('tester_id', Auth::id())
                    ->where('app_id', $record->application_id)
                    ->whereDate('report_date', Carbon::today()->toDateString())
                    ->exists();

                if ($alreadyReported) {
                    Notification::make()->title('Kamu sudah mengerjakan misi hari ini.')->warning()->send();
                    return;
                }

                DailyReport::create([
                    'tester_id'   => Auth::id(),
                    'app_id'      => $record->application_id,
                    'report_date' => Carbon::today()->toDateString(),
                    'screenshot'  => $data['screenshot'],
                    'notes'       => $data['notes'] ?? null,
                    'bug_report'  => $data['bug_report'] ?? null,
                ]);

                Notification::make()
                    ->title('Misi harian berhasil dikirim!')
                    ->body('Terima kasih. Kembali lagi besok untuk melanjutkan misi berikutnya.')
                    ->success()->send();
            });
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ACTION: Kirim Laporan Akhir (sekarang dengan form evaluasi dinamis)
    // ──────────────────────────────────────────────────────────────────────────

    public function kirimLaporanAkhirAction(): Action
    {
        // Ambil pertanyaan aktif dari database
        $questions = EvaluationQuestion::active()->get();

        // Bangun komponen form secara dinamis dari pertanyaan di DB
        $evaluationFields = $questions->map(function (EvaluationQuestion $question) {
            // Buat pilihan radio dari min_scale sampai max_scale
            $options = [];
            for ($i = $question->min_scale; $i <= $question->max_scale; $i++) {
                $options[(string) $i] = (string) $i;
            }

            return Section::make("Pertanyaan {$question->order}")
                ->schema([
                    Placeholder::make("label_{$question->id}")
                        ->label('')
                        ->content($question->question_text),

                    Radio::make("ratings.{$question->id}")
                        ->label("Rating ({$question->min_scale}–{$question->max_scale})")
                        ->options($options)
                        ->required()
                        ->inline()
                        ->inlineLabel(false)
                        ->helperText("{$question->min_scale} = sangat buruk, {$question->max_scale} = sangat baik"),

                    Textarea::make("comments.{$question->id}")
                        ->label('Komentar (opsional)')
                        ->placeholder('Tuliskan pendapat atau masukan spesifik untuk aspek ini...')
                        ->rows(2),
                ])
                ->compact();
        })->toArray();

        // Gabungkan: form bukti + kuesioner evaluasi
        $formSchema = array_merge(
            [
                Section::make('Bukti Pengujian')
                    ->schema([
                        FileUpload::make('proof_image')
                            ->label('Screenshot Bukti Testing Akhir')
                            ->image()
                            ->required()
                            ->directory('proofs')
                            ->maxSize(5120),

                        Textarea::make('feedback')
                            ->label('Feedback Umum & Laporan Bug')
                            ->required()
                            ->rows(4)
                            ->placeholder('Jelaskan pengalamanmu secara keseluruhan, bug yang ditemukan, atau saran perbaikan.')
                            ->minLength(20),
                    ]),
            ],
            $evaluationFields,
        );

        return Action::make('kirimLaporanAkhir')
            ->label('Kirim Laporan Akhir & Evaluasi')
            ->icon('heroicon-s-paper-airplane')
            ->color('primary')
            ->button()
            ->modalHeading('Form Laporan Akhir & Evaluasi')
            ->modalDescription(
                'Setelah 14 hari pengujian, isi form ini untuk menyelesaikan misimu. ' .
                'Jawab seluruh pertanyaan evaluasi dengan jujur — feedback kamu sangat berarti bagi developer!'
            )
            ->modalWidth('4xl')
            ->form($formSchema)
            ->action(function (array $data): void {
                $record = $this->getMission();

                if (! $record) {
                    Notification::make()->title('Misi tidak ditemukan.')->danger()->send();
                    return;
                }

                if (! $this->canSubmitFinalReport($record)) {
                    Notification::make()
                        ->title('Laporan akhir belum bisa dikirim.')
                        ->body($this->finalReportTooltip($record))
                        ->warning()->send();
                    return;
                }

                // Cek apakah sudah pernah kirim laporan akhir + evaluasi
                $existingReport = \App\Models\TestingReport::query()
                    ->where('application_tester_id', $record->id)
                    ->whereHas('evaluationAnswers')
                    ->first();

                if ($existingReport) {
                    Notification::make()
                        ->title('Laporan akhir sudah pernah dikirim.')
                        ->body('Kamu sudah mengisi form evaluasi untuk misi ini.')
                        ->warning()->send();
                    return;
                }

                DB::transaction(function () use ($record, $data) {
                    // 1. Simpan / update ApplicationTester dengan bukti
                    $record->update([
                        'proof_image' => $data['proof_image'],
                        'feedback'    => $data['feedback'],
                    ]);

                    // 2. Buat TestingReport dengan status pending
                    $testingReport = \App\Models\TestingReport::updateOrCreate(
                        ['application_tester_id' => $record->id],
                        [
                            'file_bukti'        => $data['proof_image'],
                            'catatan'           => $data['feedback'],
                            'status'            => 'pending',
                            'alasan_penolakan'  => null,
                        ]
                    );

                    // 3. Simpan jawaban evaluasi dari form dinamis
                    $ratings  = $data['ratings'] ?? [];
                    $comments = $data['comments'] ?? [];

                    foreach ($ratings as $questionId => $ratingValue) {
                        // Validasi pertanyaan masih ada dan aktif
                        $question = EvaluationQuestion::find($questionId);
                        if (! $question) {
                            continue;
                        }

                        EvaluationAnswer::updateOrCreate(
                            [
                                'testing_report_id'       => $testingReport->id,
                                'evaluation_question_id'  => $questionId,
                            ],
                            [
                                'rating'  => (int) $ratingValue,
                                'comment' => $comments[$questionId] ?? null,
                            ]
                        );
                    }
                });

                Notification::make()
                    ->title('Laporan akhir & evaluasi berhasil dikirim!')
                    ->body('Terima kasih atas feedback-mu! Tunggu developer memvalidasi laporanmu.')
                    ->success()->send();
            });
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helper methods
    // ──────────────────────────────────────────────────────────────────────────

    public function dailyReportCount(ApplicationTester $record): int
    {
        return DailyReport::query()
            ->where('tester_id', Auth::id())
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

        $hasRunFor14Days   = $startDate->diffInDays(Carbon::today()) >= 14;
        $has14DailyReports = $this->dailyReportCount($record) >= 14;

        return $hasRunFor14Days && $has14DailyReports;
    }

    public function finalReportTooltip(ApplicationTester $record): string
    {
        if (! $record->application?->start_date) {
            return 'Sesi testing belum dimulai.';
        }

        $startDate = Carbon::parse($record->application->start_date)->startOfDay();
        $days      = $startDate->diffInDays(Carbon::today());
        $reports   = $this->dailyReportCount($record);

        if ($days < 14) {
            return 'Laporan akhir hanya bisa dikirim setelah 14 hari masa testing. Saat ini baru ' . $days . ' hari.';
        }

        if ($reports < 14) {
            return 'Kamu perlu mengirim 14 laporan harian. Saat ini baru ' . $reports . '/14 laporan.';
        }

        return 'Klik untuk mengirim laporan akhir dan mendapatkan reward 10 poin.';
    }
}
