<?php

namespace App\Filament\Tester\Resources\MisiSayaResource\Pages;

use App\Filament\Tester\Resources\MisiSayaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMisiSaya extends EditRecord
{
    protected static string $resource = MisiSayaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
