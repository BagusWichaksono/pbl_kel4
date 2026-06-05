<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\TransactionResource\Pages\ListTransactions;
use App\Models\App; // Kita pakai model App karena bukti transfer nempel di tabel apps
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
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
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'valid' => 'Valid',
                        'invalid' => 'Tidak Sah',
                        default => '-',
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];
                        if (str_contains('menunggu', $search) || str_contains('pending', $search)) $matched[] = 'pending';
                        if (str_contains('valid', $search)) $matched[] = 'valid';
                        if (str_contains('tidak sah', $search) || str_contains('invalid', $search)) $matched[] = 'invalid';
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('payment_status', $matched);
                        }
                        return $query->where('payment_status', 'like', "%{$search}%");
                    }),

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
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
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
                                'invalid' => 'danger',
                                default => 'gray',
                            }),
                            
                        TextEntry::make('created_at')
                            ->label('Tanggal Pembayaran')
                            ->dateTime('d M Y, H:i'),
                            
                        ImageEntry::make('payment_proof')
                            ->label('Bukti Transfer')
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