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

    protected static ?string $modelLabel       = 'Misi';
    protected static ?string $pluralModelLabel = 'Cari Misi';

    protected static ?string $navigationIcon  = 'heroicon-o-magnifying-glass';
    protected static ?string $navigationLabel = 'Cari Misi';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                    ->where('payment_status', 'valid')
                    ->withCount('testers')
            )
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
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
                        ->badge(),

                    // Slot tester
                    Tables\Columns\TextColumn::make('testers_count')
                        ->label('Slot Tester')
                        ->formatStateUsing(function ($state, App $record): HtmlString {
                            $isFull = $state >= $record->max_testers;
                            $color  = $isFull ? 'text-red-500' : 'text-gray-500';
                            $label  = $isFull ? '🔴 Slot Penuh' : "🟢 {$state} / {$record->max_testers} Tester Terisi";
                            return new HtmlString("<span class='text-sm {$color}'>{$label}</span>");
                        }),

                    // Indikator status link — memberi tahu tester apakah link sudah tersedia
                    Tables\Columns\TextColumn::make('app_url')
                        ->label('Link Aplikasi')
                        ->formatStateUsing(function ($state, App $record): HtmlString {
                            if (! empty($state)) {
                                return new HtmlString("<span class='text-xs text-green-600'>✅ Link tersedia setelah mendaftar</span>");
                            }
                            if ($record->testers_count >= $record->max_testers) {
                                return new HtmlString("<span class='text-xs text-yellow-500'>⌛ Menunggu developer kirim link</span>");
                            }
                            return new HtmlString("<span class='text-xs text-gray-400'>Link dikirim setelah slot penuh</span>");
                        }),

                ])->space(2),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('daftarMisi')
                    ->label('Daftar Misi')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalHeading('Daftar Sebagai Tester?')
                    ->modalDescription('Setelah semua slot terisi (20 tester), developer akan mengirim link aplikasi yang bisa kamu akses di halaman "Misi Saya".')
                    ->action(function (App $record) {
                        $appId  = $record->id;
                        $userId = Auth::id();

                        // Sudah terdaftar?
                        $isRegistered = ApplicationTester::where('application_id', $appId)
                            ->where('tester_id', $userId)
                            ->exists();

                        if ($isRegistered) {
                            Notification::make()
                                ->title('Sudah Terdaftar')
                                ->body("Kamu sudah terdaftar sebagai tester di aplikasi \"{$record->title}\". Cek di halaman Misi Saya.")
                                ->warning()
                                ->send();
                            return;
                        }

                        // Slot penuh?
                        if ($record->testers_count >= $record->max_testers) {
                            Notification::make()
                                ->title('Slot Penuh')
                                ->body("Maaf, slot tester untuk aplikasi \"{$record->title}\" sudah penuh.")
                                ->danger()
                                ->send();
                            return;
                        }

                        // Sesi sudah berakhir?
                        if ($record->end_date && $record->end_date->isPast()) {
                            Notification::make()
                                ->title('Sesi Berakhir')
                                ->body("Sesi testing untuk aplikasi \"{$record->title}\" sudah berakhir.")
                                ->danger()
                                ->send();
                            return;
                        }

                        // Daftarkan tester
                        ApplicationTester::create([
                            'application_id' => $appId,
                            'tester_id'      => $userId,
                            'status'         => 'active',
                        ]);

                        Notification::make()
                            ->title('Berhasil Mendaftar! 🎉')
                            ->body("Kamu berhasil mendaftar di \"{$record->title}\". Link aplikasi akan muncul di halaman Misi Saya setelah semua slot terisi dan developer mengirim link.")
                            ->success()
                            ->send();
                    }),
            ]);
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
