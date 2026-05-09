<?php

namespace App\Filament\Admin\Pages;

use App\Models\App;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class VerifikasiAplikasi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string $view = 'filament.admin.pages.verifikasi-aplikasi';

    public string $filter = 'pending';

    public static function canAccess(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && $user->isAdminOrSuperAdmin();
    }

    /**
     * Ambil daftar aplikasi berdasarkan filter status.
     */
    public function getApplicationsProperty()
    {
        $query = App::with('developer')->latest();

        if ($this->filter !== 'semua') {
            $query->where('payment_status', $this->filter);
        }

        return $query->get();
    }

    /**
     * Hitung jumlah aplikasi pending.
     */
    public function getPendingCountProperty(): int
    {
        return App::where('payment_status', 'pending')->count();
    }

    /**
     * Set filter status.
     */
    public function setFilter(string $status): void
    {
        $this->filter = $status;
    }

    /**
     * Setujui pembayaran aplikasi.
     */
    public function setujui(int $id): void
    {
        $app = App::findOrFail($id);
        $app->update([
            'payment_status' => 'valid',
            'start_date'     => now(),
            'end_date'       => now()->addDays(14),
        ]);

        Notification::make()
            ->title('Pembayaran Disetujui')
            ->body("Aplikasi \"{$app->title}\" telah disetujui. Sesi testing 14 hari dimulai.")
            ->success()
            ->send();
    }

    /**
     * Tolak pembayaran aplikasi.
     */
    public function tolak(int $id): void
    {
        $app = App::findOrFail($id);
        $app->update([
            'payment_status' => 'invalid',
        ]);

        Notification::make()
            ->title('Pembayaran Ditolak')
            ->body("Aplikasi \"{$app->title}\" ditandai sebagai tidak valid.")
            ->danger()
            ->send();
    }
}