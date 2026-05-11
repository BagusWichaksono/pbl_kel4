<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AppResource\Pages;
use App\Models\App;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
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

    // Badge di sidebar — tampilkan jumlah aplikasi pending
    public static function getNavigationBadge(): ?string
    {
        $count = App::where('payment_status', 'pending')->count();
        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $adaLama = App::where('payment_status', 'pending')
            ->where('created_at', '<=', now()->subDays(3))
            ->exists();
        return $adaLama ? 'danger' : 'warning';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        $count = App::where('payment_status', 'pending')->count();
        return $count > 0 ? "{$count} aplikasi menunggu verifikasi" : null;
    }

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
                        Forms\Components\TextInput::make('title')->label('Nama Aplikasi')->disabled(),
                        Forms\Components\TextInput::make('platform')->label('Platform')->disabled(),
                        Forms\Components\TextInput::make('url')->label('URL / Link')->disabled(),
                        Forms\Components\Textarea::make('description')->label('Deskripsi')->disabled()->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Berkas Validasi')
                    ->description('Cek bukti pembayaran dan bukti Closed Testing (Google Console).')
                    ->schema([
                        Forms\Components\FileUpload::make('payment_proof')->label('Bukti Pembayaran')->disabled(),
                        Forms\Components\FileUpload::make('review_screenshot')->label('Bukti Closed Testing (MVP)')->disabled(),
                    ])->columns(2),
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
                    ->description(fn (App $record) => "Platform: " . $record->platform),

                Tables\Columns\TextColumn::make('title')
                    ->label('Aplikasi')
                    ->searchable()
                    ->weight('bold'),

                // Status Pembayaran
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Bayar')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'valid' => 'success',
                        'invalid' => 'danger',
                        default => 'gray',
                    }),

                // Status Testing (MVP)
                Tables\Columns\TextColumn::make('testing_status')
                    ->label('Status Testing')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray'
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
            ])
            ->actions([
                // PREVIEW BUKTI (NO BLADE)
                Tables\Actions\Action::make('cek_bukti')
                    ->label('Cek Bukti')
                    ->icon('heroicon-m-magnifying-glass')
                    ->color('info')
                    ->modalHeading('Validasi Berkas Developer')
                    ->modalContent(fn (App $record) => new HtmlString('
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <div style="border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                                <p style="font-weight: bold; margin-bottom: 8px; text-align: center;">1. Bukti Pembayaran</p>
                                <img src="' . asset('storage/' . $record->payment_proof) . '" style="width: 100%; border-radius: 8px;">
                            </div>
                            <div style="border: 1px solid #ddd; padding: 10px; border-radius: 10px;">
                                <p style="font-weight: bold; margin-bottom: 8px; text-align: center;">2. Bukti Closed Testing (MVP)</p>
                                <img src="' . asset('storage/' . $record->review_screenshot) . '" style="width: 100%; border-radius: 8px;">
                            </div>
                        </div>
                    '))
                    ->modalSubmitAction(false),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('approve')
                        ->label('Approve Semuanya')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (App $record) {
                            $record->update([
                                'payment_status' => 'valid',
                                'testing_status' => 'open'
                            ]);
                            Notification::make()->title('Aplikasi & Pembayaran Disetujui')->success()->send();
                        }),

                    Tables\Actions\Action::make('reject')
                        ->label('Tolak Aplikasi')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (App $record) {
                            $record->update([
                                'payment_status' => 'invalid',
                                'testing_status' => 'rejected'
                            ]);
                            Notification::make()->title('Aplikasi Ditolak')->danger()->send();
                        }),
                ])->icon('heroicon-m-ellipsis-vertical'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApps::route('/'),
        ];
    }
}
