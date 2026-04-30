<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KelolaAdminResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KelolaAdminResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Admin';
    protected static ?string $pluralModelLabel = 'Kelola Admin';
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationLabel = 'Kelola Admin';
    protected static ?int $navigationSort = 10;

    // ─── Hanya super_admin yang bisa melihat menu ini ────────────────────────
    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && $user->isSuperAdmin();
    }

    // ─── Hanya tampilkan user dengan role 'admin' ─────────────────────────────
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'admin');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Admin')
                    ->description('Isi data akun admin yang akan ditambahkan.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Nailah Salsabila'),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignorable: fn ($record) => $record)
                            ->placeholder('Contoh: nailah@gmail.com'),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->placeholder('Minimal 8 karakter')
                            ->minLength(8),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->same('password')
                            ->dehydrated(false)
                            ->placeholder('Tulis Ulang Password'),

                        // Role dikunci ke 'admin', tidak bisa diubah user
                        Forms\Components\Hidden::make('role')
                            ->default('admin'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diubah')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Akun Admin')
                    ->modalDescription('Akun admin ini akan dihapus permanen. Yakin ingin melanjutkan?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Belum ada admin')
            ->emptyStateDescription('Tambahkan akun admin baru menggunakan tombol di atas.')
            ->emptyStateIcon('heroicon-o-shield-check');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListKelolaAdmins::route('/'),
            'create' => Pages\CreateKelolaAdmin::route('/create'),
            'edit'   => Pages\EditKelolaAdmin::route('/{record}/edit'),
        ];
    }
}
