<?php

namespace App\Filament\Admin\Resources\KelolaAdminResource\Pages;

use App\Filament\Admin\Resources\KelolaAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKelolaAdmin extends EditRecord
{
    protected static string $resource = KelolaAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Pastikan role tidak bisa diubah dari halaman ini
        $data['role'] = 'admin';
        return $data;
    }
}
