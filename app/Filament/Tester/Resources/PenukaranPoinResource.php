<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\PenukaranPoinResource\Pages;
use App\Models\Withdrawal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\HtmlString;

class PenukaranPoinResource extends Resource
{
    protected static ?string $model = Withdrawal::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $navigationLabel = 'Penukaran Poin';

    protected static ?string $pluralModelLabel = 'Penukaran Poin';

    protected static ?string $modelLabel = 'Penukaran Poin';

    protected static ?string $navigationGroup = 'Poin';

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
            Forms\Components\Placeholder::make('saldo_info')
                ->label('Saldo Poin Kamu')
                ->content(new HtmlString('
                    <div style="display:flex;flex-wrap:wrap;gap:.75rem;align-items:stretch;">
                        <div style="flex:1;min-width:180px;border:1px solid #dbeafe;background:#eff6ff;border-radius:16px;padding:1rem;">
                            <div style="font-size:.78rem;font-weight:800;color:#1d4ed8;text-transform:uppercase;letter-spacing:.04em;">Saldo Tersedia</div>
                            <div style="margin-top:.3rem;font-size:1.45rem;font-weight:900;color:#0f172a;">' . number_format($points, 0, ',', '.') . ' poin</div>
                        </div>
                        <div style="flex:1;min-width:180px;border:1px solid #bbf7d0;background:#f0fdf4;border-radius:16px;padding:1rem;">
                            <div style="font-size:.78rem;font-weight:800;color:#15803d;text-transform:uppercase;letter-spacing:.04em;">Estimasi Rupiah</div>
                            <div style="margin-top:.3rem;font-size:1.45rem;font-weight:900;color:#0f172a;">Rp' . number_format($points * 1000, 0, ',', '.') . '</div>
                        </div>
                    </div>
                '))
                ->columnSpanFull(),

            Forms\Components\Section::make('Tujuan Pencairan')
                ->description('Pilih e-wallet tujuan seperti memilih metode top up, lalu masukkan nomor yang benar.')
                ->icon('heroicon-o-wallet')
                ->schema([
                    Forms\Components\ToggleButtons::make('e_wallet_provider')
                        ->label('Mau ditukar ke e-wallet apa?')
                        ->options(self::walletOptions())
                        ->colors([
                            'DANA' => 'info',
                            'GoPay' => 'success',
                            'OVO' => 'primary',
                            'ShopeePay' => 'warning',
                            'LinkAja' => 'danger',
                        ])
                        ->columns([
                            'default' => 2,
                            'md' => 3,
                            'xl' => 5,
                        ])
                        ->gridDirection('row')
                        ->required()
                        ->columnSpanFull(),

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
                        ->suffix('poin')
                        ->live(onBlur: true)
                        ->helperText("Saldo poin kamu saat ini: {$points} poin. Jika jumlah melebihi saldo, pengajuan akan ditolak saat ditukar.")
                        ->afterStateUpdated(function ($state, callable $set): void {
                            $set('amount_rp', (int) $state * 1000);
                        }),

                    Forms\Components\TextInput::make('amount_rp')
                        ->label('Estimasi Rupiah')
                        ->numeric()
                        ->readOnly()
                        ->default(0)
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Detail Request Penukaran')
                    ->description('Ringkasan pengajuan penukaran poin yang dikirim ke admin.')
                    ->schema([
                        TextEntry::make('invoice_code')
                            ->label('Kode Invoice')
                            ->copyable()
                            ->weight('bold')
                            ->color('primary')
                            ->placeholder('-')
                            ->visible(fn (): bool => Schema::hasColumn('withdrawals', 'invoice_code')),

                        TextEntry::make('created_at')
                            ->label('Waktu Pengajuan')
                            ->dateTime('d M Y, H:i'),

                        TextEntry::make('status')
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
                            }),

                        TextEntry::make('points_withdrawn')
                            ->label('Poin Ditukar')
                            ->formatStateUsing(fn (?int $state): string => number_format((int) $state, 0, ',', '.') . ' poin'),

                        TextEntry::make('amount_rp')
                            ->label('Nominal Rupiah')
                            ->money('IDR', locale: 'id')
                            ->weight('bold')
                            ->color('success'),
                    ])
                    ->columns(2),

                Section::make('Tujuan Pencairan')
                    ->schema([
                        TextEntry::make('e_wallet_provider')
                            ->label('E-Wallet')
                            ->badge()
                            ->color('info')
                            ->placeholder('-'),

                        TextEntry::make('e_wallet_number')
                            ->label('Nomor E-Wallet')
                            ->copyable()
                            ->placeholder('-'),

                        TextEntry::make('account_name')
                            ->label('Atas Nama')
                            ->placeholder('-')
                            ->visible(fn (): bool => Schema::hasColumn('withdrawals', 'account_name')),

                        TextEntry::make('notes')
                            ->label('Catatan Admin')
                            ->placeholder('Belum ada catatan.')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Bukti Pembayaran')
                    ->description('Bukti transfer dari admin akan tampil setelah penukaran selesai diproses.')
                    ->schema([
                        ImageEntry::make('payment_proof')
                            ->label('Bukti Pembayaran')
                            ->disk('public')
                            ->height(280)
                            ->columnSpanFull(),
                    ])
                    ->visible(fn (Withdrawal $record): bool => Schema::hasColumn('withdrawals', 'payment_proof') && filled($record->payment_proof)),
            ]);
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
            ->defaultSort('created_at', 'desc')
            ->paginated([5, 10, 25]);
    }

    private static function walletOptions(): array
    {
        return [
            'DANA' => self::walletBadge('DANA', 'DANA', '#118eea'),
            'GoPay' => self::walletBadge('GPAY', 'GoPay', '#00aed6'),
            'OVO' => self::walletBadge('OVO', 'OVO', '#4c1d95'),
            'ShopeePay' => self::walletBadge('SPay', 'ShopeePay', '#ee4d2d'),
            'LinkAja' => self::walletBadge('LA', 'LinkAja', '#e11d48'),
        ];
    }

    private static function walletBadge(string $shortName, string $name, string $background): HtmlString
    {
        return new HtmlString(
            '<span style="display:flex;align-items:center;justify-content:center;gap:.55rem;">' .
                '<span style="width:46px;height:30px;border-radius:10px;background:' . e($background) . ';color:#ffffff;display:inline-flex;align-items:center;justify-content:center;font-size:.66rem;font-weight:900;letter-spacing:.02em;box-shadow:inset 0 0 0 1px rgba(255,255,255,.22);">' . e($shortName) . '</span>' .
                '<span style="font-weight:900;">' . e($name) . '</span>' .
            '</span>'
        );
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
        return [];
    }
}
