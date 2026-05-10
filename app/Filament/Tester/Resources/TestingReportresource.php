<?php

namespace App\Filament\Tester\Resources;

use Illuminate\Support\Facades\Auth;
use App\Filament\Tester\Resources\TestingReportResource\Pages;
use App\Models\TestingReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TestingReportResource extends Resource
{
    protected static ?string $model = TestingReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';
    protected static ?string $navigationLabel = 'Laporan Bug';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kirim Laporan Bug')
                    ->schema([
                        Forms\Components\Select::make('application_tester_id')
                            ->label('Pilih Misi Aplikasi')
                            ->relationship('applicationTester', 'id', fn ($query) => $query->where('tester_id', Auth::id()))
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->application->app_name)
                            ->required()
                            ->searchable(),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Detail Bug/Temuan')
                            ->required()
                            ->rows(5),

                        Forms\Components\FileUpload::make('file_bukti')
                            ->label('Bukti Screenshot')
                            ->image()
                            ->directory('testing-reports'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applicationTester.application.app_name')->label('Aplikasi'),
                Tables\Columns\TextColumn::make('catatan')->label('Temuan')->limit(50),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Supaya tester cuma bisa lihat laporannya sendiri
        return parent::getEloquentQuery()->whereHas('applicationTester', function ($query) {
            $query->where('tester_id', Auth::id());
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestingReports::route('/'),
            'create' => Pages\CreateTestingReport::route('/create'),
            'edit' => Pages\EditTestingReport::route('/{record}/edit'),
        ];
    }
}