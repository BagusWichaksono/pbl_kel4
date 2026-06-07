<?php

namespace App\Filament\Tester\Resources\CariMisiResource\Pages;

use App\Filament\Tester\Resources\CariMisiResource;
use App\Models\App;
use App\Models\ApplicationTester;
use App\Support\AppNotifier;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ViewCariMisi extends Page
{
    protected static string $resource = CariMisiResource::class;

    protected static string $view = 'filament.tester.pages.view-cari-misi';

    public ?int $recordId = null;

    public function mount(int $record): void
    {
        $this->recordId = $record;

        $this->getApp() ?? abort(404);
    }

    public function getTitle(): string
    {
        return $this->getApp()?->title ?? 'Detail Misi';
    }

    protected function getViewData(): array
    {
        $app = $this->getApp();

        return [
            'app' => $app,
            'filled' => $app ? CariMisiResource::testerCount($app) : 0,
            'maxTester' => $app ? CariMisiResource::maxTesters($app) : 1,
            'imageUrl' => $app ? CariMisiResource::getAppImageUrl($app) : null,
            'isRegistered' => $app ? CariMisiResource::testerHasJoined($app) : false,
            'isFull' => $app ? CariMisiResource::isMissionFull($app) : false,
            'isExpired' => $app ? CariMisiResource::isMissionExpired($app) : false,
            'canTakeMission' => $app ? CariMisiResource::canTakeMission($app) : false,
        ];
    }

    public function takeMission(): void
    {
        $app = $this->getApp();

        if (! $app) {
            Notification::make()
                ->title('Misi tidak ditemukan.')
                ->danger()
                ->send();

            return;
        }

        if (CariMisiResource::testerHasJoined($app)) {
            Notification::make()
                ->title('Sudah Terdaftar')
                ->body("Kamu sudah mengambil misi aplikasi \"{$app->title}\".")
                ->warning()
                ->send();

            return;
        }

        if (CariMisiResource::isMissionFull($app)) {
            Notification::make()
                ->title('Slot Penuh')
                ->body("Maaf, slot tester untuk aplikasi \"{$app->title}\" sudah penuh.")
                ->danger()
                ->send();

            return;
        }

        if (CariMisiResource::isMissionExpired($app)) {
            Notification::make()
                ->title('Sesi Berakhir')
                ->body("Sesi testing untuk aplikasi \"{$app->title}\" sudah berakhir.")
                ->danger()
                ->send();

            return;
        }

        ApplicationTester::create([
            'application_id' => $app->id,
            'tester_id' => Auth::id(),
            'status' => 'active',
        ]);

        Notification::make()
            ->title('Misi Berhasil Diambil')
            ->body("Kamu berhasil mengambil misi aplikasi \"{$app->title}\".")
            ->success()
            ->send();

        if ($tester = Auth::user()) {
            AppNotifier::database(
                $tester,
                'Misi berhasil diambil',
                "Kamu berhasil mengambil misi aplikasi {$app->title}.",
                'success',
            );
        }

        if ($app->developer) {
            Notification::make()
                ->title('Tester Baru Bergabung')
                ->body((Auth::user()?->name ?? 'Tester') . ' telah bergabung sebagai tester pada aplikasi ' . $app->title)
                ->info()
                ->sendToDatabase($app->developer);
        }
    }

    private function getApp(): ?App
    {
        if (! $this->recordId) {
            return null;
        }

        return App::query()
            ->whereKey($this->recordId)
            ->where('payment_status', 'valid')
            ->with(['developer'])
            ->withCount('testers')
            ->when(Schema::hasColumn('applications', 'testing_status'), function (Builder $query): void {
                $query->where(function (Builder $query): void {
                    $query
                        ->where('testing_status', 'open')
                        ->orWhereNull('testing_status');
                });
            })
            ->first();
    }
}
