<?php

namespace App\Filament\Developer\Resources\DailyReportResource\Pages;

use App\Filament\Developer\Resources\DailyReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDailyReport extends EditRecord
{
    protected static string $resource = DailyReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
