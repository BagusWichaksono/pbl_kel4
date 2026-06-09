<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\TransactionResource\Pages\ListTransactions;
use App\Filament\Developer\Resources\TransactionResource\Pages\RequestRefund;
use App\Filament\Developer\Resources\TransactionResource\Pages\ViewTransaction;
use App\Models\App; // Kita pakai model App karena bukti transfer nempel di tabel apps
use App\Models\RefundRequest;
use App\Support\AppNotifier;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TransactionResource extends Resource
{
    protected static ?string $model = App::class;
    
    // Ganti icon dan label biar sesuai
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Riwayat Transaksi';
    
    protected static ?string $pluralModelLabel = 'Riwayat Transaksi';
    
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Transaksi';

    // KUNCI KEAMANAN: Developer cuma bisa lihat transaksi miliknya sendiri
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('developer_id', Auth::id())
            ->with('latestRefundRequest');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Untuk Aplikasi')
                    ->searchable()
                    ->weight('bold'),
                    
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Transfer')
                    ->disk('public')
                    ->circular(), // Biar bentuknya bulat estetik
                    
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Validasi Admin')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'valid' => 'success',
                        'pending' => 'warning',
                        'invalid', 'refunded' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'valid' => 'Valid',
                        'invalid' => 'Tidak Sah',
                        'refunded' => 'Refunded',
                        default => '-',
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];
                        if (str_contains('menunggu', $search) || str_contains('pending', $search)) $matched[] = 'pending';
                        if (str_contains('valid', $search)) $matched[] = 'valid';
                        if (str_contains('tidak sah', $search) || str_contains('invalid', $search)) $matched[] = 'invalid';
                        if (str_contains('refund', $search)) $matched[] = 'refunded';
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('payment_status', $matched);
                        }
                        return $query->where('payment_status', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('latestRefundRequest.status')
                    ->label('Refund')
                    ->badge()
                    ->placeholder('Belum Ada')
                    ->color(fn (?string $state): string => self::refundStatusColor($state))
                    ->formatStateUsing(fn (?string $state): string => self::refundStatusLabel($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pembayaran')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),
            ])
            ->filters([
                // Filter bawaan biar gampang nyari
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Menunggu Validasi',
                        'valid' => 'Pembayaran Valid',
                        'invalid' => 'Tidak Sah',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail'),

                Tables\Actions\Action::make('ajukanRefund')
                    ->label('Ajukan Refund')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->button()
                    ->url(fn (App $record): string => self::getUrl('refund', ['record' => $record]))
                    ->disabled(fn (App $record): bool => ! self::canRequestRefund($record))
                    ->tooltip(fn (App $record): ?string => self::refundRequestTooltip($record))
                    ->visible(fn (App $record): bool => (int) $record->developer_id === (int) Auth::id()),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detail Transaksi')
                    ->description('Informasi lengkap mengenai pembayaran aplikasi.')
                    ->schema([
                        TextEntry::make('title')
                            ->label('Untuk Aplikasi')
                            ->weight('bold'),
                            
                        TextEntry::make('payment_status')
                            ->label('Status Validasi Admin')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'valid' => 'success',
                                'pending' => 'warning',
                                'invalid', 'refunded' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'pending' => 'Menunggu',
                                'valid' => 'Valid',
                                'invalid' => 'Tidak Sah',
                                'refunded' => 'Refunded',
                                default => '-',
                            }),
                            
                        TextEntry::make('created_at')
                            ->label('Tanggal Pembayaran')
                            ->dateTime('d M Y, H:i'),

                        TextEntry::make('latestRefundRequest.status')
                            ->label('Status Refund')
                            ->badge()
                            ->placeholder('Belum Ada')
                            ->color(fn (?string $state): string => self::refundStatusColor($state))
                            ->formatStateUsing(fn (?string $state): string => self::refundStatusLabel($state)),
                            
                        ImageEntry::make('payment_proof')
                            ->label('Bukti Transfer')
                            ->disk('public')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }


    public static function getPages(): array
    {
        return [
            // Pastikan kamu punya file ListTransactions di dalam folder Developer/Resources/TransactionResource/Pages/
            'index' => ListTransactions::route('/'),
            'view' => ViewTransaction::route('/{record}'),
            'refund' => RequestRefund::route('/{record}/refund'),
        ];
    }

    // --- MATIKAN FITUR TAMBAH & EDIT KARENA INI CUMA RIWAYAT ---
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canRequestRefund(App $record): bool
    {
        if ((int) $record->developer_id !== (int) Auth::id()) {
            return false;
        }

        $latestRefund = $record->latestRefundRequest;

        return $latestRefund === null || $latestRefund->status === RefundRequest::STATUS_REJECTED;
    }

    public static function createRefundRequest(App $record, array $data): bool
    {
        if (! self::canRequestRefund($record)) {
            Notification::make()
                ->title('Refund belum bisa diajukan')
                ->body(self::refundRequestTooltip($record) ?? 'Silakan cek status refund aplikasi ini.')
                ->warning()
                ->send();

            return false;
        }

        if ($record->refundRequests()
            ->whereIn('status', [RefundRequest::STATUS_PENDING, RefundRequest::STATUS_APPROVED])
            ->exists()) {
            Notification::make()
                ->title('Pengajuan refund sudah ada')
                ->body('Aplikasi ini sudah memiliki pengajuan refund yang sedang diproses atau sudah disetujui.')
                ->warning()
                ->send();

            return false;
        }

        RefundRequest::create([
            'developer_id' => Auth::id(),
            'application_id' => $record->id,
            'amount' => RefundRequest::DEFAULT_AMOUNT,
            'reason' => $data['reason'],
            'bank_name' => $data['bank_name'],
            'account_name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'status' => RefundRequest::STATUS_PENDING,
        ]);

        Notification::make()
            ->title('Pengajuan refund terkirim')
            ->body('Admin akan meninjau pengajuan refund Anda.')
            ->success()
            ->send();

        AppNotifier::adminsDatabase(
            'Pengajuan refund baru',
            (Auth::user()?->name ?? 'Developer') . ' mengajukan refund untuk aplikasi ' . $record->title . '.',
            'warning',
        );

        return true;
    }

    private static function refundStatusColor(?string $state): string
    {
        return match ($state) {
            RefundRequest::STATUS_PENDING => 'warning',
            RefundRequest::STATUS_APPROVED => 'success',
            RefundRequest::STATUS_REJECTED => 'danger',
            default => 'gray',
        };
    }

    private static function refundStatusLabel(?string $state): string
    {
        return match ($state) {
            RefundRequest::STATUS_PENDING => 'Menunggu',
            RefundRequest::STATUS_APPROVED => 'Disetujui',
            RefundRequest::STATUS_REJECTED => 'Ditolak',
            default => 'Belum Ada',
        };
    }

    public static function refundRequestTooltip(App $record): ?string
    {
        $latestRefund = $record->latestRefundRequest;

        return match ($latestRefund?->status) {
            RefundRequest::STATUS_PENDING => 'Pengajuan refund sedang menunggu admin.',
            RefundRequest::STATUS_APPROVED => 'Refund untuk aplikasi ini sudah disetujui.',
            default => null,
        };
    }
}
