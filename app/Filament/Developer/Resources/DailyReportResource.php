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

class DailyReportResource extends Resource
{
    protected static ?string $model = DailyReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Aplikasi';

    protected static ?string $navigationLabel = 'Laporan Harian Tester';

    protected static ?string $modelLabel = 'Laporan Harian';

    protected static ?string $pluralModelLabel = 'Laporan Harian Tester';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tester.name')
                    ->label('Nama Tester')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('app.title')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('report_date')
                    ->label('Tanggal Laporan')
                    ->date('d M Y')
                    ->sortable(),

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

                TextColumn::make('created_at')
                    ->label('Dikirim Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat'),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('report_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDailyReports::route('/'),
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

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}