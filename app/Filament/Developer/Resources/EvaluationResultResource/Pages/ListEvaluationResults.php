<?php

namespace App\Filament\Developer\Resources\EvaluationResultResource\Pages;

use App\Filament\Developer\Resources\EvaluationResultResource;
use Filament\Resources\Pages\ListRecords;

class ListEvaluationResults extends ListRecords
{
    protected static string $resource = EvaluationResultResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
