<?php

namespace App\Filament\Developer\Resources\TestingReportResource\Pages;

use App\Filament\Developer\Resources\TestingReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestingReport extends EditRecord
{
    protected static string $resource = TestingReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
