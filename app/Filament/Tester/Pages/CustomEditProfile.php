<?php

namespace App\Filament\Tester\Pages;

use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class CustomEditProfile extends BaseEditProfile
{
    
    protected static string $layout = 'filament-panels::components.layout.index';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // Ini komponen untuk upload foto
                FileUpload::make('avatar_url')
                    ->label('Foto Profil (Avatar)')
                    ->avatar() // Otomatis bikin tampilannya bulat
                    ->imageEditor() // Biar user bisa nge-crop foto
                    ->circleCropper() // Crop-nya bentuk lingkaran
                    ->directory('avatars'), // Disimpan di folder storage/app/public/avatars
                
                // Ini komponen bawaan dari Filament untuk form lainnya
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}