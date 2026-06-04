<?php

namespace App\Filament\Admin\Resources\EvaluationQuestionResource\Pages;

use App\Filament\Admin\Resources\EvaluationQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvaluationQuestions extends ListRecords
{
    protected static string $resource = EvaluationQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pertanyaan'),
        ];
    }
}
