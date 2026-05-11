<?php

namespace App\Filament\Developer\Resources\RiwayatTransaksiResource\Pages;

use App\Filament\Developer\Resources\RiwayatTransaksiResource;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatTransaksis extends ListRecords
{
    protected static string $resource = RiwayatTransaksiResource::class;

    protected function getHeaderActions(): array
    {
        return []; // Developer tidak bisa buat transaksi manual
    }
}
