<?php

namespace App\Filament\Tester\Pages;

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

    protected static ?string $navigationLabel = 'Pengaturan Akun';

    protected static ?string $title = 'Pengaturan Akun';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profil')
                    ->description('Kelola informasi dasar akun kamu.')
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('Foto Profil')
                            ->image()
                            ->avatar()
                            ->directory('avatars')
                            ->imageEditor()
                            ->columnSpanFull(),

                        $this->getNameFormComponent()
                            ->label('Nama Lengkap'),

                        TextInput::make('phone')
                            ->label('Nomor HP')
                            ->tel()
                            ->maxLength(20)
                            ->placeholder('Contoh: 081234567890'),
                    ])
                    ->columns(2),

                Section::make('Keamanan')
                    ->description('Gunakan password lama untuk mengganti password baru.')
                    ->schema([
                        $this->getEmailFormComponent()
                            ->label('Email'),

                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->revealable()
                            ->currentPassword()
                            ->requiredWith('password')
                            ->dehydrated(false)
                            ->helperText('Wajib diisi jika ingin mengganti password.'),

                        TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->confirmed()
                            ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->helperText('Kosongkan jika tidak ingin mengganti password.'),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->revealable()
                            ->dehydrated(false)
                            ->helperText('Isi ulang password baru yang sama.'),
                    ])
                    ->columns(2),
            ]);
    }
}