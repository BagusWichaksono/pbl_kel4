<?php

namespace App\Filament\Admin\Resources\TestingReportResource\Pages;

use App\Filament\Admin\Resources\TestingReportResource;
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
