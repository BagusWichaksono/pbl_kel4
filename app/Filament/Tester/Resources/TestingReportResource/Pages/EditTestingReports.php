<?php

namespace App\Filament\Tester\Resources\TestingReportResource\Pages;

use App\Filament\Tester\Resources\TestingReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestingReport extends EditRecord
{
    protected static string $resource = TestingReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}