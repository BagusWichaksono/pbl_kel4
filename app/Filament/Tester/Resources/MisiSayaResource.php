<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\MisiSayaResource\Pages;
use App\Models\ApplicationTester;
use App\Models\DailyReport;
use App\Models\TesterProfile;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MisiSayaResource extends Resource
{
    protected static ?string $model = ApplicationTester::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Misi Saya';

    protected static ?string $modelLabel = 'Misi Saya';

    protected static ?string $pluralModelLabel = 'Misi Saya';

    protected static ?string $navigationGroup = 'Aktivitas Testing';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tester_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('application.title')
                    ->label('Nama Aplikasi')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('application.developer.name')
                    ->label('Developer')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Misi')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'active' => 'warning',
                        'completed' => 'success',
                        'failed', 'dropped' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'failed' => 'Gagal',
                        'dropped' => 'Berhenti',
                        default => '-',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Diambil')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('daily_reports_count')
                    ->label('Laporan Harian')
                    ->counts('dailyReports')
                    ->suffix('/14')
                    ->badge()
                    ->color('info'),
            ])
            ->actions([
                Tables\Actions\Action::make('cekEmail')
                    ->label('Cara Akses')
                    ->icon('heroicon-o-envelope')
                    ->color('gray')
                    ->button()
                    ->outlined()
                    ->modalHeading('Prosedur Akses Aplikasi')
                    ->modalDescription('Developer akan mendaftarkan email kamu ke Google Play Console. Link undangan resmi akan dikirim oleh Google ke email kamu setelah kuota tester terpenuhi. Silakan cek Inbox/Spam email secara berkala.')
                    ->modalSubmitAction(false)
                    ->visible(fn (ApplicationTester $record): bool => $record->status === 'active'),

                Tables\Actions\ViewAction::make()
                    ->label('Lihat Tugas')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->button()
                    ->outlined()
                    ->form([
                        Forms\Components\TextInput::make('nama_aplikasi_display')
                            ->label('Aplikasi')
                            ->formatStateUsing(fn (ApplicationTester $record) => $record->application?->title ?? '-')
                            ->disabled(),

                        Forms\Components\Textarea::make('deskripsi_display')
                            ->label('Instruksi / Misi Harian')
                            ->formatStateUsing(fn (ApplicationTester $record) => $record->application?->description ?? '-')
                            ->disabled()
                            ->rows(5),
                    ]),

                Tables\Actions\Action::make('lapor_harian')
                    ->label(function (ApplicationTester $record): string {
                        return self::hasReportedToday($record)
                            ? 'Sudah Lapor Hari Ini'
                            : 'Lapor Harian';
                    })
                    ->icon('heroicon-o-camera')
                    ->color('success')
                    ->button()
                    ->modalHeading('Laporan Pengecekan Harian')
                    ->modalDescription('Unggah screenshot bukti kamu sudah mengecek dan mencoba aplikasi hari ini. Misi harian hanya bisa dilakukan 1x sehari.')
                    ->form([
                        FileUpload::make('screenshot')
                            ->label('Bukti Screenshot Hari Ini')
                            ->image()
                            ->directory('daily-reports')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Catatan Singkat')
                            ->placeholder('Contoh: Hari ini saya cek menu login, aman tidak ada bug.')
                            ->rows(3),
                    ])
                    ->action(function (ApplicationTester $record, array $data) {
                        DailyReport::create([
                            'tester_id' => Auth::id(),
                            'app_id' => $record->application_id,
                            'report_date' => Carbon::today()->toDateString(),
                            'screenshot' => $data['screenshot'],
                            'notes' => $data['notes'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Misi Harian Selesai!')
                            ->description('Terima kasih. Jangan lupa kembali lagi besok untuk laporan berikutnya.')
                            ->success()
                            ->send();
                    })
                    ->disabled(fn (ApplicationTester $record): bool => self::hasReportedToday($record))
                    ->visible(fn (ApplicationTester $record): bool => $record->status === 'active'),

                Tables\Actions\Action::make('kirimLaporan')
                    ->label('Kirim Laporan Akhir')
                    ->icon('heroicon-s-paper-airplane')
                    ->color('primary')
                    ->button()
                    ->disabled(fn (ApplicationTester $record): bool => ! self::canSubmitFinalReport($record))
                    ->tooltip(fn (ApplicationTester $record): string => self::finalReportTooltip($record))
                    ->visible(fn (ApplicationTester $record): bool => $record->status === 'active')
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
                            ->placeholder('Jelaskan pengalamanmu menggunakan aplikasi ini...')
                            ->minLength(50),
                    ])
                    ->action(function (ApplicationTester $record, array $data) {
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
                            ->title('Laporan Berhasil Dikirim!')
                            ->body('Misi selesai. Reward 10 poin sudah ditambahkan ke saldo kamu.')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    private static function hasReportedToday(ApplicationTester $record): bool
    {
        return DailyReport::where('tester_id', Auth::id())
            ->where('app_id', $record->application_id)
            ->whereDate('report_date', Carbon::today()->toDateString())
            ->exists();
    }

    private static function dailyReportCount(ApplicationTester $record): int
    {
        return DailyReport::where('tester_id', Auth::id())
            ->where('app_id', $record->application_id)
            ->count();
    }

    private static function canSubmitFinalReport(ApplicationTester $record): bool
    {
        $hasRunFor14Days = $record->created_at->diffInDays(now()) >= 14;
        $has14DailyReports = self::dailyReportCount($record) >= 14;

        return $hasRunFor14Days && $has14DailyReports;
    }

    private static function finalReportTooltip(ApplicationTester $record): string
    {
        $days = $record->created_at->diffInDays(now());
        $reports = self::dailyReportCount($record);

        if ($days < 14) {
            return 'Laporan akhir hanya bisa dikirim setelah 14 hari masa testing. Saat ini baru ' . $days . ' hari.';
        }

        if ($reports < 14) {
            return 'Kamu perlu mengirim 14 laporan harian. Saat ini baru ' . $reports . '/14 laporan.';
        }

        return 'Klik untuk mengirim laporan akhir dan mendapatkan reward 10 poin.';
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMisiSayas::route('/'),
        ];
    }
}