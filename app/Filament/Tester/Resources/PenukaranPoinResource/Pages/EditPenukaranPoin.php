<?php

namespace App\Filament\Tester\Resources\PenukaranPoinResource\Pages;

use App\Filament\Tester\Resources\PenukaranPoinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPenukaranPoin extends EditRecord
{
    protected static string $resource = PenukaranPoinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
