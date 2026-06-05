<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\DailyReportResource\Pages;
use App\Models\DailyReport;
use Filament\Forms\Form;
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

    protected static ?string $navigationGroup = 'Laporan';

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
            ])
            ->actions([
                // Gunakan url() agar redirect ke halaman view, bukan modal
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Detail')
                    ->url(fn (DailyReport $record) => static::getUrl('view', ['record' => $record->id])),
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

                        TextEntry::make('report_date')
                            ->label('Tanggal Laporan')
                            ->date('d M Y'),
                    ])
                    ->columns(3),

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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailyReports::route('/'),
            'view'  => Pages\ViewDailyReport::route('/{record}'),
        ];
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
}
