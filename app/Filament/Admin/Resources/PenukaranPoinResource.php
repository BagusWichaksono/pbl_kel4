<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PenukaranPoinResource\Pages;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;

class PenukaranPoinResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $modelLabel = 'Pencairan Poin';

    protected static ?string $pluralModelLabel = 'Pencairan Poin Tester';
    
    protected static ?string $slug = 'penarikan-dana';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationGroup = 'Keuangan';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Pengajuan')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),

                Tables\Columns\TextColumn::make('invoice_code')
                    ->label('Kode Invoice')
                    ->searchable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('tester.name') // Asumsi relasi ke model user adalah 'tester'
                    ->label('Nama Tester')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('points_withdrawn')
                    ->label('Poin Ditukar')
                    ->badge()
                    ->color('info')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $num = preg_replace('/[^0-9]/', '', $search);
                        if ($num !== '') {
                            return $query->where('points_withdrawn', 'like', "%{$num}%");
                        }
                        return $query->where('points_withdrawn', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('amount_rp')
                    ->label('Harus Ditransfer')
                    ->money('IDR', locale: 'id')
                    ->color('primary')
                    ->weight('bold')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $num = preg_replace('/[^0-9]/', '', $search);
                        if ($num !== '') {
                            return $query->where('amount_rp', 'like', "%{$num}%");
                        }
                        return $query->where('amount_rp', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('e_wallet_provider')
                    ->label('E-Wallet')
                    ->badge()
                    ->searchable()
                    ->description(fn (Withdrawal $record): string => $record->e_wallet_number ?? ''),

                Tables\Columns\TextColumn::make('account_name')
                    ->label('Atas Nama E-Wallet'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => strtoupper($state === 'approved' ? 'completed' : $state))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];
                        if (str_contains('pending', $search)) $matched[] = 'pending';
                        if (str_contains('completed', $search) || str_contains('approved', $search)) $matched[] = 'approved';
                        if (str_contains('rejected', $search)) $matched[] = 'rejected';
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('status', $matched);
                        }
                        return $query->where('status', 'like', "%{$search}%");
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('approve_payment')
                    ->label('Upload Bukti & Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\FileUpload::make('payment_proof')
                            ->label('Bukti Pembayaran')
                            ->image()
                            ->required()
                            ->directory('payment-proofs'),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status' => 'approved',
                            'payment_proof' => $data['payment_proof']
                        ]);
                        
                        // Notify Tester
                        if ($record->tester) {
                            \Filament\Notifications\Notification::make()
                                ->title('Penarikan Poin Berhasil')
                                ->body('Dana sebesar Rp ' . number_format($record->amount_rp, 0, ',', '.') . ' telah ditransfer ke rekening Anda.')
                                ->success()
                                ->sendToDatabase($record->tester);
                        }
                    })
                    ->visible(fn ($record) => $record->status === 'pending'),

                Action::make('reject_payment')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Penarikan Poin')
                    ->modalDescription('Apakah Anda yakin menolak penarikan poin ini? Poin akan dikembalikan ke saldo tester.')
                    ->action(function ($record) {
                        \Illuminate\Support\Facades\DB::transaction(function () use ($record) {
                            $record->update(['status' => 'rejected']);
                            
                            // Refund points
                            if ($record->tester && $record->tester->testerProfile) {
                                $profile = $record->tester->testerProfile;
                                $profile->points += $record->points_withdrawn;
                                $profile->save();

                                // Catat ke riwayat
                                \App\Models\PointHistory::create([
                                    'tester_id' => $record->tester_id,
                                    'amount' => $record->points_withdrawn,
                                    'type' => 'credit',
                                    'description' => 'Pengembalian poin karena penarikan ditolak (Invoice: ' . $record->invoice_code . ')',
                                ]);
                                
                                // Notify Tester
                                \Filament\Notifications\Notification::make()
                                    ->title('Penarikan Poin Ditolak')
                                    ->body('Permintaan penarikan Anda ditolak oleh Admin. Saldo poin Anda telah dikembalikan.')
                                    ->danger()
                                    ->sendToDatabase($record->tester);
                            }
                        });
                    })
                    ->visible(fn ($record) => $record->status === 'pending'),

                Action::make('view_proof')
                    ->label('Lihat Bukti')
                    ->icon('heroicon-o-photo')
                    ->color('info')
                    ->modalHeading('Bukti Pembayaran')
                    ->modalContent(fn ($record) => new HtmlString('
                        <div style="text-align: center;">
                            <img src="' . asset('storage/' . $record->payment_proof) . '" style="max-width: 100%; max-height: 400px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin: 0 auto;">
                        </div>
                    '))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->visible(fn ($record) => $record->status === 'approved' && $record->payment_proof),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenukaranPoins::route('/'),
        ];
    }
}