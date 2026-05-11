<?php

namespace App\Filament\Admin\Resources\VerifikasiSaldoResource\Pages;

use App\Filament\Admin\Resources\VerifikasiSaldoResource;
use Filament\Resources\Pages\ListRecords;

class ListVerifikasiSaldos extends ListRecords
{
    protected static string $resource = VerifikasiSaldoResource::class;

    protected function getHeaderActions(): array
    {
        return []; // Admin tidak membuat pencairan, hanya memverifikasi
    }
}
