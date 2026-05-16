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

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Penukaran Poin';

    protected static ?string $modelLabel = 'Penukaran Poin';

    protected static ?string $pluralModelLabel = 'Penukaran Poin';

    protected static ?string $navigationGroup = 'Reward Tester';
    
    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tester_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Form Penukaran Poin')
                    ->description('Masukkan detail e-wallet untuk mencairkan reward. 1 poin = Rp1.000.')
                    ->schema([
                        Forms\Components\Select::make('e_wallet_provider')
                            ->label('Pilih E-Wallet')
                            ->options([
                                'DANA' => 'DANA',
                                'GoPay' => 'GoPay',
                                'OVO' => 'OVO',
                                'ShopeePay' => 'ShopeePay',
                                'LinkAja' => 'LinkAja',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('e_wallet_number')
                            ->label('Nomor Handphone E-Wallet')
                            ->tel()
                            ->required()
                            ->placeholder('Contoh: 081234567890')
                            ->rules(['regex:/^08[0-9]{8,13}$/'])
                            ->validationMessages([
                                'regex' => 'Nomor e-wallet harus diawali 08 dan berisi 10-15 digit angka.',
                            ]),

                        Forms\Components\TextInput::make('points_withdrawn')
                            ->label('Jumlah Poin yang Ditukar')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('amount_rp', (int) $state * 1000);
                            })
                            ->maxValue(fn () => Auth::user()?->testerProfile?->points ?? 0)
                            ->helperText(fn () => 'Saldo poin kamu saat ini: ' . (Auth::user()?->testerProfile?->points ?? 0) . ' poin'),

                        Forms\Components\TextInput::make('amount_rp')
                            ->label('Estimasi Rupiah yang Diterima')
                            ->numeric()
                            ->readOnly()
                            ->prefix('Rp')
                            ->required(),

                        Forms\Components\Hidden::make('tester_id')
                            ->default(fn () => Auth::id()),

                        Forms\Components\Hidden::make('status')
                            ->default('pending'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Request')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('points_withdrawn')
                    ->label('Poin Ditukar')
                    ->numeric()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('amount_rp')
                    ->label('Nominal')
                    ->money('IDR', locale: 'id')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('e_wallet_provider')
                    ->label('E-Wallet')
                    ->badge()
                    ->description(fn (Withdrawal $record): string => $record->e_wallet_number),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => '-',
                    }),
            ])
            ->emptyStateIcon('heroicon-o-wallet')
            ->emptyStateHeading('Belum Ada Riwayat Penukaran Poin')
            ->emptyStateDescription('Setelah mendapatkan poin dari misi testing, kamu bisa menukarkannya ke e-wallet.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tukar Poin Sekarang')
                    ->icon('heroicon-m-plus')
                    ->button(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenukaranPoins::route('/'),
            'create' => Pages\CreatePenukaranPoin::route('/create'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            \App\Filament\Tester\Widgets\PenukaranPoinStats::class,
        ];
    }
}