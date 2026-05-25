<?php

namespace App\Filament\Tester\Resources\PenukaranPoinResource\Pages;

use App\Filament\Tester\Resources\PenukaranPoinResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenukaranPoins extends ListRecords
{
    protected static string $resource = PenukaranPoinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tukar Poin Sekarang')
                ->icon('heroicon-m-plus')
                ->visible(fn () => (\Illuminate\Support\Facades\Auth::user()?->testerProfile?->points ?? 0) > 0 && \App\Models\Withdrawal::query()->where('tester_id', \Illuminate\Support\Facades\Auth::id())->exists()),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return PenukaranPoinResource::getWidgets();
    }
}