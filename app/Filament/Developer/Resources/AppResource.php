<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\AppResource\Pages;
use App\Models\App;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

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
                        Forms\Components\TextInput::make('title')
                            ->label('Nama Aplikasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Sistem Kasir UMKM'),
                            
                        Forms\Components\TextInput::make('platform')
                            ->label('Platform')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Android / Web / iOS'),
                            
                        Forms\Components\TextInput::make('url')
                            ->label('URL / Link Aplikasi')
                            ->url()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: https://play.google.com/...'),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Aplikasi')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Contoh: Tolong test fitur keranjang belanja dan proses checkout...'),

                        Forms\Components\FileUpload::make('payment_proof')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->directory('payment_proofs')
                            ->helperText('Biaya Upload Rp 300.000 ke Rekening XXX')
                            ->columnSpanFull(),

                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('show_qris')
                                ->label('Tampilkan Barcode QRIS')
                                ->icon('heroicon-m-qr-code')
                                ->color('primary')
                                ->modalHeading('Scan Barcode QRIS')
                                ->modalContent(fn () => new \Illuminate\Support\HtmlString('<div class="flex justify-center flex-col items-center gap-4"><img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=Pembayaran+Aplikasi+PBL" alt="QRIS Barcode" class="w-64 h-64 rounded-lg shadow-md" /><p class="text-sm text-gray-500">Silakan bayar menggunakan e-wallet atau m-banking dengan scan barcode ini.</p></div>'))
                                ->modalSubmitAction(false)
                                ->modalCancelAction(false)
                        ])->columnSpanFull(),

                        Forms\Components\FileUpload::make('review_screenshot')
                            ->label('Bukti Lulus Review Awal')
                            ->image()
                            ->directory('review_screenshots')
                            ->helperText('Upload screenshot bukti aplikasi telah lulus review awal')
                            ->columnSpanFull(),

                        Forms\Components\Select::make('payment_status')
                            ->label('Status Pembayaran')
                            ->options([
                                'pending' => 'Pending',
                                'valid' => 'Valid',
                                'invalid' => 'Tidak Valid',
                            ])
                            ->default('pending')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('testing_status')
                            ->label('Status Pengujian')
                            ->options([
                                'open' => 'Terbuka',
                                'in_progress' => 'Sedang Dites',
                                'completed' => 'Selesai',
                            ])
                            ->default('open')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'valid' => 'success',
                        'invalid' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('testing_status')
                    ->label('Status Pengujian')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'gray',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                // // Filter berdasarkan status pengujian
                // Tables\Filters\SelectFilter::make('testing_status')
                //     ->options([
                //         'open' => 'Terbuka',
                //         'in_progress' => 'Sedang Dites',
                //         'completed' => 'Selesai',
                //     ]),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('developer_id', Auth::id());
    }
}