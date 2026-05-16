<?php

namespace App\Filament\Tester\Resources\PenukaranPoinResource\Pages;

use App\Filament\Tester\Resources\PenukaranPoinResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreatePenukaranPoin extends CreateRecord
{
    protected static string $resource = PenukaranPoinResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $profile = $user?->testerProfile;
        $pointsWithdrawn = (int) ($data['points_withdrawn'] ?? 0);

        if (! $profile) {
            Notification::make()
                ->title('Profil tester belum ditemukan.')
                ->body('Silakan hubungi admin.')
                ->danger()
                ->send();

            throw new Halt();
        }

        if ($pointsWithdrawn < 1) {
            Notification::make()
                ->title('Jumlah poin tidak valid.')
                ->danger()
                ->send();

            throw new Halt();
        }

        if ($profile->points < $pointsWithdrawn) {
            Notification::make()
                ->title('Poin tidak cukup.')
                ->body('Saldo poin kamu saat ini hanya ' . $profile->points . ' poin.')
                ->danger()
                ->send();

            throw new Halt();
        }

        $data['tester_id'] = $user->id;
        $data['amount_rp'] = $pointsWithdrawn * 1000;
        $data['status'] = 'pending';

        return $data;
    }

    protected function afterCreate(): void
    {
        DB::transaction(function () {
            $profile = Auth::user()?->testerProfile;

            if ($profile) {
                $profile->decrement('points', $this->record->points_withdrawn);
            }
        });

        Notification::make()
            ->title('Request penukaran poin berhasil dibuat.')
            ->body('Poin kamu sudah dipotong dan request akan diproses oleh admin.')
            ->success()
            ->send();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}