<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\AppResource\Pages;
use App\Models\App;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AppResource extends Resource
{
    protected static ?string $model = App::class;
    
    protected static ?string $modelLabel = 'Aplikasi';
    protected static ?string $pluralModelLabel = 'Daftar Aplikasi';

    // Ikon di sidebar
    protected static ?string $navigationIcon = 'heroicon-o-device-phone-mobile';
    
    // Nama menu di sidebar
    protected static ?string $navigationLabel = 'Kelola Aplikasi';
    
    // Urutan menu (di bawah Dashboard)
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Utama')
                    ->description('Masukkan detail aplikasi yang ingin divalidasi oleh Tester.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Aplikasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Sistem Kasir UMKM'),
                            
                        Forms\Components\Select::make('platform')
                            ->label('Platform Target')
                            ->options([
                                'android' => 'Android (APK)',
                                'ios' => 'iOS (TestFlight)',
                                'web' => 'Website / Web App',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('app_link')
                            ->label('Link URL / Tempat Download File')
                            ->url()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('https://...'),
                            
                        Forms\Components\Textarea::make('description')
                            ->label('Skenario Pengujian (Instruksi untuk Tester)')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Contoh: Tolong test fitur keranjang belanja dan proses checkout...'),

                        Forms\Components\Select::make('status')
                            ->label('Status Rilis')
                            ->options([
                                'draft' => 'Draft (Belum Dites)',
                                'active' => 'Aktif (Sedang Dites)',
                                'finished' => 'Selesai (Siap Rilis)',
                            ])
                            ->default('draft')
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'android' => 'success',
                        'ios' => 'info',
                        'web' => 'warning',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'finished' => 'primary',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                // Filter berdasarkan status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Aktif',
                        'finished' => 'Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Kosongkan dulu
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApps::route('/'),
            'create' => Pages\CreateApp::route('/create'),
            'edit' => Pages\EditApp::route('/{record}/edit'),
        ];
    }
}