<?php

namespace App\Filament\Admin\Resources\ValidasiAplikasiResource\Pages;

use App\Filament\Admin\Resources\ValidasiAplikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditValidasiAplikasi extends EditRecord
{
    protected static string $resource = ValidasiAplikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
