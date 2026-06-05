<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\PointHistoryResource\Pages;
use App\Filament\Tester\Resources\PointHistoryResource\RelationManagers;
use App\Models\PointHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PointHistoryResource extends Resource
{
    protected static ?string $model = PointHistory::class;

    protected static ?string $modelLabel = 'Riwayat Penukaran Poin';

    protected static ?string $pluralModelLabel = 'Riwayat Penukaran Poin';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Riwayat Penukaran Poin';

    protected static ?string $navigationGroup = 'Poin';

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tester_id', '=', \Illuminate\Support\Facades\Auth::id(), 'and');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal & Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Keterangan')
                    ->wrap()
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Mutasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'credit' => 'success',
                        'debit' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => $state === 'credit' ? 'Pemasukan (+)' : 'Pengeluaran (-)')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];
                        if (str_contains('pemasukan', $search) || str_contains('+', $search)) $matched[] = 'credit';
                        if (str_contains('pengeluaran', $search) || str_contains('-', $search)) $matched[] = 'debit';
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('type', $matched);
                        }
                        
                        return $query->where('type', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Jumlah Poin')
                    ->numeric()
                    ->color(fn ($record) => $record->type === 'credit' ? 'success' : 'danger')
                    ->weight('bold')
                    ->formatStateUsing(fn ($state, $record) => ($record->type === 'credit' ? '+' : '-') . ' ' . $state . ' Pts')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $num = preg_replace('/[^0-9]/', '', $search);
                        if ($num !== '') {
                            return $query->where('amount', 'like', "%{$num}%");
                        }
                        return $query->where('amount', 'like', "%{$search}%");
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Read-only
            ])
            ->bulkActions([
                // Read-only
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPointHistories::route('/'),
        ];
    }
}
