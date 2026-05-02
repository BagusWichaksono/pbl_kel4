<?php

namespace App\Filament\Tester\Pages;

use App\Models\App;
use App\Models\ApplicationTester;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class CariMisi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';
    protected static string $view = 'filament.tester.pages.cari-misi';

    public string $search = '';

    /**
     * Ambil daftar aplikasi valid yang bisa diambil tester.
     */
    public function getApplicationsProperty()
    {
        $query = App::with(['developer', 'testers'])
            ->where('payment_status', 'valid')
            ->withCount('testers')
            ->latest();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhereHas('developer', function ($q2) {
                      $q2->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        return $query->get();
    }

    /**
     * Cek apakah user sudah terdaftar sebagai tester di aplikasi tertentu.
     */
    public function isRegistered(int $appId): bool
    {
        return ApplicationTester::where('application_id', $appId)
            ->where('tester_id', Auth::id())
            ->exists();
    }

    /**
     * Daftarkan tester ke aplikasi.
     */
    public function daftarMisi(int $appId): void
    {
        $app = App::withCount('testers')->findOrFail($appId);

        // Cek apakah sudah terdaftar
        if ($this->isRegistered($appId)) {
            Notification::make()
                ->title('Sudah Terdaftar')
                ->body("Kamu sudah terdaftar sebagai tester di aplikasi \"{$app->title}\".")
                ->warning()
                ->send();
            return;
        }

        // Cek apakah slot penuh
        if ($app->testers_count >= $app->max_testers) {
            Notification::make()
                ->title('Slot Penuh')
                ->body("Maaf, slot tester untuk aplikasi \"{$app->title}\" sudah penuh ({$app->max_testers}/{$app->max_testers}).")
                ->danger()
                ->send();
            return;
        }

        // Cek apakah sesi testing sudah berakhir
        if ($app->end_date && $app->end_date->isPast()) {
            Notification::make()
                ->title('Sesi Berakhir')
                ->body("Sesi testing untuk aplikasi \"{$app->title}\" sudah berakhir.")
                ->danger()
                ->send();
            return;
        }

        // Daftarkan tester
        ApplicationTester::create([
            'application_id' => $appId,
            'tester_id' => Auth::id(),
            'status' => 'active',
        ]);

        Notification::make()
            ->title('Berhasil Mendaftar!')
            ->body("Kamu berhasil mendaftar sebagai tester di aplikasi \"{$app->title}\". Selamat menguji!")
            ->success()
            ->send();
    }
}
