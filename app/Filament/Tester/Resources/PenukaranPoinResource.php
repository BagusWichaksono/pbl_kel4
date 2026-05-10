<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\PenukaranPoinResource\Pages;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PenukaranPoinResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $modelLabel = 'Penukaran Poin';
    protected static ?string $pluralModelLabel = 'Penukaran Poin';
    protected static ?string $navigationLabel = 'Penukaran Poin';
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tester_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Penukaran Poin')
                    ->description('Masukkan detail e-wallet kamu dengan benar.')
                    ->schema([
                        Forms\Components\Select::make('e_wallet_provider')
                            ->label('Pilih E-Wallet')
                            ->options([
                                'DANA' => 'DANA',
                                'GoPay' => 'GoPay',
                                'OVO' => 'OVO',
                                'ShopeePay' => 'ShopeePay',
                            ])
                            ->required(),
                            
                        Forms\Components\TextInput::make('e_wallet_number')
                            ->label('Nomor Handphone E-Wallet')
                            ->numeric()
                            ->required()
                            ->placeholder('081234567890'),
                            
                        Forms\Components\TextInput::make('points_withdrawn')
                            ->label('Jumlah Poin yang Ditarik')
                            ->numeric()
                            ->required()
                            // Asumsi 1 Poin = Rp 1.000 (Tinggal disesuaikan dengan aturan bisnismu)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('amount_rp', (int)$state * 1000))
                            // Validasi: Nggak boleh narik poin melebihi saldo yang dimiliki tester
                            ->maxValue(fn () => Auth::user()->points ?? 0)
                            ->helperText(fn () => 'Saldo Poin kamu saat ini: ' . (Auth::user()->points ?? 0) . ' pts'),
                            
                        Forms\Components\TextInput::make('amount_rp')
                            ->label('Estimasi Rupiah yang Diterima')
                            ->numeric()
                            ->readOnly()
                            ->prefix('Rp')
                            ->required(),
                            
                        Forms\Components\Hidden::make('tester_id')
                            ->default(fn () => Auth::id()),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Request')
                    ->date('d M Y, H:i')
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('points_withdrawn')
                    ->label('Poin Ditarik')
                    ->numeric()
                    ->badge()
                    ->color('info'),
                    
                Tables\Columns\TextColumn::make('amount_rp')
                    ->label('Rupiah')
                    ->money('IDR', locale: 'id')
                    ->weight('bold'),
                    
                Tables\Columns\TextColumn::make('e_wallet_provider')
                    ->label('E-Wallet')
                    ->description(fn (Withdrawal $record): string => $record->e_wallet_number),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenukaranPoins::route('/'),
            'create' => Pages\CreatePenukaranPoin::route('/create'),
        ];
    }
}