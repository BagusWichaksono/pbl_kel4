<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\RiwayatTransaksiResource\Pages;
use App\Models\App;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class RiwayatTransaksiResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $modelLabel       = 'Transaksi';
    protected static ?string $pluralModelLabel = 'Riwayat Transaksi';

    protected static ?string $navigationIcon  = 'heroicon-o-receipt-refund';
    protected static ?string $navigationLabel = 'Riwayat Transaksi';
    protected static ?int    $navigationSort  = 4;

    public static function canCreate(): bool { return false; }
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool { return false; }
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Aplikasi')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->badge(),

                // Status pembayaran — jika ditolak tampilkan cuplikan alasan di bawah badge
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'valid'   => 'success',
                        'invalid' => 'danger',
                        default   => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'valid'   => '✅ Disetujui',
                        'invalid' => '❌ Ditolak',
                        default   => '⏳ Menunggu',
                    })
                    ->description(fn (App $record): string =>
                        $record->payment_status === 'invalid' && ! empty($record->rejection_reason)
                            ? '⚠️ ' . \Illuminate\Support\Str::limit($record->rejection_reason, 40)
                            : ''
                    ),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu',
                        'valid'   => 'Disetujui',
                        'invalid' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (App $record): string => 'Detail Transaksi — ' . $record->title)
                    ->modalContent(fn (App $record): HtmlString => self::buildModal($record))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('2xl'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Belum Ada Transaksi')
            ->emptyStateDescription('Aplikasi yang kamu daftarkan akan muncul di sini beserta status verifikasinya.')
            ->emptyStateIcon('heroicon-o-receipt-refund');
    }

    // ─────────────────────────────────────────────────────────
    //  MODAL DETAIL
    // ─────────────────────────────────────────────────────────

    private static function buildModal(App $record): HtmlString
    {
        // ── Status verifikasi ─────────────────────────────────
        $payStatus = $record->payment_status;
        $payColor  = match ($payStatus) {
            'valid'   => '#16a34a',
            'invalid' => '#dc2626',
            default   => '#d97706',
        };
        $payBg = match ($payStatus) {
            'valid'   => '#f0fdf4',
            'invalid' => '#fef2f2',
            default   => '#fffbeb',
        };
        $payBorder = match ($payStatus) {
            'valid'   => '#bbf7d0',
            'invalid' => '#fecaca',
            default   => '#fde68a',
        };
        $payLabel = match ($payStatus) {
            'valid'   => '✅ Pembayaran Disetujui',
            'invalid' => '❌ Pembayaran Ditolak',
            default   => '⏳ Menunggu Verifikasi Admin',
        };

        $statusHtml = "
            <div style='background:{$payBg};border:2px solid {$payBorder};border-radius:14px;
                        padding:14px 18px;margin-bottom:16px;text-align:center;'>
                <p style='margin:0;font-weight:800;font-size:1rem;color:{$payColor};'>{$payLabel}</p>
                <p style='margin:4px 0 0;font-size:0.75rem;color:#64748b;'>
                    Diajukan pada: " . $record->created_at->translatedFormat('d F Y, H:i') . "
                </p>
            </div>";

        // ── Alasan penolakan ──────────────────────────────────
        $alasanHtml = '';
        if (! empty($record->rejection_reason)) {
            $alasan     = e($record->rejection_reason);
            $alasanHtml = "
                <div style='background:#fef2f2;border:1px solid #fecaca;border-radius:12px;
                            padding:14px 16px;margin-bottom:16px;'>
                    <p style='margin:0 0 8px;font-weight:700;color:#dc2626;font-size:0.85rem;'>
                        ⚠️ Alasan Penolakan dari Admin
                    </p>
                    <p style='margin:0;color:#7f1d1d;font-size:0.875rem;line-height:1.7;
                              background:white;padding:10px 12px;border-radius:8px;border:1px solid #fecaca;'>
                        {$alasan}
                    </p>
                    <p style='margin:8px 0 0;font-size:0.75rem;color:#ef4444;'>
                        💡 Silakan unggah ulang bukti pembayaran yang sesuai melalui menu <strong>Kelola Aplikasi</strong>.
                    </p>
                </div>";
        }

        // ── Bukti pembayaran ──────────────────────────────────
        $buktiHtml = '';
        if (! empty($record->payment_proof)) {
            $buktiUrl  = asset('storage/' . $record->payment_proof);
            $buktiHtml = "
                <div style='margin-bottom:16px;'>
                    <p style='margin:0 0 8px;font-weight:700;color:#374151;font-size:0.85rem;'>
                        📎 Bukti Pembayaran yang Diunggah
                    </p>
                    <a href='{$buktiUrl}' target='_blank'>
                        <img src='{$buktiUrl}'
                             style='width:100%;max-height:280px;object-fit:contain;
                                    border-radius:12px;border:1px solid #e2e8f0;
                                    background:#f8fafc;cursor:zoom-in;'
                             alt='Bukti Pembayaran'>
                    </a>
                    <p style='margin:6px 0 0;font-size:0.7rem;color:#94a3b8;text-align:center;'>
                        Klik gambar untuk membuka ukuran penuh
                    </p>
                </div>";
        } else {
            $buktiHtml = "
                <div style='background:#f8fafc;border:1px dashed #cbd5e1;border-radius:12px;
                            padding:20px;text-align:center;margin-bottom:16px;'>
                    <p style='margin:0;color:#94a3b8;font-size:0.875rem;'>
                        📎 Bukti pembayaran belum diunggah
                    </p>
                </div>";
        }

        // ── Info aplikasi ─────────────────────────────────────
        $infoHtml = "
            <div style='display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:16px;'>
                <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;'>
                    <p style='margin:0;font-size:0.7rem;color:#94a3b8;font-weight:700;text-transform:uppercase;'>Platform</p>
                    <p style='margin:4px 0 0;font-weight:600;color:#1e293b;'>" . e($record->platform) . "</p>
                </div>
                <div style='background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;'>
                    <p style='margin:0;font-size:0.7rem;color:#94a3b8;font-weight:700;text-transform:uppercase;'>Status Testing</p>
                    <p style='margin:4px 0 0;font-weight:600;color:#1e293b;capitalize'>" . e($record->testing_status) . "</p>
                </div>
            </div>";

        return new HtmlString("
            <div style='font-size:0.875rem;'>
                {$statusHtml}
                {$alasanHtml}
                {$infoHtml}
                {$buktiHtml}
            </div>
        ");
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiwayatTransaksis::route('/'),
        ];
    }

    // Hanya tampilkan aplikasi milik developer yang login
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('developer_id', Auth::id());
    }
}
