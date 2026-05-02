<?php

namespace App\Filament\Admin\Resources\KelolaAdminResource\Pages;

use App\Filament\Admin\Resources\KelolaAdminResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKelolaAdmins extends ListRecords
{
    protected static string $resource = KelolaAdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Admin Baru'),
        ];
    }
}
