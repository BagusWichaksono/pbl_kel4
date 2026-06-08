<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransactionResource\Pages;
use App\Models\App;
use App\Support\AppNotifier;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;


class TransactionResource extends Resource
{
    protected static ?string $model = App::class;

    private const DEVELOPER_PAYMENT_AMOUNT = 300000;

    protected static ?string $modelLabel = 'Pembayaran Developer';

    protected static ?string $pluralModelLabel = 'Pembayaran Developer';
    
    protected static ?string $slug = 'transaksi';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Pembayaran Developer';
    
    protected static ?string $navigationGroup = 'Keuangan';
    
    protected static ?int $navigationSort = 1;

    // ─── AKSES: Admin & Superadmin Boleh Masuk ────────────────────────
    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && in_array($user->role, ['admin', 'super_admin']);
    }

    // Blokir fitur "Buat Baru" karena admin cuma memvalidasi, bukan bikin transaksi
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Validasi dilakukan langsung dari tabel, tidak lewat halaman edit.
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Nama Developer (Asumsi relasi ke user bernama 'developer')
                Tables\Columns\TextColumn::make('developer.name')
                    ->label('Nama Developer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('title')
                    ->label('Aplikasi')
                    ->searchable()
                    ->description(fn (App $record): string => 'Platform: ' . ($record->platform ?? '-')),

                Tables\Columns\TextColumn::make('payment_amount')
                    ->label('Nominal')
                    ->getStateUsing(fn (App $record): int => self::DEVELOPER_PAYMENT_AMOUNT)
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Transfer')
                    ->disk('public')
                    ->square()
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->defaultImageUrl(url('/images/no-image.png')),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => self::paymentStatusColor($state))
                    ->formatStateUsing(fn (?string $state): string => self::paymentStatusLabel($state))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];

                        if (str_contains('menunggu', $search) || str_contains('pending', $search)) {
                            $matched[] = 'pending';
                        } elseif (str_contains('ditolak', $search) || str_contains('invalid', $search) || str_contains('tidak', $search)) {
                            $matched[] = 'invalid';
                        } elseif (str_contains('lunas', $search) || str_contains('valid', $search) || str_contains('approved', $search) || str_contains('disetujui', $search)) {
                            $matched = ['valid', 'approved'];
                        } elseif (str_contains('refund', $search)) {
                            $matched[] = 'refunded';
                        }
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('payment_status', $matched);
                        }
                        return $query->where('payment_status', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('testing_status')
                    ->label('Status Testing')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending_approval' => 'warning',
                        'open' => 'gray',
                        'active', 'in_progress' => 'info',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending_approval' => 'Menunggu Admin',
                        'open' => 'Mencari Tester',
                        'active', 'in_progress' => 'Sedang Testing',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => '-',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Menunggu Validasi',
                        'valid' => 'Lunas / Valid',
                        'approved' => 'Approved',
                        'invalid' => 'Ditolak',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->actions([
                Action::make('lihatBukti')
                    ->label('Cek Bukti')
                    ->icon('heroicon-m-eye')
                    ->color('info')
                    ->modalHeading(fn (App $record): string => 'Bukti Pembayaran - ' . $record->title)
                    ->modalContent(fn (App $record): HtmlString => self::renderPaymentProofModal($record))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                Action::make('terima')
                    ->label('Terima')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Validasi Pembayaran')
                    ->modalDescription('Apakah bukti transfer ini sudah valid dan dana sudah masuk ke rekening? Status aplikasi akan dilanjutkan.')
                    ->action(function (App $record) {
                        $record->update([
                            'payment_status' => 'valid',
                            'testing_status' => 'open',
                        ]);

                        Notification::make()
                            ->title('Pembayaran Developer Disetujui')
                            ->success()
                            ->send();
                        
                        if ($record->developer) {
                            AppNotifier::database(
                                $record->developer,
                                'Pembayaran diterima',
                                "Pembayaran Rp300.000 untuk aplikasi {$record->title} sudah divalidasi admin.",
                                'success',
                            );
                        }
                    })
                    ->visible(fn (App $record): bool => ! in_array($record->payment_status, ['valid', 'approved', 'refunded'], true)),

                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran')
                    ->modalDescription('Apakah Anda yakin ingin menolak pembayaran ini? Developer harus mengunggah ulang bukti transfer.')
                    ->action(function (App $record) {
                        $record->update([
                            'payment_status' => 'invalid',
                            'testing_status' => 'rejected',
                        ]);

                        Notification::make()
                            ->title('Pembayaran Developer Ditolak')
                            ->danger()
                            ->send();
                        
                        if ($record->developer) {
                            AppNotifier::database(
                                $record->developer,
                                'Pembayaran ditolak',
                                "Pembayaran untuk aplikasi {$record->title} ditolak. Silakan unggah ulang bukti yang benar.",
                                'danger',
                            );
                        }
                    })
                    ->visible(fn (App $record): bool => ! in_array($record->payment_status, ['invalid', 'refunded'], true)),
            ])
            ->emptyStateHeading('Belum Ada Pembayaran Developer')
            ->emptyStateDescription('Data pembayaran akan muncul dari aplikasi yang diajukan developer.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('developer');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
        ];
    }

    private static function paymentStatusColor(?string $state): string
    {
        return match ($state) {
            'pending' => 'warning',
            'valid', 'approved' => 'success',
            'invalid', 'refunded' => 'danger',
            default => 'gray',
        };
    }

    private static function paymentStatusLabel(?string $state): string
    {
        return match ($state) {
            'pending' => 'Menunggu Validasi',
            'valid', 'approved' => 'Lunas',
            'invalid' => 'Ditolak',
            'refunded' => 'Refunded',
            default => '-',
        };
    }

    private static function renderPaymentProofModal(App $record): HtmlString
    {
        if (blank($record->payment_proof)) {
            return new HtmlString('
                <div style="text-align:center;color:#64748b;padding:2rem 1rem;">
                    Bukti pembayaran belum diunggah untuk aplikasi ini.
                </div>
            ');
        }

        $imageUrl = e(asset('storage/' . $record->payment_proof));
        $title = e($record->title);

        return new HtmlString('
            <div style="text-align:center;">
                <img src="' . $imageUrl . '" alt="Bukti pembayaran ' . $title . '" style="max-width:100%;max-height:70vh;object-fit:contain;border-radius:12px;border:1px solid #e2e8f0;margin:0 auto;">
            </div>
        ');
    }
}
