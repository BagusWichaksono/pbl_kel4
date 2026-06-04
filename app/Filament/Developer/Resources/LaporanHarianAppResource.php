<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\LaporanHarianAppResource\Pages;
use App\Models\App;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class LaporanHarianAppResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Laporan Harian Tester';

    protected static ?string $modelLabel = 'Laporan Harian';

    protected static ?string $pluralModelLabel = 'Laporan Harian Tester';

    protected static ?string $navigationGroup = 'Laporan';

    // Munculkan di atas Hasil Pengujian
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('developer_id', Auth::id())
            ->whereIn('testing_status', ['in_progress', 'completed'])
            ->withCount('testers');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('placeholder')
                        ->defaultImageUrl('https://ui-avatars.com/api/?name=App&background=0D8ABC&color=fff&size=200')
                        ->height('150px')
                        ->extraImgAttributes(['class' => 'w-full object-cover rounded-t-xl']),
                    
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('title')
                            ->weight('bold')
                            ->size('lg')
                            ->searchable(),
                            
                        Tables\Columns\TextColumn::make('platform')
                            ->icon('heroicon-o-device-phone-mobile')
                            ->badge()
                            ->searchable()
                            ->color('info'),

                        Tables\Columns\TextColumn::make('testers_count')
                            ->formatStateUsing(fn ($state, $record) => new HtmlString("<span class='text-sm font-medium text-gray-500'>{$state} / {$record->max_testers} Tester Aktif</span>")),
                    ])->space(2)->extraAttributes(['class' => 'p-4']),

                    Tables\Columns\TextColumn::make('info')
                        ->default('Pilih aplikasi ini untuk melihat detail laporan harian dari para tester.')
                        ->color('gray')
                        ->size('xs')
                ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('lihat_laporan')
                    ->label('Lihat Laporan Harian')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->color('primary')
                    ->button()
                    ->url(fn (App $record) => DailyReportResource::getUrl('index', ['tableFilters' => ['app_id' => ['value' => $record->id]]])),
            ])
            ->paginated([9, 18, 36]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanHarianApps::route('/'),
        ];
    }
}
