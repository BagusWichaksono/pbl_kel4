<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AppResource\Pages;
use App\Models\App;
use App\Support\AppNotifier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class AppResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $modelLabel = 'Verifikasi Aplikasi';

    protected static ?string $pluralModelLabel = 'Verifikasi Aplikasi';

    protected static ?string $slug = 'verifikasi-aplikasi';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Verifikasi Aplikasi';

    protected static ?string $navigationGroup = 'Manajemen Testing';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user !== null && in_array($user->role, ['admin', 'super_admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Aplikasi')
                    ->description('Informasi teknis aplikasi yang diajukan oleh developer.')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Nama Aplikasi')
                            ->disabled(),

                        Forms\Components\TextInput::make('platform')
                            ->label('Platform')
                            ->disabled(),

                        Forms\Components\FileUpload::make('app_icon')
                            ->label('Icon Aplikasi')
                            ->disk('public')
                            ->image()
                            ->imagePreviewHeight('120')
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Berkas Validasi')
                    ->description('Cek bukti pembayaran dan bukti closed testing.')
                    ->schema([
                        Forms\Components\FileUpload::make('payment_proof')
                            ->label('Bukti Pembayaran')
                            ->disabled(),

                        Forms\Components\FileUpload::make('review_screenshot')
                            ->label('Bukti Closed Testing')
                            ->disabled(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Berkas Validasi')
                    ->schema([
                        Infolists\Components\ImageEntry::make('payment_proof')
                            ->label('Bukti Pembayaran')
                            ->columnSpanFull()
                            ->extraImgAttributes(['style' => 'max-width: 100%; height: auto; object-fit: contain; max-height: 800px;']),
                        Infolists\Components\ImageEntry::make('review_screenshot')
                            ->label('Bukti Closed Testing')
                            ->columnSpanFull()
                            ->extraImgAttributes(['style' => 'max-width: 100%; height: auto; object-fit: contain; max-height: 800px;']),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('developer.name')
                    ->label('Developer')
                    ->searchable()
                    ->sortable()
                    ->description(fn (App $record) => 'Platform: ' . ($record->platform ?? '-')),

                Tables\Columns\ImageColumn::make('app_icon')
                    ->label('Icon')
                    ->disk('public')
                    ->square()
                    ->size(44)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Aplikasi')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Bayar')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'valid' => 'success',
                        'invalid', 'refunded' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'invalid' => 'Tidak Valid',
                        'refunded' => 'Refunded',
                        default => '-',
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];
                        if (str_contains('pending', $search) || str_contains('menunggu', $search)) $matched[] = 'pending';
                        if (str_contains('valid', $search)) $matched[] = 'valid';
                        if (str_contains('tidak valid', $search) || str_contains('invalid', $search)) $matched[] = 'invalid';
                        if (str_contains('refund', $search)) $matched[] = 'refunded';
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('payment_status', $matched);
                        }
                        return $query->where('payment_status', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('testing_status')
                    ->label('Status Testing')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'open' => 'gray',
                        'in_progress' => 'info',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'open' => 'Terbuka',
                        'in_progress' => 'Sedang Dites',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => '-',
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];
                        if (str_contains('terbuka', $search) || str_contains('open', $search)) $matched[] = 'open';
                        if (str_contains('sedang', $search) || str_contains('dites', $search) || str_contains('progress', $search)) $matched[] = 'in_progress';
                        if (str_contains('selesai', $search) || str_contains('completed', $search)) $matched[] = 'completed';
                        if (str_contains('ditolak', $search) || str_contains('rejected', $search)) $matched[] = 'rejected';
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('testing_status', $matched);
                        }
                        return $query->where('testing_status', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'invalid' => 'Tidak Valid',
                        'refunded' => 'Refunded',
                    ]),

                Tables\Filters\SelectFilter::make('testing_status')
                    ->label('Status Testing')
                    ->options([
                        'open' => 'Terbuka',
                        'in_progress' => 'Sedang Dites',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Cek Bukti')
                    ->icon('heroicon-m-magnifying-glass')
                    ->color('info'),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Terima Aplikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn (App $record): bool => $record->payment_status !== 'refunded')
                        ->action(function (App $record) {
                            $record->update([
                                'payment_status' => 'valid',
                                'testing_status' => 'open',
                            ]);

                            Notification::make()
                                ->title('Aplikasi & Pembayaran Disetujui')
                                ->success()
                                ->send();

                            if ($record->developer) {
                                AppNotifier::database(
                                    $record->developer,
                                    'Aplikasi disetujui',
                                    "Aplikasi {$record->title} sudah disetujui dan siap mencari tester.",
                                    'success',
                                );
                            }
                        }),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak Aplikasi')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn (App $record): bool => $record->payment_status !== 'refunded')
                        ->action(function (App $record) {
                            $record->update([
                                'payment_status' => 'invalid',
                                'testing_status' => 'rejected',
                            ]);

                            Notification::make()
                                ->title('Aplikasi Ditolak')
                                ->danger()
                                ->send();

                            if ($record->developer) {
                                AppNotifier::database(
                                    $record->developer,
                                    'Aplikasi ditolak',
                                    "Aplikasi {$record->title} ditolak oleh admin. Silakan cek kembali data pengajuan.",
                                    'danger',
                                );
                            }
                        }),
                ])
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->visible(fn (App $record): bool => $record->payment_status !== 'refunded'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function renderPreviewBukti(App $record): string
    {
        $paymentProof = $record->payment_proof
            ? '<img src="' . asset('storage/' . $record->payment_proof) . '" style="width: 100%; border-radius: 8px;">'
            : '<p style="text-align: center; color: #777;">Bukti pembayaran belum diupload.</p>';

        $reviewScreenshot = $record->review_screenshot
            ? '<img src="' . asset('storage/' . $record->review_screenshot) . '" style="width: 100%; border-radius: 8px;">'
            : '<p style="text-align: center; color: #777;">Bukti closed testing belum diupload.</p>';

        return '
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                    <p style="font-weight: bold; margin-bottom: 8px; text-align: center;">1. Bukti Pembayaran</p>
                    ' . $paymentProof . '
                </div>

                <div style="border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                    <p style="font-weight: bold; margin-bottom: 8px; text-align: center;">2. Bukti Closed Testing</p>
                    ' . $reviewScreenshot . '
                </div>
            </div>
        ';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApps::route('/'),
            'view' => Pages\ViewApp::route('/{record}'),
        ];
    }
}
