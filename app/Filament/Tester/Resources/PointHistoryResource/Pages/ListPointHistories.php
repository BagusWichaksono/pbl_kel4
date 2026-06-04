<?php

namespace App\Filament\Tester\Resources\PointHistoryResource\Pages;

use App\Filament\Tester\Resources\PointHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPointHistories extends ListRecords
{
    protected static string $resource = PointHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
