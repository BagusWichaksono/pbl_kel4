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
    
    // Taruh di grup khusus biar sidebarnya lebih rapi
    protected static ?string $navigationGroup = 'Manajemen Sistem'; 

    // ─── KEAMANAN BULLETPROOF: Hanya Superadmin ────────────────────────
    
    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

    // Blokir akses nembak URL /create
    public static function canCreate(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

    // Blokir akses nembak URL /edit
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

    // Blokir akses hapus data via API/URL
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user?->isSuperAdmin() ?? false;
    }

    // ─── QUERY: Hanya tampilkan user dengan role 'admin' ────────────────
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'admin')->latest();
    }

    // ─── TAMPILAN FORM (Soft UI Vibe) ──────────────────────────────────
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Akun Admin')
                    ->description('Pastikan email yang didaftarkan aktif dan password cukup kuat.')
                    ->icon('heroicon-o-user-plus') // Tambah icon di header section
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->prefixIcon('heroicon-m-user') // Icon di dalam kolom input
                            ->placeholder('Contoh: Bagus Wichaksono'),

                        Forms\Components\TextInput::make('email')
                            ->label('Alamat Email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'email', ignorable: fn ($record) => $record)
                            ->prefixIcon('heroicon-m-envelope')
                            ->placeholder('Contoh: admin@tesyuk.com'),

                        Forms\Components\TextInput::make('password')
                            ->label('Password Akses')
                            ->password()
                            ->revealable() // Tombol lihat password
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->prefixIcon('heroicon-m-lock-closed')
                            ->placeholder('Minimal 8 karakter')
                            ->minLength(8),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->same('password')
                            ->dehydrated(false)
                            ->prefixIcon('heroicon-m-check-badge')
                            ->placeholder('Ketik ulang password'),

                        // Role dikunci ke 'admin', tidak bisa diubah user
                        Forms\Components\Hidden::make('role')
                            ->default('admin'),
                    ])->columns(2), // Dibagi 2 kolom biar imbang
            ]);
    }

    // ─── TAMPILAN TABEL ────────────────────────────────────────────────
    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Admin')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (User $record): string => $record->email), // Email ditaruh di bawah nama biar hemat tempat

                // Kasih badge visual biar kelihatan resmi
                Tables\Columns\TextColumn::make('role')
                    ->label('Hak Akses')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn () => 'Admin Panel'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Bergabung Sejak')
                    ->dateTime('d F Y') // Format tanggal lebih manusiawi
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([ // Jadikan dropdown biar rapi
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Cabut Akses Admin')
                        ->modalDescription('Akun admin ini akan dihapus permanen. Mereka tidak akan bisa login lagi. Yakin?')
                        ->modalSubmitActionLabel('Ya, Hapus Akses'),
                ])
                ->icon('heroicon-m-ellipsis-vertical')
                ->tooltip('Aksi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Sistem Aman, Belum Ada Admin Lain')
            ->emptyStateDescription('Saat ini hanya Superadmin yang memegang kendali. Tambahkan admin baru jika butuh bantuan.')
            ->emptyStateIcon('heroicon-o-shield-exclamation');
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