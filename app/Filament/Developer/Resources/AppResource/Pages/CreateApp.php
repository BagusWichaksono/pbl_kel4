<?php

namespace App\Filament\Developer\Resources\AppResource\Pages;

use App\Filament\Developer\Resources\AppResource;
use App\Support\AppNotifier;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateApp extends CreateRecord
{
    protected static string $resource = AppResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['developer_id'] = Auth::id();
        
        return $data;
    }

    protected function afterCreate(): void
    {
        $developer = Auth::user();
        $title = $this->record?->title ?? 'Aplikasi';

        if ($developer) {
            AppNotifier::database(
                $developer,
                'Aplikasi berhasil diajukan',
                "Aplikasi {$title} sedang menunggu verifikasi admin.",
                'success',
            );
        }

        AppNotifier::adminsDatabase(
            'Aplikasi baru menunggu verifikasi',
            ($developer?->name ?? 'Developer') . " mengajukan aplikasi {$title}.",
        );
    }
}
