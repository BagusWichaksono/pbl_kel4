<?php

namespace App\Filament\Admin\Pages;

use App\Models\App;
use Carbon\Carbon;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class VerifikasiAplikasi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static string $view = 'filament.admin.pages.verifikasi-aplikasi';

    // Properties untuk Livewire
    public string $filter = 'pending';
    public bool $showDetail = false;
    public ?int $selectedAppId = null;

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
     * Ambil data aplikasi yang sedang dilihat detailnya.
     */
    public function getSelectedAppProperty()
    {
        if (!$this->selectedAppId) {
            return null;
        }

        return App::with('developer')->find($this->selectedAppId);
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
        $this->showDetail = false;
        $this->selectedAppId = null;
    }

    /**
     * Tampilkan detail aplikasi.
     */
    public function lihatDetail(int $id): void
    {
        $this->selectedAppId = $id;
        $this->showDetail = true;
    }

    /**
     * Kembali ke daftar.
     */
    public function kembali(): void
    {
        $this->showDetail = false;
        $this->selectedAppId = null;
    }

    /**
     * Setujui pembayaran aplikasi.
     */
    public function setujui(int $id): void
    {
        $app = App::findOrFail($id);
        $app->update([
            'payment_status' => 'valid',
        ]);

        Notification::make()
            ->title('Pembayaran Disetujui')
            ->success()
            ->send();

        // Jika sedang di detail, kembali ke daftar
        $this->showDetail = false;
        $this->selectedAppId = null;
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

        // Jika sedang di detail, kembali ke daftar
        $this->showDetail = false;
        $this->selectedAppId = null;
    }
}
