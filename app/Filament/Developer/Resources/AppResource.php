<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\AppResource\Pages;
use App\Models\App;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class AppResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static ?string $navigationLabel = 'Aplikasi Saya';

    protected static ?string $modelLabel = 'Aplikasi';

    protected static ?string $pluralModelLabel = 'Aplikasi Saya';

    protected static ?string $navigationGroup = 'Testing';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Aplikasi')
                    ->description('Masukkan data aplikasi yang sudah lulus pengujian awal Google Play Console.')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Nama Aplikasi')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Sistem Kasir UMKM'),

                        Forms\Components\Select::make('platform')
                            ->label('Platform')
                            ->required()
                            ->options([
                                'Android' => 'Android',
                                'iOS' => 'iOS',
                                'Web' => 'Web',
                            ])
                            ->native(false)
                            ->placeholder('Pilih platform aplikasi'),

                        Forms\Components\FileUpload::make('app_icon')
                            ->label('Icon Aplikasi')
                            ->disk('public')
                            ->directory('app-icons')
                            ->image()
                            ->imagePreviewHeight('120')
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                            ->maxSize(2048)
                            ->required()
                            ->helperText('Upload icon persegi agar tampil menarik di menu Cari Misi.'),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Aplikasi')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull()
                            ->placeholder('Jelaskan fitur yang perlu dites, instruksi tester, dan bagian penting yang harus diperhatikan.'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Bukti Lulus Review Awal')
                    ->description('Upload screenshot bukti bahwa aplikasi sudah lulus review awal dari Google Play Console.')
                    ->schema([
                        Forms\Components\FileUpload::make('review_screenshot')
                            ->label('Bukti Lulus Review Awal')
                            ->disk('public')
                            ->directory('review_screenshots')
                            ->image()
                            ->imagePreviewHeight('220')
                            ->required()
                            ->columnSpanFull()
                            ->helperText('Contoh: screenshot status review aplikasi di Google Play Console.'),
                    ]),

                Forms\Components\Section::make('Pembayaran')
                    ->description('Biaya upload aplikasi sebesar Rp300.000. Silakan bayar melalui QRIS lalu upload bukti pembayaran.')
                    ->schema([
                        Forms\Components\Placeholder::make('payment_info')
                            ->label('Informasi Pembayaran')
                            ->content(new HtmlString('
                                <div style="border: 1px solid #dbeafe; background: #eff6ff; border-radius: 16px; padding: 18px;">
                                    <div style="font-weight: 700; color: #1e3a8a; font-size: 16px; margin-bottom: 8px;">
                                        Biaya Upload: Rp300.000
                                    </div>
                                    <div style="color: #334155; line-height: 1.7;">
                                        Silakan lakukan pembayaran ke rekening/QRIS TesYuk.<br>
                                        <strong>Rekening:</strong> XXX<br>
                                        Setelah membayar, upload bukti pembayaran pada form di bawah.
                                    </div>
                                </div>
                            '))
                            ->columnSpanFull(),

                    Forms\Components\Actions::make([
                        Forms\Components\Actions\Action::make('show_qris')
                            ->label('Tampilkan Barcode QRIS')
                            ->icon('heroicon-m-qr-code')
                            ->color('primary')
                            ->modalHeading('Scan Barcode QRIS')
                            ->modalContent(function () {
                                $qrisPath = public_path('assets/qris.png');
                                $qrisVersion = file_exists($qrisPath) ? filemtime($qrisPath) : time();
                                $qrisUrl = asset('assets/qris.png') . '?v=' . $qrisVersion;

                                return new HtmlString('
                                    <div style="text-align: center;">
                                        <img src="' . $qrisUrl . '" alt="QRIS TesYuk" style="max-width: 430px; width: 100%; margin: 0 auto; border-radius: 18px; border: 1px solid #e2e8f0; box-shadow: 0 18px 45px -28px rgba(15, 23, 42, 0.35);">
                                        <p style="margin-top: 16px; color: #475569; font-size: 0.95rem; line-height: 1.6;">
                                            Scan QRIS ini menggunakan e-wallet atau mobile banking.
                                        </p>
                                        <p style="margin-top: 8px; color: #dc2626; font-size: 0.82rem; font-weight: 700;">
                                            Catatan: QRIS ini digunakan untuk simulasi/demo pembayaran TesYuk.
                                        </p>
                                    </div>
                                ');
                            })
                            ->modalWidth('lg')
                            ->modalSubmitAction(false)
                            ->modalCancelActionLabel('Tutup'),
                    ])
                        ->columnSpanFull(),

                        Forms\Components\FileUpload::make('payment_proof')
                        ->label('Bukti Pembayaran')
                        ->disk('public')
                        ->directory('payment_proofs')
                        ->image()
                        ->imagePreviewHeight('220')
                        ->required()
                        ->columnSpanFull()
                        ->helperText('Upload screenshot atau foto bukti pembayaran Rp300.000.'),

                        Forms\Components\Hidden::make('payment_status')
                            ->default('pending'),

                        Forms\Components\Hidden::make('testing_status')
                            ->default('pending_approval'),

                        Forms\Components\Hidden::make('developer_id')
                            ->default(fn() => Auth::id()),
                    ]),

                Forms\Components\Section::make('Ketentuan Testing')
                    ->description('Tanggal testing baru dapat dimulai setelah minimal 12 tester terkumpul.')
                    ->schema([
                        Forms\Components\TextInput::make('max_testers')
                            ->label('Target Jumlah Tester')
                            ->numeric()
                            ->required()
                            ->default(20)
                            ->minValue(12)
                            ->maxValue(100)
                            ->helperText('Minimal 12 tester karena kebutuhan closed testing Google Play Console.'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('platform')
                    ->label('Platform')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'Android' => 'success',
                        'iOS' => 'dark',
                        'Web' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('testers_count')
                    ->label('Tester Terdaftar')
                    ->counts('testers')
                    ->formatStateUsing(fn($state, App $record): string => $state . ' / ' . $record->max_testers)
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Pembayaran')
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'valid' => 'success',
                        'invalid' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'valid' => 'Valid',
                        'invalid' => 'Tidak Valid',
                        default => '-',
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];
                        if (str_contains('menunggu', $search) || str_contains('pending', $search)) $matched[] = 'pending';
                        if (str_contains('valid', $search)) $matched[] = 'valid';
                        if (str_contains('tidak valid', $search) || str_contains('invalid', $search)) $matched[] = 'invalid';
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('payment_status', $matched);
                        }
                        return $query->where('payment_status', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('testing_status')
                    ->label('Status Testing')
                    ->badge()
                    ->color(fn(?string $state): string => match ($state) {
                        'pending_approval' => 'warning',
                        'open' => 'gray',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(?string $state): string => match ($state) {
                        'pending_approval' => 'Menunggu Admin',
                        'open' => 'Mencari Tester',
                        'in_progress' => 'Sedang Testing',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => '-',
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];
                        if (str_contains('menunggu', $search) || str_contains('admin', $search)) $matched[] = 'pending_approval';
                        if (str_contains('mencari', $search) || str_contains('tester', $search) || str_contains('open', $search)) $matched[] = 'open';
                        if (str_contains('sedang', $search) || str_contains('testing', $search) || str_contains('progress', $search)) $matched[] = 'in_progress';
                        if (str_contains('selesai', $search) || str_contains('completed', $search)) $matched[] = 'completed';
                        if (str_contains('ditolak', $search) || str_contains('rejected', $search)) $matched[] = 'rejected';
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('testing_status', $matched);
                        }
                        return $query->where('testing_status', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Submit')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('view_testers')
                    ->label('Lihat Tester')
                    ->icon('heroicon-o-users')
                    ->color('info')
                    ->url(fn(App $record): string => AppResource::getUrl('view-testers', ['record' => $record])),

                Tables\Actions\EditAction::make()
                    ->visible(fn(App $record): bool => in_array($record->testing_status, ['pending_approval', 'rejected'])),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn(App $record): bool => in_array($record->testing_status, ['pending_approval', 'rejected'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('developer_id', Auth::id());
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApps::route('/'),
            'create' => Pages\CreateApp::route('/create'),
            'edit' => Pages\EditApp::route('/{record}/edit'),
            'view-testers' => Pages\ViewAppTesters::route('/{record}/testers'),
        ];
    }
}
