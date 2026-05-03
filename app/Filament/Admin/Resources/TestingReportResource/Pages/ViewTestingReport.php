<?php

namespace App\Filament\Admin\Resources\TestingReportResource\Pages;

use App\Filament\Admin\Resources\TestingReportResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTestingReport extends ViewRecord
{
    protected static string $resource = TestingReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
