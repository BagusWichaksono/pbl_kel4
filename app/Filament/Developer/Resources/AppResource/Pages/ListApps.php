<?php

namespace App\Filament\Developer\Resources\AppResource\Pages;

use App\Filament\Developer\Resources\AppResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApps extends ListRecords
{
    protected static string $resource = AppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('+ Tambahkan Aplikasi'),
        ];
    }
}
