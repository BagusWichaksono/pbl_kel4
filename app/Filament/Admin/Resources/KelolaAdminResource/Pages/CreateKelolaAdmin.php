<?php

namespace App\Filament\Admin\Resources\KelolaAdminResource\Pages;

use App\Filament\Admin\Resources\KelolaAdminResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKelolaAdmin extends CreateRecord
{
    protected static string $resource = KelolaAdminResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Pastikan role selalu 'admin' saat create dari halaman ini
        $data['role'] = 'admin';
        return $data;
    }
}
