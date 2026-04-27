<?php

namespace App\Filament\Admin\Resources\ValidasiAplikasiResource\Pages;

use App\Filament\Admin\Resources\ValidasiAplikasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;

class ViewValidasiAplikasi extends ViewRecord
{
    protected static string $resource = ValidasiAplikasiResource::class;

    public function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            TextEntry::make('title')
                ->label('Nama Aplikasi'),

            TextEntry::make('description')
                ->label('Deskripsi'),

            ImageEntry::make('payment_proof')
                ->label('Bukti Pembayaran'),

            TextEntry::make('payment_status')
                ->label('Status Pembayaran'),

            TextEntry::make('testing_status')
                ->label('Status Pengujian'),
        ]);
}
}
