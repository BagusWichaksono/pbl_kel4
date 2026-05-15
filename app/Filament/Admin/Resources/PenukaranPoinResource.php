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
    protected static ?string $navigationGroup = 'Validasi & Keuangan';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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

                Tables\Columns\TextColumn::make('account_name')
                    ->label('Atas Nama QRIS'),

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
                // TOMBOL: SCAN QRIS DI LAYAR
                Action::make('scan_qris')
                    ->label('Scan QRIS')
                    ->icon('heroicon-m-qr-code')
                    ->color('info')
                    ->modalHeading('Scan QRIS Tester')
                    ->modalContent(fn ($record) => new HtmlString('
                        <div style="text-align: center;">
                            <p style="margin-bottom: 10px; font-weight: bold; font-size: 1.2rem;">Atas Nama: ' . $record->account_name . '</p>
                            <p style="margin-bottom: 20px; color: #d97706; font-weight: bold;">Transfer: Rp ' . number_format($record->amount_rp, 0, ',', '.') . '</p>
                            <img src="' . asset('storage/' . $record->qris_image) . '" style="max-width: 100%; max-height: 400px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); margin: 0 auto;">
                        </div>
                    '))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),

                Action::make('approve')
                    ->label('Tandai Lunas')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['status' => 'approved']);
                        // Note: Saldo poin tester sudah berkurang di backend kan? Kalau belum, tambahkan logikanya di sini.
                    })
                    ->visible(fn ($record) => $record->status === 'pending'),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(['status' => 'rejected', 'notes' => $data['notes']]);
                        // Note: Kalau ditolak, saldo poin tester harus dikembalikan lagi (refund).
                    })
                    ->visible(fn ($record) => $record->status === 'pending'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenukaranPoins::route('/'),
        ];
    }
}