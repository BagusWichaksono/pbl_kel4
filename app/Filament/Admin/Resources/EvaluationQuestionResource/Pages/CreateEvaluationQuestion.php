<?php

namespace App\Filament\Admin\Resources\EvaluationQuestionResource\Pages;

use App\Filament\Admin\Resources\EvaluationQuestionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvaluationQuestion extends CreateRecord
{
    protected static string $resource = EvaluationQuestionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
