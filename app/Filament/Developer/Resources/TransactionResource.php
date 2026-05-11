<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\TransactionResource\Pages\ListTransactions;
use App\Models\App; // Kita pakai model App karena bukti transfer nempel di tabel apps
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
    protected static ?int $navigationSort = 2; // Biar posisinya pas di bawah menu Aplikasi Saya

    // KUNCI KEAMANAN: Developer cuma bisa lihat transaksi miliknya sendiri
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('developer_id', Auth::id());
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
                    ->circular(), // Biar bentuknya bulat estetik
                    
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Validasi Admin')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'valid' => 'success',
                        'pending' => 'warning',
                        'invalid' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pembayaran')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                // Filter bawaan biar gampang nyari
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Menunggu Validasi',
                        'valid' => 'Pembayaran Valid',
                        'invalid' => 'Tidak Sah',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            // Pastikan kamu punya file ListTransactions di dalam folder Developer/Resources/TransactionResource/Pages/
            'index' => ListTransactions::route('/'),
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
}