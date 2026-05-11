<?php

namespace App\Filament\Developer\Resources\AppResource\Pages;

use App\Filament\Developer\Resources\AppResource;
use App\Models\App;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ViewAppTesters extends Page
{
    protected static string $resource = AppResource::class;

    protected static string $view = 'developer.view-app-testers';

    // Simpan hanya ID agar Livewire bisa serialize dengan aman
    public int $recordId;

    // Record di-load dari DB saat dibutuhkan
    public function getRecord(): App
    {
        return App::with(['testers.tester.testerProfile'])->findOrFail($this->recordId);
    }

    public function mount(int|string $record): void
    {
        $app = App::findOrFail($record);

        abort_unless(
            $app->developer_id === Auth::id(),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );

        $this->recordId = $app->id;
    }

    public function getTitle(): string
    {
        return 'Daftar Tester: ' . $this->getRecord()->title;
    }

    protected function getHeaderActions(): array
    {
        $app = $this->getRecord();

        if ($app->start_date) {
            return [
                Action::make('testing_started')
                    ->label('Sesi Testing Dimulai: ' . Carbon::parse($app->start_date)->format('d M Y'))
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->disabled(),
            ];
        }

        return [
            Action::make('start_testing')
                ->label('Mulai Sesi Testing')
                ->icon('heroicon-o-play-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Mulai Sesi Testing?')
                ->modalDescription(fn () => 'Tindakan ini akan mengisi tanggal mulai testing untuk aplikasi "' . $this->getRecord()->title . '" dengan tanggal hari ini.')
                ->modalSubmitActionLabel('Ya, Mulai Sekarang')
                ->action(function () {
                    App::findOrFail($this->recordId)->update([
                        'start_date'     => Carbon::today(),
                        'testing_status' => 'in_progress',
                    ]);

                    Notification::make()
                        ->title('Sesi testing berhasil dimulai!')
                        ->body('Tanggal mulai telah diisi: ' . Carbon::today()->format('d M Y'))
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->getRecord(),
        ];
    }
}
