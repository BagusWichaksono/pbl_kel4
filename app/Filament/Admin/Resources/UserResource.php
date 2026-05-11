<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel        = 'Pengguna';
    protected static ?string $pluralModelLabel  = 'Manajemen Pengguna';

    protected static ?string $navigationIcon  = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Manajemen Pengguna';

    // ─────────────────────────────────────────────────────────
    //  FORM
    // ─────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('role')
                    ->label('Peran')
                    ->options([
                        'developer' => 'Developer',
                        'tester'    => 'Tester',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
            ]);
    }

    // ─────────────────────────────────────────────────────────
    //  TABLE
    // ─────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'developer' => 'info',
                        'tester'    => 'success',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'developer' => 'Developer',
                        'tester'    => 'Tester',
                        default     => $state,
                    }),

                // Kolom khusus Developer: jumlah aplikasi yang pernah didaftarkan
                Tables\Columns\TextColumn::make('applications_count')
                    ->label('Aplikasi')
                    ->counts('applications')
                    ->badge()
                    ->color('info')
                    ->tooltip('Jumlah aplikasi yang didaftarkan')
                    ->visibleOn('developer'), // hanya tampil di tab developer (dikontrol via tab)

                // Kolom khusus Tester: poin yang dimiliki
                Tables\Columns\TextColumn::make('testerProfile.points')
                    ->label('Poin')
                    ->badge()
                    ->color('success')
                    ->default('0')
                    ->tooltip('Total poin tester'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])  // filter role dihapus — sudah digantikan tab
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    // Hanya tampilkan developer dan tester (bukan admin/super_admin)
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('role', ['developer', 'tester']);
    }
}
