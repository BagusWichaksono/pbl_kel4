<?php

namespace App\Filament\Developer\Resources\TransactionResource\Pages;

use App\Filament\Developer\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function configureViewAction(Tables\Actions\ViewAction $action): void
    {
        parent::configureViewAction($action);

        $action->url(null);
    }

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
