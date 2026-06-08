<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\DailyReportResource\Pages;
use App\Models\DailyReport;
use App\Support\AppNotifier;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class DailyReportResource extends Resource
{
    protected static ?string $model = DailyReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Testing';

    protected static ?string $navigationLabel = 'Laporan Harian Tester';

    protected static ?string $modelLabel = 'Laporan Harian';

    protected static ?string $pluralModelLabel = 'Laporan Harian Tester';

    protected static bool $shouldRegisterNavigation = false;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('application', function (Builder $query) {
                $query->where('developer_id', Auth::id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tester.name')
                    ->label('Nama Tester')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('application.title')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('report_date')
                    ->label('Tanggal Laporan')
                    ->date('d M Y')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(report_date, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),

                ImageColumn::make('screenshot')
                    ->label('Screenshot')
                    ->disk('public')
                    ->height(60)
                    ->width(80),

                TextColumn::make('notes')
                    ->label('Catatan')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('bug_report')
                    ->label('Laporan Bug')
                    ->limit(80)
                    ->wrap()
                    ->searchable()
                    ->placeholder('—')
                    ->color(fn ($state) => $state ? 'danger' : null),

                TextColumn::make('status')
                    ->label('Status Review')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        DailyReport::STATUS_APPROVED => 'Disetujui',
                        DailyReport::STATUS_REJECTED => 'Ditolak',
                        default => 'Menunggu Review',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        DailyReport::STATUS_APPROVED => 'success',
                        DailyReport::STATUS_REJECTED => 'danger',
                        default => 'warning',
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];

                        if (str_contains('menunggu', $search) || str_contains('pending', $search)) {
                            $matched[] = DailyReport::STATUS_PENDING;
                        }

                        if (str_contains('disetujui', $search) || str_contains('approve', $search)) {
                            $matched[] = DailyReport::STATUS_APPROVED;
                        }

                        if (str_contains('ditolak', $search) || str_contains('reject', $search)) {
                            $matched[] = DailyReport::STATUS_REJECTED;
                        }

                        return filled($matched)
                            ? $query->whereIn('status', $matched)
                            : $query->where('status', 'like', "%{$search}%");
                    }),

                TextColumn::make('created_at')
                    ->label('Dikirim Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('has_bug')
                    ->label('Ada Laporan Bug')
                    ->query(fn (Builder $query) => $query
                        ->whereNotNull('bug_report')
                        ->where('bug_report', '!=', '')
                    ),

                Tables\Filters\SelectFilter::make('app_id')
                    ->label('Aplikasi')
                    ->relationship('application', 'title', fn (Builder $query) =>
                        $query->where('developer_id', Auth::id())
                    ),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status Review')
                    ->options([
                        DailyReport::STATUS_PENDING => 'Menunggu Review',
                        DailyReport::STATUS_APPROVED => 'Disetujui',
                        DailyReport::STATUS_REJECTED => 'Ditolak',
                    ]),
            ])
            ->actions([
                // Gunakan url() agar redirect ke halaman view, bukan modal
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn (DailyReport $record) => static::getUrl('view', ['record' => $record->id])),

                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Approve Laporan Harian')
                    ->modalDescription('Laporan harian ini akan ditandai valid.')
                    ->visible(fn (DailyReport $record): bool => ($record->status ?? DailyReport::STATUS_PENDING) === DailyReport::STATUS_PENDING)
                    ->action(fn (DailyReport $record) => static::approveReport($record)),

                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reject Laporan Harian')
                    ->modalDescription('Berikan alasan agar tester dapat memperbaiki dan mengirim ulang laporan.')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Reject')
                            ->required()
                            ->rows(3),
                    ])
                    ->visible(fn (DailyReport $record): bool => ($record->status ?? DailyReport::STATUS_PENDING) === DailyReport::STATUS_PENDING)
                    ->action(fn (DailyReport $record, array $data) => static::rejectReport($record, $data['rejection_reason'])),
            ])
            ->bulkActions([])
            ->defaultSort('report_date', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Tester')
                    ->schema([
                        TextEntry::make('tester.name')
                            ->label('Nama Tester'),

                        TextEntry::make('application.title')
                            ->label('Aplikasi'),

                        ImageEntry::make('application.app_icon')
                            ->label('Logo Aplikasi')
                            ->disk('public')
                            ->height(48)
                            ->width(48)
                            ->extraImgAttributes([
                                'style' => 'width:48px;height:48px;max-width:48px;max-height:48px;object-fit:contain;border-radius:12px;padding:4px;background:#fff;',
                            ]),

                        TextEntry::make('report_date')
                            ->label('Tanggal Laporan')
                            ->date('d M Y'),

                        TextEntry::make('status')
                            ->label('Status Review')
                            ->badge()
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                DailyReport::STATUS_APPROVED => 'Disetujui',
                                DailyReport::STATUS_REJECTED => 'Ditolak',
                                default => 'Menunggu Review',
                            })
                            ->color(fn (?string $state): string => match ($state) {
                                DailyReport::STATUS_APPROVED => 'success',
                                DailyReport::STATUS_REJECTED => 'danger',
                                default => 'warning',
                            }),
                    ])
                    ->columns(4),

                Section::make('Bukti Testing')
                    ->schema([
                        ImageEntry::make('screenshot')
                            ->label('Screenshot')
                            ->disk('public')
                            ->columnSpanFull()
                            ->height(350),

                        TextEntry::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull()
                            ->placeholder('Tidak ada catatan.'),
                    ]),

                Section::make('Laporan Bug')
                    ->schema([
                        TextEntry::make('bug_report')
                            ->label('Detail Bug yang Ditemukan')
                            ->columnSpanFull()
                            ->color('danger')
                            ->placeholder('Tidak ada laporan bug pada hari ini.'),
                    ])
                    ->visible(fn ($record) => !empty($record?->bug_report)),

                Section::make('Catatan Review')
                    ->schema([
                        TextEntry::make('rejection_reason')
                            ->label('Alasan Reject')
                            ->columnSpanFull()
                            ->placeholder('-'),

                        TextEntry::make('reviewer.name')
                            ->label('Direview Oleh')
                            ->placeholder('-'),

                        TextEntry::make('reviewed_at')
                            ->label('Direview Pada')
                            ->dateTime('d M Y H:i')
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => filled($record?->reviewed_at) || filled($record?->rejection_reason)),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailyReports::route('/'),
            'view'  => Pages\ViewDailyReport::route('/{record}'),
        ];
    }

    public static function approveReport(DailyReport $record): void
    {
        $record->update([
            'status' => DailyReport::STATUS_APPROVED,
            'rejection_reason' => null,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        Notification::make()
            ->title('Laporan harian disetujui')
            ->success()
            ->send();

        AppNotifier::database(
            $record->tester,
            'Laporan harian disetujui',
            'Laporan harian untuk '.($record->application?->title ?? 'aplikasi').' pada '.($record->report_date?->translatedFormat('d M Y') ?? '-').' sudah disetujui developer.',
            'success',
        );
    }

    public static function rejectReport(DailyReport $record, string $reason): void
    {
        $record->update([
            'status' => DailyReport::STATUS_REJECTED,
            'rejection_reason' => $reason,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        Notification::make()
            ->title('Laporan harian ditolak')
            ->body('Tester dapat mengirim ulang laporan untuk tanggal tersebut.')
            ->danger()
            ->send();

        AppNotifier::database(
            $record->tester,
            'Laporan harian ditolak',
            'Laporan harian untuk '.($record->application?->title ?? 'aplikasi').' pada '.($record->report_date?->translatedFormat('d M Y') ?? '-').' ditolak. Alasan: '.$reason,
            'danger',
        );
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
}
