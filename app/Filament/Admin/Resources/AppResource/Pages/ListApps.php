<?php

namespace App\Filament\Admin\Resources\AppResource\Pages;

use App\Filament\Admin\Resources\AppResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApps extends ListRecords
{
    protected static string $resource = AppResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
