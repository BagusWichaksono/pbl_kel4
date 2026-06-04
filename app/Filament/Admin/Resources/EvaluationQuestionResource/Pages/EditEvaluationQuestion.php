<?php

namespace App\Filament\Admin\Resources\EvaluationQuestionResource\Pages;

use App\Filament\Admin\Resources\EvaluationQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvaluationQuestion extends EditRecord
{
    protected static string $resource = EvaluationQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
