<?php

namespace App\Filament\Tester\Resources\TestingReportResource\Pages;

use App\Filament\Tester\Resources\TestingReportResource;
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