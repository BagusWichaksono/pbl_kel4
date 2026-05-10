<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\AppResource\Pages;
use App\Models\App;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class AppResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $modelLabel        = 'Aplikasi';
    protected static ?string $pluralModelLabel  = 'Daftar Aplikasi';

    protected static ?string $navigationIcon   = 'heroicon-o-device-phone-mobile';
    protected static ?string $navigationLabel  = 'Kelola Aplikasi';
    protected static ?int    $navigationSort   = 2;

    // ─────────────────────────────────────────────────────────
    //  FORM  (dipakai di Create & Edit)
    // ─────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // ── SECTION 1: Informasi Utama ──────────────────────────
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

                        // ── FIELD URL DIHAPUS dari create.
                        //    Developer akan mengirim link nanti lewat section "Kirim Link"
                        //    setelah tester mencapai batas maksimal.

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
                                ->modalContent(fn () => new HtmlString(
                                    '<div class="flex justify-center flex-col items-center gap-4">'
                                    . '<img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=Pembayaran+Aplikasi+PBL" '
                                    . 'alt="QRIS Barcode" class="w-64 h-64 rounded-lg shadow-md" />'
                                    . '<p class="text-sm text-gray-500">Silakan bayar menggunakan e-wallet atau m-banking dengan scan barcode ini.</p>'
                                    . '</div>'
                                ))
                                ->modalSubmitAction(false)
                                ->modalCancelAction(false),
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
                                'valid'   => 'Valid',
                                'invalid' => 'Tidak Valid',
                            ])
                            ->default('pending')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('testing_status')
                            ->label('Status Pengujian')
                            ->options([
                                'open'        => 'Terbuka',
                                'in_progress' => 'Sedang Dites',
                                'completed'   => 'Selesai',
                            ])
                            ->default('open')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                // ── SECTION 2: Kirim Link ke Tester (hanya muncul di Edit) ──
                //
                //   Logika tampil/sembunyi ditangani di EditApp::afterFill()
                //   dengan ->visible() berdasarkan kondisi record.
                //   Di sini kita pakai ->hidden() yang dikontrol dari luar
                //   via EditApp page dengan cara paling sederhana:
                //   section ini selalu dirender tapi kita sembunyikan
                //   menggunakan ->visibleOn('edit') + kondisi tester.

                Forms\Components\Section::make('Kirim Link Aplikasi ke Tester')
                    ->description(
                        fn (Get $get, ?App $record) => $record
                            ? self::getLinkSectionDescription($record)
                            : ''
                    )
                    ->schema([
                        // Info jumlah tester saat ini (read-only, dekoratif)
                        Forms\Components\Placeholder::make('tester_info')
                            ->label('Status Pendaftar')
                            ->content(function (?App $record): HtmlString {
                                if (! $record) {
                                    return new HtmlString('');
                                }

                                $count    = $record->testers()->count();
                                $max      = $record->max_testers;
                                $isFull   = $count >= $max;
                                $color    = $isFull ? 'text-green-600' : 'text-yellow-600';
                                $icon     = $isFull ? '✅' : '⏳';
                                $msg      = $isFull
                                    ? "Slot penuh! ({$count}/{$max}) — Kamu sudah bisa mengirim link ke tester."
                                    : "Belum penuh ({$count}/{$max})";

                                return new HtmlString(
                                    "<span class='{$color} font-semibold'>{$icon} {$msg}</span>"
                                );
                            }),

                        Forms\Components\TextInput::make('app_url')
                            ->label('Link / URL Aplikasi')
                            ->url()
                            ->maxLength(500)
                            ->placeholder('Contoh: https://play.google.com/store/apps/...')
                            ->helperText('Link ini akan langsung tampil di halaman tester yang sudah mendaftar.')
                            ->disabled(fn (?App $record): bool => $record ? ! $record->isFull() : true)
                            ->columnSpanFull(),

                        // Tombol "Kirim Link" — action khusus, tidak submit form biasa
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('kirim_link')
                                ->label('Simpan & Kirim Link ke Tester')
                                ->icon('heroicon-o-paper-airplane')
                                ->color('success')
                                ->requiresConfirmation()
                                ->modalHeading('Kirim Link ke Tester?')
                                ->modalDescription('Link ini akan langsung bisa diakses oleh semua tester yang sudah mendaftar. Pastikan link sudah benar.')
                                ->disabled(fn (?App $record): bool => $record ? ! $record->isFull() : true)
                                ->action(function (Forms\Set $set, Forms\Get $get, ?App $record) {
                                    $url = $get('app_url');

                                    if (empty($url)) {
                                        Notification::make()
                                            ->title('Link Kosong')
                                            ->body('Harap isi link aplikasi terlebih dahulu.')
                                            ->warning()
                                            ->send();
                                        return;
                                    }

                                    if (! $record) {
                                        return;
                                    }

                                    $record->update(['app_url' => $url]);

                                    Notification::make()
                                        ->title('Link Berhasil Dikirim!')
                                        ->body('Link aplikasi sudah dapat diakses oleh ' . $record->testers()->count() . ' tester yang terdaftar.')
                                        ->success()
                                        ->send();
                                }),
                        ])->columnSpanFull(),
                    ])
                    ->columns(1)
                    // Section ini hanya tampil di halaman Edit, bukan Create
                    ->visibleOn('edit'),
            ]);
    }

    // ─────────────────────────────────────────────────────────
    //  TABLE
    // ─────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('testers_count')
                    ->label('Tester')
                    ->counts('testers')
                    ->formatStateUsing(fn ($state, App $record): string => "{$state} / {$record->max_testers}")
                    ->badge()
                    ->color(fn ($state, App $record): string => $record->testers()->count() >= $record->max_testers ? 'success' : 'warning'),

                // Indikator apakah link sudah dikirim
                Tables\Columns\IconColumn::make('app_url')
                    ->label('Link Terkirim')
                    ->icon(fn ($state): string => $state ? 'heroicon-o-check-circle' : 'heroicon-o-clock')
                    ->color(fn ($state): string => $state ? 'success' : 'gray')
                    ->tooltip(fn ($state): string => $state ? 'Link sudah dikirim ke tester' : 'Link belum dikirim'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'valid'   => 'success',
                        'invalid' => 'danger',
                        default   => 'gray',
                    }),

                Tables\Columns\TextColumn::make('testing_status')
                    ->label('Status Pengujian')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open'        => 'gray',
                        'in_progress' => 'info',
                        'completed'   => 'success',
                        default       => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([])
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

    // ─────────────────────────────────────────────────────────
    //  PAGES & QUERY
    // ─────────────────────────────────────────────────────────

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListApps::route('/'),
            'create' => Pages\CreateApp::route('/create'),
            'edit'   => Pages\EditApp::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // Developer hanya bisa melihat aplikasi miliknya sendiri
        return parent::getEloquentQuery()->where('developer_id', Auth::id());
    }

    // ─────────────────────────────────────────────────────────
    //  HELPER PRIVATE
    // ─────────────────────────────────────────────────────────

    private static function getLinkSectionDescription(App $record): string
    {
        $count  = $record->testers()->count();
        $max    = $record->max_testers;
        $isFull = $count >= $max;

        if ($record->hasAppUrl()) {
            return "✅ Link sudah terkirim. Tester bisa mengakses aplikasimu sekarang.";
        }

        if ($isFull) {
            return "Slot tester sudah penuh ({$count}/{$max}). Kamu bisa mengirim link aplikasi ke semua tester yang terdaftar.";
        }

        return "Link baru bisa dikirim setelah semua slot tester terisi ({$count}/{$max} terdaftar).";
    }
}
