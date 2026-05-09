<?php

namespace App\Filament\Tester\Resources\CariMisiResource\Pages;

use App\Filament\Tester\Resources\CariMisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCariMisis extends ListRecords
{
    protected static string $resource = CariMisiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
