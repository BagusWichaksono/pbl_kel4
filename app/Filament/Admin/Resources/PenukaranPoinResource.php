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

class PenukaranPoinResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $modelLabel = 'Pencairan Poin';

    protected static ?string $pluralModelLabel = 'Pencairan Poin Tester';
    
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
                    ->sortable(),

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
                    ->color('info'),

                Tables\Columns\TextColumn::make('amount_rp')
                    ->label('Harus Ditransfer')
                    ->money('IDR', locale: 'id')
                    ->color('primary')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('e_wallet_provider')
                    ->label('E-Wallet')
                    ->badge()
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
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),
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