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
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

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

                // Indikator cepat jika ada penolakan
                Tables\Columns\IconColumn::make('rejection_reason')
                    ->label('Penolakan')
                    ->icon(fn ($state): string => $state ? 'heroicon-o-exclamation-circle' : 'heroicon-o-check-circle')
                    ->color(fn ($state): string => $state ? 'danger' : 'gray')
                    ->tooltip(fn ($state): string => $state ? 'Ada alasan penolakan — klik Detail Transaksi' : 'Tidak ada penolakan'),
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
                Tables\Actions\Action::make('detail_transaksi')
                    ->label('Detail Transaksi')
                    ->icon('heroicon-o-receipt-refund')
                    ->color('info')
                    ->modalHeading(fn (App $record): string => 'Riwayat Transaksi — ' . $record->title)
                    ->modalContent(fn (App $record): HtmlString => self::buildTransaksiModal($record))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('2xl'),

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

    // ─────────────────────────────────────────────────────────
    //  MODAL DETAIL TRANSAKSI
    // ─────────────────────────────────────────────────────────

    private static function buildTransaksiModal(App $record): HtmlString
    {
        // Ambil semua transaksi untuk aplikasi ini
        $transaksis = Transaction::where('application_id', $record->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // ── Blok status verifikasi ──────────────────────────
        $payStatus   = $record->payment_status;
        $testStatus  = $record->testing_status;

        $payColor = match ($payStatus) {
            'valid'   => '#16a34a', 'invalid' => '#dc2626', default => '#d97706',
        };
        $payLabel = match ($payStatus) {
            'valid'   => '✅ Disetujui', 'invalid' => '❌ Ditolak', default => '⏳ Menunggu',
        };
        $testColor = match ($testStatus) {
            'approved' => '#16a34a', 'rejected' => '#dc2626', default => '#d97706',
        };
        $testLabel = match ($testStatus) {
            'approved' => '✅ Disetujui', 'rejected' => '❌ Ditolak', default => '⏳ Menunggu',
        };

        $statusHtml = "
            <div style='display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;'>
                <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px;text-align:center;'>
                    <p style='margin:0;font-size:0.7rem;color:#64748b;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;'>Status Pembayaran</p>
                    <p style='margin:6px 0 0;font-weight:800;color:{$payColor};font-size:0.9rem;'>{$payLabel}</p>
                </div>
                <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:12px;text-align:center;'>
                    <p style='margin:0;font-size:0.7rem;color:#64748b;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;'>Status MVP</p>
                    <p style='margin:6px 0 0;font-weight:800;color:{$testColor};font-size:0.9rem;'>{$testLabel}</p>
                </div>
            </div>";

        // ── Alasan penolakan (jika ada) ─────────────────────
        $alasanHtml = '';
        if (! empty($record->rejection_reason)) {
            $alasan     = e($record->rejection_reason);
            $alasanHtml = "
                <div style='background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px 16px;margin-bottom:16px;'>
                    <p style='margin:0 0 6px;font-weight:700;color:#dc2626;font-size:0.8rem;'>
                        ⚠️ Alasan Penolakan dari Admin
                    </p>
                    <p style='margin:0;color:#7f1d1d;font-size:0.875rem;line-height:1.6;'>{$alasan}</p>
                </div>";
        }

        // ── Daftar transaksi ────────────────────────────────
        $transaksiHtml = '';
        if ($transaksis->isEmpty()) {
            $transaksiHtml = "
                <div style='text-align:center;padding:24px;color:#94a3b8;font-size:0.875rem;'>
                    Belum ada data transaksi untuk aplikasi ini.
                </div>";
        } else {
            foreach ($transaksis as $i => $t) {
                $no         = $i + 1;
                $tgl        = $t->created_at->translatedFormat('d F Y, H:i');
                $nominal    = 'Rp ' . number_format($t->amount, 0, ',', '.');
                $stColor    = match ($t->status) {
                    'approved' => '#16a34a', 'rejected' => '#dc2626', default => '#d97706',
                };
                $stBg       = match ($t->status) {
                    'approved' => '#f0fdf4', 'rejected' => '#fef2f2', default => '#fffbeb',
                };
                $stBorder   = match ($t->status) {
                    'approved' => '#bbf7d0', 'rejected' => '#fecaca', default => '#fde68a',
                };
                $stLabel    = match ($t->status) {
                    'approved' => 'Lunas', 'rejected' => 'Ditolak', default => 'Menunggu',
                };

                // SS bukti pembayaran
                $buktiHtml = '';
                if (! empty($t->payment_proof)) {
                    $buktiUrl  = asset('storage/' . $t->payment_proof);
                    $buktiHtml = "
                        <div style='margin-top:10px;'>
                            <p style='margin:0 0 6px;font-size:0.75rem;color:#64748b;font-weight:600;'>📎 Bukti Pembayaran</p>
                            <a href='{$buktiUrl}' target='_blank' style='display:block;'>
                                <img src='{$buktiUrl}'
                                     style='width:100%;max-height:200px;object-fit:contain;border-radius:10px;border:1px solid #e2e8f0;background:#f8fafc;cursor:pointer;'
                                     alt='Bukti Pembayaran'
                                     title='Klik untuk buka gambar penuh'>
                            </a>
                            <p style='margin:4px 0 0;font-size:0.7rem;color:#94a3b8;text-align:center;'>Klik gambar untuk membuka ukuran penuh</p>
                        </div>";
                } else {
                    $buktiHtml = "
                        <p style='margin-top:10px;font-size:0.8rem;color:#94a3b8;'>
                            📎 Bukti pembayaran tidak tersedia
                        </p>";
                }

                $transaksiHtml .= "
                    <div style='background:{$stBg};border:1px solid {$stBorder};border-radius:14px;padding:14px 16px;margin-bottom:10px;'>
                        <div style='display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;'>
                            <div>
                                <p style='margin:0;font-weight:700;color:#1e293b;font-size:0.875rem;'>Transaksi #{$no}</p>
                                <p style='margin:2px 0 0;color:#64748b;font-size:0.75rem;'>{$tgl}</p>
                            </div>
                            <div style='text-align:right;'>
                                <p style='margin:0;font-weight:800;color:{$stColor};font-size:0.95rem;'>{$nominal}</p>
                                <span style='display:inline-block;padding:2px 10px;border-radius:999px;background:{$stColor};color:white;font-size:0.7rem;font-weight:700;margin-top:3px;'>{$stLabel}</span>
                            </div>
                        </div>
                        {$buktiHtml}
                    </div>";
            }
        }

        return new HtmlString("
            <div style='font-size:0.875rem;'>
                {$statusHtml}
                {$alasanHtml}
                <p style='margin:0 0 10px;font-weight:700;color:#374151;font-size:0.8rem;text-transform:uppercase;letter-spacing:0.05em;'>
                    📋 Riwayat Transaksi
                </p>
                {$transaksiHtml}
            </div>
        ");
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('developer_id', Auth::id());
    }
}
