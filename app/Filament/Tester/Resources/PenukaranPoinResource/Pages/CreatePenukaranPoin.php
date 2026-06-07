<?php

namespace App\Filament\Tester\Resources\PenukaranPoinResource\Pages;

use App\Filament\Tester\Resources\PenukaranPoinResource;
use App\Support\AppNotifier;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreatePenukaranPoin extends CreateRecord
{
    protected static string $resource = PenukaranPoinResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();
        $profile = $user?->testerProfile;
        $pointsWithdrawn = (int) ($data['points_withdrawn'] ?? 0);
        $availablePoints = (int) ($profile?->points ?? 0);

        if ($pointsWithdrawn < 1) {
            Notification::make()
                ->title('Jumlah poin tidak valid.')
                ->danger()
                ->send();

            throw new Halt();
        }

        if ($availablePoints < $pointsWithdrawn) {
            Notification::make()
                ->title('Poin tidak mencukupi.')
                ->body('Saldo poin kamu saat ini hanya ' . $availablePoints . ' poin.')
                ->danger()
                ->send();

            throw new Halt();
        }

        $data['tester_id'] = $user->id;
        $data['amount_rp'] = $pointsWithdrawn * 1000;
        $data['status'] = 'pending';
        $data['invoice_code'] = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        return $data;
    }

    protected function afterCreate(): void
    {
        $profile = Auth::user()?->testerProfile;

        if ($profile) {
            $profile->points -= (int) $this->record->points_withdrawn;
            $profile->save();
            
            // Catat ke riwayat (Point Ledger)
            \App\Models\PointHistory::create([
                'tester_id' => $profile->user_id,
                'amount' => (int) $this->record->points_withdrawn,
                'type' => 'debit',
                'description' => 'Penarikan dana dengan invoice: ' . $this->record->invoice_code,
            ]);
        }

        Notification::make()
            ->title('Request penukaran poin berhasil dibuat.')
            ->body('Poin kamu sudah dipotong dan request akan diproses oleh admin.')
            ->success()
            ->send();

        if ($tester = Auth::user()) {
            AppNotifier::database(
                $tester,
                'Penukaran poin diajukan',
                'Permintaan penukaran ' . number_format((int) $this->record->points_withdrawn, 0, ',', '.') . ' poin sedang diproses admin.',
                'warning',
            );
        }

        AppNotifier::adminsDatabase(
            'Pengajuan penukaran poin baru',
            (Auth::user()?->name ?? 'Tester') . ' mengajukan penukaran ' . number_format((int) $this->record->points_withdrawn, 0, ',', '.') . ' poin.',
            'warning',
        );
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
