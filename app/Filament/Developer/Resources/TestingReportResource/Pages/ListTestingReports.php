<?php

namespace App\Filament\Developer\Resources\TestingReportResource\Pages;

use App\Filament\Developer\Resources\TestingReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestingReports extends ListRecords
{
    protected static string $resource = TestingReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
