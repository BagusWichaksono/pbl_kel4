<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\PenukaranPoinResource\Pages;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PenukaranPoinResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Penukaran Poin';

    protected static ?string $pluralModelLabel = 'Penukaran Poin';

    protected static ?string $modelLabel = 'Penukaran Poin';

    protected static ?string $navigationGroup = 'Reward Tester';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('tester_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        $profile = Auth::user()?->testerProfile;
        $points = (int) ($profile?->points ?? 0);

        $schema = [
            Forms\Components\Section::make('Tujuan Pencairan')
                ->description('Masukkan data e-wallet yang benar agar proses pencairan poin berjalan lancar.')
                ->icon('heroicon-o-wallet')
                ->schema([
                    Forms\Components\Select::make('e_wallet_provider')
                        ->label('E-Wallet')
                        ->options([
                            'DANA' => 'DANA',
                            'GoPay' => 'GoPay',
                            'OVO' => 'OVO',
                            'ShopeePay' => 'ShopeePay',
                            'LinkAja' => 'LinkAja',
                        ])
                        ->native(false)
                        ->required(),

                    Forms\Components\TextInput::make('e_wallet_number')
                        ->label('Nomor E-Wallet')
                        ->tel()
                        ->required()
                        ->placeholder('Contoh: 081234567890')
                        ->rules(['regex:/^08[0-9]{8,13}$/'])
                        ->validationMessages([
                            'regex' => 'Nomor e-wallet harus diawali 08 dan berisi 10-15 digit angka.',
                        ]),

                    Forms\Components\TextInput::make('account_name')
                        ->label('Atas Nama')
                        ->placeholder('Contoh: Bagus Wichaksono')
                        ->maxLength(255)
                        ->required()
                        ->visible(fn (): bool => Schema::hasColumn('withdrawals', 'account_name'))
                        ->helperText('Isi sesuai nama pemilik akun e-wallet.'),
                ])
                ->columns(2),

            Forms\Components\Section::make('Detail Poin')
                ->description('1 poin bernilai Rp1.000. Pastikan jumlah poin tidak melebihi saldo kamu.')
                ->icon('heroicon-o-banknotes')
                ->schema([
                    Forms\Components\TextInput::make('points_withdrawn')
                        ->label('Jumlah Poin yang Ditukar')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->maxValue($points)
                        ->live(onBlur: true)
                        ->helperText("Saldo poin kamu saat ini: {$points} poin.")
                        ->afterStateUpdated(function ($state, callable $set): void {
                            $set('amount_rp', (int) $state * 1000);
                        }),

                    Forms\Components\TextInput::make('amount_rp')
                        ->label('Estimasi Rupiah')
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
        ];

        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        $columns = [];

        if (Schema::hasColumn('withdrawals', 'invoice_code')) {
            $columns[] = Tables\Columns\TextColumn::make('invoice_code')
                ->label('Kode Invoice')
                ->searchable()
                ->copyable()
                ->weight('bold')
                ->color('primary')
                ->placeholder('-');
        }

        $columns[] = Tables\Columns\TextColumn::make('created_at')
            ->label('Waktu Pengajuan')
            ->dateTime('d M Y, H:i')
            ->sortable()
            ->searchable(query: function (Builder $query, string $search): Builder {
                return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
            });

        if (Schema::hasColumn('withdrawals', 'account_name')) {
            $columns[] = Tables\Columns\TextColumn::make('account_name')
                ->label('Atas Nama')
                ->searchable()
                ->weight('medium')
                ->placeholder('-');
        }

        $columns[] = Tables\Columns\TextColumn::make('e_wallet_provider')
            ->label('E-Wallet')
            ->badge()
            ->color('info')
            ->searchable()
            ->description(fn (Withdrawal $record): string => $record->e_wallet_number ?? '-');

        $columns[] = Tables\Columns\TextColumn::make('points_withdrawn')
            ->label('Poin')
            ->numeric()
            ->badge()
            ->color('primary')
            ->alignCenter()
            ->searchable(query: function (Builder $query, string $search): Builder {
                $num = preg_replace('/[^0-9]/', '', $search);
                if ($num !== '') {
                    return $query->where('points_withdrawn', 'like', "%{$num}%");
                }
                return $query->where('points_withdrawn', 'like', "%{$search}%");
            });

        $columns[] = Tables\Columns\TextColumn::make('amount_rp')
            ->label('Nominal')
            ->money('IDR', locale: 'id')
            ->weight('bold')
            ->color('success')
            ->searchable(query: function (Builder $query, string $search): Builder {
                $num = preg_replace('/[^0-9]/', '', $search);
                if ($num !== '') {
                    return $query->where('amount_rp', 'like', "%{$num}%");
                }
                return $query->where('amount_rp', 'like', "%{$search}%");
            });

        $columns[] = Tables\Columns\TextColumn::make('status')
            ->label('Status')
            ->badge()
            ->color(fn (?string $state): string => match ($state) {
                'pending' => 'warning',
                'approved', 'completed' => 'success',
                'rejected' => 'danger',
                default => 'gray',
            })
            ->formatStateUsing(fn (?string $state): string => match ($state) {
                'pending' => 'Menunggu Pembayaran',
                'approved', 'completed' => 'Selesai',
                'rejected' => 'Ditolak',
                default => '-',
            })
            ->searchable(query: function (Builder $query, string $search): Builder {
                $search = strtolower($search);
                $matched = [];
                if (str_contains('menunggu', $search) || str_contains('pending', $search)) $matched[] = 'pending';
                if (str_contains('selesai', $search) || str_contains('approved', $search) || str_contains('completed', $search)) {
                    $matched[] = 'approved';
                    $matched[] = 'completed';
                }
                if (str_contains('ditolak', $search) || str_contains('rejected', $search)) $matched[] = 'rejected';
                
                if (count($matched) > 0) {
                    return $query->whereIn('status', $matched);
                }
                return $query->where('status', 'like', "%{$search}%");
            });

        if (Schema::hasColumn('withdrawals', 'payment_proof')) {
            $columns[] = Tables\Columns\ImageColumn::make('payment_proof')
                ->label('Bukti Bayar')
                ->disk('public')
                ->square()
                ->size(46)
                ->defaultImageUrl(null)
                ->toggleable();
        }

        return $table
            ->columns($columns)
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'completed' => 'Selesai',
                        'approved' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Penukaran Poin')
                    ->modalWidth('lg'),
            ])
            ->emptyStateIcon('heroicon-o-wallet')
            ->emptyStateHeading('Belum Ada Riwayat Penukaran Poin')
            ->emptyStateDescription('Setelah mendapatkan poin dari misi testing, kamu bisa menukarkannya ke e-wallet.')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Tukar Poin Sekarang')
                    ->icon('heroicon-o-plus')
                    ->button()
                    ->visible(fn (): bool => (int) (Auth::user()?->testerProfile?->points ?? 0) > 0),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25]);
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