<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\CariMisiResource\Pages;
use App\Models\App;
use App\Models\ApplicationTester;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class CariMisiResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $modelLabel = 'Misi';
    protected static ?string $pluralModelLabel = 'Cari Misi';

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationLabel = 'Cari Misi';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->where('payment_status', 'valid')->withCount('testers'))
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
                            
                        Tables\Columns\TextColumn::make('developer.name')
                            ->icon('heroicon-o-user')
                            ->color('gray'),
                            
                        Tables\Columns\TextColumn::make('platform')
                            ->icon('heroicon-o-device-phone-mobile')
                            ->badge()
                            ->color('info'),

                        Tables\Columns\TextColumn::make('testers_count')
                            ->formatStateUsing(fn ($state, $record) => new HtmlString("<span class='text-sm font-medium text-gray-500'>{$state} / {$record->max_testers} Tester Terisi</span>")),
                            ])->space(2)->extraAttributes(['class' => 'p-4']),

                        Tables\Columns\TextColumn::make('info')
                            ->default('Link akses akan dikirim ke email anda oleh Google Play Console setelah kuota terpenuhi.')
                            ->color('gray')
                            ->size('xs')
                        ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('daftarMisi')
                    ->label(fn (App $record) => ApplicationTester::where('application_id', $record->id)->where('tester_id', Auth::id())->exists() ? 'Sudah Diambil' : 'Ambil Misi')
                    ->icon(fn (App $record) => ApplicationTester::where('application_id', $record->id)->where('tester_id', Auth::id())->exists() ? 'heroicon-o-check-circle' : 'heroicon-o-plus-circle')
                    ->color(fn (App $record) => ApplicationTester::where('application_id', $record->id)->where('tester_id', Auth::id())->exists() ? 'gray' : 'success')
                    ->disabled(fn (App $record) => ApplicationTester::where('application_id', $record->id)->where('tester_id', Auth::id())->exists())
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Ambil Misi Pengujian')
                    ->modalDescription('Apakah kamu yakin ingin mengambil misi ini? Kamu harus menyelesaikan instruksi untuk mendapatkan poin.')
                    ->action(function (App $record) {
                        $appId = $record->id;
                        $userId = Auth::id();

                        // Keamanan ganda
                        $isRegistered = ApplicationTester::where('application_id', $appId)
                            ->where('tester_id', $userId)
                            ->exists();

                        if ($isRegistered) {
                            Notification::make()
                                ->title('Sudah Terdaftar')
                                ->body("Kamu sudah terdaftar sebagai tester di aplikasi \"{$record->title}\".")
                                ->warning()
                                ->send();
                            return;
                        }

                        if ($record->testers_count >= $record->max_testers) {
                            Notification::make()
                                ->title('Slot Penuh')
                                ->body("Maaf, slot tester untuk aplikasi \"{$record->title}\" sudah penuh.")
                                ->danger()
                                ->send();
                            return;
                        }

                        if ($record->end_date && $record->end_date->isPast()) {
                            Notification::make()
                                ->title('Sesi Berakhir')
                                ->body("Sesi testing untuk aplikasi \"{$record->title}\" sudah berakhir.")
                                ->danger()
                                ->send();
                            return;
                        }

                        ApplicationTester::create([
                            'application_id' => $appId,
                            'tester_id' => $userId,
                            'status' => 'active',
                        ]);

                        Notification::make()
                            ->title('Berhasil Mendaftar!')
                            ->body("Kamu berhasil mendaftar sebagai tester di aplikasi \"{$record->title}\".")
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListCariMisis::route('/'),
        ];
    }
}