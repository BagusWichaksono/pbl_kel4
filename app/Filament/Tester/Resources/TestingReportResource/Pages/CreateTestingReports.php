<?php

namespace App\Filament\Tester\Resources\TestingReportResource\Pages;

use App\Filament\Tester\Resources\TestingReportResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTestingReport extends CreateRecord
{
    protected static string $resource = TestingReportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = 'pending';
        return $data;
    }

    protected function afterFill(): void
    {
        if (request()->has('application_tester_id')) {
            $this->form->fill([
                'application_tester_id' => request()->query('application_tester_id'),
            ]);
        }
    }
}