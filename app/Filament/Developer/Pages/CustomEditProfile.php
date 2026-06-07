<?php

namespace App\Filament\Developer\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile;
use Illuminate\Support\Facades\Hash;

class CustomEditProfile extends EditProfile
{
    protected static string $layout = 'filament-panels::components.layout.index';

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Edit Profil';

    protected static ?string $title = 'Edit Profil';

    protected static bool $shouldRegisterNavigation = false;

    public function getTitle(): string
    {
        return 'Edit Profil';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profil Akun')
                    ->description('Kelola foto dan informasi dasar akun kamu.')
                    ->icon('heroicon-o-user-circle')
                    ->extraAttributes(['class' => 'tesyuk-profile-section tesyuk-profile-avatar-section'])
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Foto Profil')
                            ->image()
                            ->avatar()
                            ->directory('avatars')
                            ->imageEditor()
                            ->imagePreviewHeight('10.75rem')
                            ->extraAttributes(['class' => 'tesyuk-profile-avatar-upload'])
                            ->columnSpanFull(),

                        $this->getNameFormComponent()
                            ->label('Nama Lengkap')
                            ->prefixIcon('heroicon-o-user'),

                        TextInput::make('phone')
                            ->label('Nomor HP')
                            ->prefixIcon('heroicon-o-device-phone-mobile')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Contoh: 081234567890'),
                    ])
                    ->columns(2),

                Section::make('Keamanan Akun')
                    ->description('Gunakan password lama untuk mengganti password baru.')
                    ->icon('heroicon-o-shield-check')
                    ->extraAttributes(['class' => 'tesyuk-profile-section'])
                    ->schema([
                        $this->getEmailFormComponent()
                            ->label('Email')
                            ->prefixIcon('heroicon-o-envelope'),

                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->prefixIcon('heroicon-o-lock-closed')
                            ->password()
                            ->revealable()
                            ->currentPassword()
                            ->requiredWith('password')
                            ->dehydrated(false)
                            ->helperText('Wajib diisi jika ingin mengganti password.'),

                        TextInput::make('password')
                            ->label('Password Baru')
                            ->prefixIcon('heroicon-o-key')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->confirmed()
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText('Kosongkan jika tidak ingin mengganti password.'),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->prefixIcon('heroicon-o-check-badge')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->helperText('Isi ulang password baru yang sama.'),
                    ])
                    ->columns(2),
            ]);
    }
}
