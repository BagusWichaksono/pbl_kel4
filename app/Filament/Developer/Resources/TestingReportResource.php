<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\TestingReportResource\Pages;
use App\Models\DailyReport;
use App\Models\TestingReport;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TestingReportResource extends Resource
{
    protected static ?string $model = TestingReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Hasil Pengujian';

    protected static ?string $pluralModelLabel = 'Daftar Hasil Pengujian';

    protected static ?string $navigationGroup = 'Laporan';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('applicationTester.application', function (Builder $query) {
            $query->where('developer_id', Auth::id());
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applicationTester.application.title')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('applicationTester.tester.name')
                    ->label('Nama Tester')
                    ->searchable(),

                Tables\Columns\IconColumn::make('bug_report')
                    ->label('Ada Bug?')
                    ->boolean()
                    ->trueIcon('heroicon-o-bug-ant')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->getStateUsing(fn ($record) => !empty($record->bug_report)),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'disetujui' => 'success',
                        'pending'   => 'warning',
                        'ditolak'   => 'danger',
                        default     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dikirim')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'   => 'Pending',
                        'disetujui' => 'Disetujui',
                        'ditolak'   => 'Ditolak',
                    ]),

                Tables\Filters\Filter::make('has_bug')
                    ->label('Ada Laporan Bug Harian')
                    ->query(fn (Builder $query) => $query->whereHas('applicationTester', function ($q) {
                        $q->whereHas('dailyReports', function ($r) {
                            $r->whereNotNull('bug_report')->where('bug_report', '!=', '');
                        });
                    })),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat Detail'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Laporan')
                    ->schema([
                        TextEntry::make('applicationTester.application.title')
                            ->label('Aplikasi yang Diuji'),

                        TextEntry::make('applicationTester.tester.name')
                            ->label('Penguji (Tester)'),

                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'disetujui' => 'success',
                                'pending'   => 'warning',
                                'ditolak'   => 'danger',
                                default     => 'gray',
                            }),
                    ])
                    ->columns(3),

                Section::make('Bukti Pengujian')
                    ->schema([
                        ImageEntry::make('file_bukti')
                            ->label('Bukti Screenshot')
                            ->columnSpanFull()
                            ->height(300),

                        TextEntry::make('catatan')
                            ->label('Catatan / Ulasan Laporan')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada catatan.'),

                        TextEntry::make('alasan_penolakan')
                            ->label('Alasan Penolakan (Jika ada)')
                            ->columnSpanFull()
                            ->placeholder('—'),
                    ]),

                Section::make('Laporan Bug Harian')
                    ->description('Bug yang dilaporkan tester setiap hari selama masa testing.')
                    ->schema([
                        TextEntry::make('bug_harian')
                            ->label('')
                            ->columnSpanFull()
                            ->getStateUsing(function ($record) {
                                // Ambil tester_id dan application_id dari applicationTester
                                $testerId     = $record->applicationTester?->tester_id;
                                $applicationId = $record->applicationTester?->application_id;

                                if (!$testerId || !$applicationId) {
                                    return 'Data tester tidak ditemukan.';
                                }

                                $bugs = DailyReport::where('tester_id', $testerId)
                                    ->where('app_id', $applicationId)
                                    ->whereNotNull('bug_report')
                                    ->where('bug_report', '!=', '')
                                    ->orderBy('report_date')
                                    ->get();

                                if ($bugs->isEmpty()) {
                                    return 'Tidak ada laporan bug harian dari tester ini.';
                                }

                                return $bugs->map(function ($bug) {
                                    return '📅 ' . \Carbon\Carbon::parse($bug->report_date)->translatedFormat('d F Y') . "\n" . $bug->bug_report;
                                })->implode("\n\n");
                            })
                            ->extraAttributes(['style' => 'white-space: pre-line;']),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestingReports::route('/'),
            'view'  => Pages\ViewTestingReport::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
