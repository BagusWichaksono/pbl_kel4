<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        $totalDeveloper = User::where('role', 'developer')->count();
        $totalTester    = User::where('role', 'tester')->count();

        return [
            'semua' => Tab::make('Semua')
                ->icon('heroicon-o-users')
                ->badge($totalDeveloper + $totalTester),

            'developer' => Tab::make('Developer')
                ->icon('heroicon-o-code-bracket')
                ->badge($totalDeveloper)
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'developer')),

            'tester' => Tab::make('Tester')
                ->icon('heroicon-o-beaker')
                ->badge($totalTester)
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'tester')),
        ];
    }
}
