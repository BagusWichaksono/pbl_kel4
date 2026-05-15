<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AppResource\Pages;
use App\Models\App;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class AppResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $modelLabel = 'Validasi Aplikasi';

    protected static ?string $pluralModelLabel = 'Verifikasi Aplikasi';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Verifikasi Aplikasi';

    protected static ?string $navigationGroup = 'Validasi & Keuangan';

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('developer.name')
                    ->label('Developer')
                    ->searchable()
                    ->sortable()
                    ->description(fn (App $record) => 'Platform: ' . ($record->platform ?? '-')),

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
                        'invalid' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'invalid' => 'Tidak Valid',
                        default => '-',
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
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Status Pembayaran')
                    ->options([
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'invalid' => 'Tidak Valid',
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
                Tables\Actions\Action::make('cek_bukti')
                    ->label('Cek Bukti')
                    ->icon('heroicon-m-magnifying-glass')
                    ->color('info')
                    ->modalHeading('Validasi Berkas Developer')
                    ->modalContent(fn (App $record) => new HtmlString(self::renderPreviewBukti($record)))
                    ->modalSubmitAction(false),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Terima Aplikasi')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (App $record) {
                            $record->update([
                                'payment_status' => 'valid',
                                'testing_status' => 'open',
                            ]);

                            Notification::make()
                                ->title('Aplikasi & Pembayaran Disetujui')
                                ->success()
                                ->send();
                        }),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak Aplikasi')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (App $record) {
                            $record->update([
                                'payment_status' => 'invalid',
                                'testing_status' => 'rejected',
                            ]);

                            Notification::make()
                                ->title('Aplikasi Ditolak')
                                ->danger()
                                ->send();
                        }),
                ])->icon('heroicon-m-ellipsis-vertical'),
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
        ];
    }
}