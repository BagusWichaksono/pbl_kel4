<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ValidasiAplikasiResource\Pages;
use App\Filament\Admin\Resources\ValidasiAplikasiResource\RelationManagers;
use App\Models\App;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;


class ValidasiAplikasiResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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

                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'valid' => 'success',
                        'invalid' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Bayar'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y'),
            ])
            ->filters([
                // Filter berdasarkan status pengujian
                Tables\Filters\SelectFilter::make('testing_status')
                    ->options([
                        'open' => 'Terbuka',
                        'in_progress' => 'Sedang Dites',
                        'completed' => 'Selesai',
                    ]),
            ])
        ->actions([
            ViewAction::make(),

            Action::make('verifikasi')
                ->label('Verifikasi')
                ->icon('heroicon-o-check-circle')
                ->color('success')

                ->form([
                    Forms\Components\Select::make('payment_status')
                        ->label('Status Verifikasi')
                        ->options([
                            'valid' => 'Valid',
                            'invalid' => 'Tidak Valid',
                        ])
                        ->required(),
                ])

                ->action(function ($record, array $data) {
                    $record->update([
                        'payment_status' => $data['payment_status'],
                    ]);
                })

                ->visible(fn ($record) => $record->payment_status === 'pending'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListValidasiAplikasis::route('/'),
            'view' => Pages\ViewValidasiAplikasi::route('/{record}'),
            // 'create' => Pages\CreateValidasiAplikasi::route('/create'),
            'edit' => Pages\EditValidasiAplikasi::route('/{record}/edit'),
        ];
    }
    // Tambahkan method
    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && $user->isAdminOrSuperAdmin();
    }
}
