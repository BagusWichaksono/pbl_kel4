<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VerifikasiSaldoResource\Pages;
use App\Models\TesterProfile;
use App\Models\Withdrawal;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class VerifikasiSaldoResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $modelLabel       = 'Pencairan Saldo';
    protected static ?string $pluralModelLabel = 'Verifikasi Pencairan Saldo';

    protected static ?string $navigationIcon  = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Pencairan Saldo';
    protected static ?string $navigationGroup = 'Validasi & Keuangan';
    protected static ?int    $navigationSort  = 3;

    // Badge jumlah pencairan pending di sidebar
    public static function getNavigationBadge(): ?string
    {
        $count = Withdrawal::where('status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && in_array($user->role, ['admin', 'super_admin']);
    }

    public static function canCreate(): bool
    {
        return false; // Admin hanya verifikasi, tidak membuat
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    // ─────────────────────────────────────────────────────────
    //  TABLE
    // ─────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Info tester
                Tables\Columns\TextColumn::make('tester.name')
                    ->label('Nama Tester')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (Withdrawal $record): string =>
                        $record->tester->email ?? '-'
                    ),

                // E-wallet tujuan
                Tables\Columns\TextColumn::make('e_wallet_provider')
                    ->label('E-Wallet')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'DANA'       => 'info',
                        'GoPay'      => 'success',
                        'OVO'        => 'warning',
                        'ShopeePay'  => 'danger',
                        default      => 'gray',
                    })
                    ->description(fn (Withdrawal $record): string =>
                        '📱 ' . $record->e_wallet_number
                    ),

                // Poin yang ditarik
                Tables\Columns\TextColumn::make('points_withdrawn')
                    ->label('Poin')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn ($state): string => number_format($state) . ' pts'),

                // Nominal rupiah
                Tables\Columns\TextColumn::make('amount_rp')
                    ->label('Nominal')
                    ->formatStateUsing(fn ($state): string =>
                        'Rp ' . number_format($state, 0, ',', '.')
                    )
                    ->weight('bold')
                    ->color('success'),

                // Saldo poin tester saat ini
                Tables\Columns\TextColumn::make('tester.testerProfile.points')
                    ->label('Saldo Poin Tester')
                    ->formatStateUsing(fn ($state): string =>
                        number_format((int) $state) . ' pts'
                    )
                    ->badge()
                    ->color('gray')
                    ->tooltip('Saldo poin tester saat ini di sistem'),

                // Status
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending'  => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending'  => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default    => $state,
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'asc') // yang paling lama menunggu di atas
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending'  => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('pending'),

                Tables\Filters\SelectFilter::make('e_wallet_provider')
                    ->label('E-Wallet')
                    ->options([
                        'DANA'      => 'DANA',
                        'GoPay'     => 'GoPay',
                        'OVO'       => 'OVO',
                        'ShopeePay' => 'ShopeePay',
                    ]),
            ])
            ->actions([
                // Tombol detail — tampilkan info lengkap sebelum approve
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-m-eye')
                    ->color('info')
                    ->modalHeading(fn (Withdrawal $record): string =>
                        'Detail Pencairan — ' . ($record->tester->name ?? '-')
                    )
                    ->modalContent(fn (Withdrawal $record): HtmlString =>
                        self::buildDetailModal($record)
                    )
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                // Tombol approve + kurangi poin tester
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pencairan Saldo?')
                    ->modalDescription(fn (Withdrawal $record): string =>
                        'Kamu akan menyetujui pencairan ' . number_format($record->points_withdrawn) . ' poin '
                        . '(Rp ' . number_format($record->amount_rp, 0, ',', '.') . ') '
                        . 'ke ' . $record->e_wallet_provider . ' ' . $record->e_wallet_number . '. '
                        . 'Poin tester akan langsung dikurangi.'
                    )
                    ->visible(fn (Withdrawal $record): bool => $record->status === 'pending')
                    ->action(function (Withdrawal $record): void {
                        $profile = TesterProfile::where('user_id', $record->tester_id)->first();

                        // Pastikan poin masih cukup
                        if ($profile && $profile->points < $record->points_withdrawn) {
                            Notification::make()
                                ->title('Poin Tidak Cukup')
                                ->body('Saldo poin tester tidak mencukupi untuk pencairan ini.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Kurangi poin tester
                        if ($profile) {
                            $profile->decrement('points', $record->points_withdrawn);
                        }

                        // Update status withdrawal
                        $record->update(['status' => 'approved']);

                        Notification::make()
                            ->title('Pencairan Disetujui ✅')
                            ->body(
                                'Pencairan ' . number_format($record->points_withdrawn) . ' poin milik '
                                . ($record->tester->name ?? 'tester') . ' berhasil disetujui. '
                                . 'Poin sudah dikurangi dari akun tester.'
                            )
                            ->success()
                            ->send();
                    }),

                // Tombol tolak
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pencairan?')
                    ->modalDescription('Pengajuan pencairan ini akan ditolak. Poin tester tidak akan dikurangi.')
                    ->visible(fn (Withdrawal $record): bool => $record->status === 'pending')
                    ->action(function (Withdrawal $record): void {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->title('Pencairan Ditolak')
                            ->body('Pengajuan pencairan dari ' . ($record->tester->name ?? 'tester') . ' telah ditolak.')
                            ->danger()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Tidak Ada Pengajuan Pencairan')
            ->emptyStateDescription('Belum ada tester yang mengajukan pencairan saldo saat ini.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }

    // ─────────────────────────────────────────────────────────
    //  MODAL DETAIL
    // ─────────────────────────────────────────────────────────

    private static function buildDetailModal(Withdrawal $record): HtmlString
    {
        $profile      = $record->tester->testerProfile;
        $saldoPoin    = $profile ? number_format($profile->points) : '0';
        $nama         = e($record->tester->name ?? '-');
        $email        = e($record->tester->email ?? '-');
        $ewallet      = e($record->e_wallet_provider);
        $nomorEwallet = e($record->e_wallet_number);
        $poin         = number_format($record->points_withdrawn);
        $rupiah       = 'Rp ' . number_format($record->amount_rp, 0, ',', '.');
        $tanggal      = $record->created_at->translatedFormat('d F Y, H:i');

        $statusColor = match ($record->status) {
            'approved' => '#16a34a',
            'rejected' => '#dc2626',
            default    => '#d97706',
        };
        $statusLabel = match ($record->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default    => 'Menunggu Verifikasi',
        };

        // Cek cukup poin atau tidak
        $cukup       = $profile && $profile->points >= $record->points_withdrawn;
        $warningHtml = ! $cukup && $record->status === 'pending'
            ? "<div style='background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:10px 14px;margin-top:12px;color:#b91c1c;font-size:0.8rem;'>
                ⚠️ <strong>Perhatian:</strong> Saldo poin tester ({$saldoPoin} pts) kurang dari jumlah yang diminta ({$poin} pts). Pertimbangkan untuk menolak pengajuan ini.
               </div>"
            : '';

        return new HtmlString("
            <div style='font-size:0.875rem; display:flex; flex-direction:column; gap:14px;'>

                <!-- Header status -->
                <div style='text-align:center; padding:12px; background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0;'>
                    <span style='display:inline-block; padding:4px 14px; border-radius:999px; background:{$statusColor}; color:white; font-weight:700; font-size:0.8rem;'>
                        {$statusLabel}
                    </span>
                    <p style='margin:6px 0 0; color:#64748b; font-size:0.75rem;'>Diajukan pada {$tanggal}</p>
                </div>

                <!-- Info tester -->
                <div style='background:#f0f9ff; border:1px solid #bae6fd; border-radius:12px; padding:12px 16px;'>
                    <p style='margin:0 0 4px; font-weight:700; color:#0369a1; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em;'>👤 Info Tester</p>
                    <p style='margin:0; font-weight:600; color:#1e293b;'>{$nama}</p>
                    <p style='margin:2px 0 0; color:#64748b; font-size:0.8rem;'>{$email}</p>
                    <p style='margin:6px 0 0; color:#0369a1; font-size:0.8rem;'>Saldo poin saat ini: <strong>{$saldoPoin} pts</strong></p>
                </div>

                <!-- Detail pencairan -->
                <div style='display:grid; grid-template-columns:1fr 1fr; gap:10px;'>
                    <div style='background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:12px; text-align:center;'>
                        <p style='margin:0; color:#15803d; font-size:0.7rem; font-weight:700; text-transform:uppercase;'>Poin Ditarik</p>
                        <p style='margin:4px 0 0; font-size:1.5rem; font-weight:800; color:#16a34a;'>{$poin}</p>
                        <p style='margin:0; color:#15803d; font-size:0.75rem;'>points</p>
                    </div>
                    <div style='background:#fefce8; border:1px solid #fde68a; border-radius:12px; padding:12px; text-align:center;'>
                        <p style='margin:0; color:#854d0e; font-size:0.7rem; font-weight:700; text-transform:uppercase;'>Nominal</p>
                        <p style='margin:4px 0 0; font-size:1.1rem; font-weight:800; color:#92400e;'>{$rupiah}</p>
                        <p style='margin:0; color:#854d0e; font-size:0.75rem;'>yang diterima</p>
                    </div>
                </div>

                <!-- Tujuan e-wallet -->
                <div style='background:#fdf4ff; border:1px solid #e9d5ff; border-radius:12px; padding:12px 16px; display:flex; align-items:center; gap:12px;'>
                    <div style='background:#7c3aed; border-radius:10px; padding:10px; color:white; font-size:1.25rem; flex-shrink:0;'>💳</div>
                    <div>
                        <p style='margin:0; font-weight:700; color:#1e293b;'>{$ewallet}</p>
                        <p style='margin:2px 0 0; color:#64748b; font-size:0.875rem;'>{$nomorEwallet}</p>
                    </div>
                </div>

                {$warningHtml}
            </div>
        ");
    }

    // ─────────────────────────────────────────────────────────
    //  PAGES & RELASI
    // ─────────────────────────────────────────────────────────

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVerifikasiSaldos::route('/'),
        ];
    }
}
