<?php

namespace App\Filament\Admin\Resources\ValidasiAplikasiResource\Pages;

use App\Filament\Admin\Resources\ValidasiAplikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListValidasiAplikasis extends ListRecords
{
    protected static string $resource = ValidasiAplikasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
