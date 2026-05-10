<?php

namespace App\Filament\Tester\Resources\PenukaranPoinResource\Pages;

use App\Filament\Tester\Resources\PenukaranPoinResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPenukaranPoins extends ListRecords
{
    protected static string $resource = PenukaranPoinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
}
