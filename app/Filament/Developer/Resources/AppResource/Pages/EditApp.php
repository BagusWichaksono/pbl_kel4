<?php

namespace App\Filament\Developer\Resources\AppResource\Pages;

use App\Filament\Developer\Resources\AppResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApp extends EditRecord
{
    protected static string $resource = AppResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Saat menyimpan form Edit biasa (tombol Save di header),
     * kita pastikan app_url TIDAK ikut tersimpan lewat form ini
     * — app_url hanya boleh diubah lewat tombol "Kirim Link" di dalam form.
     *
     * Ini mencegah developer tidak sengaja mengosongkan app_url
     * saat mengedit bagian lain aplikasi.
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Pertahankan nilai app_url yang sudah ada di database
        $data['app_url'] = $this->record->app_url;

        return $data;
    }
}
