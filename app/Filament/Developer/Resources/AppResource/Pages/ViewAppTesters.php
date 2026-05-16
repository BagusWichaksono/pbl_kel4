<?php

namespace App\Filament\Developer\Resources\AppResource\Pages;

use App\Filament\Developer\Resources\AppResource;
use App\Models\App;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class ViewAppTesters extends Page
{
    protected static string $resource = AppResource::class;

    protected static string $view = 'filament.developer.pages.view-app-testers';

    // Simpan hanya ID agar Livewire bisa serialize dengan aman
    public int $recordId;

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
        return [
            Action::make('copy_tester_emails')
                ->label('Copy Email Tester')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('gray')
                ->disabled(fn (): bool => $this->activeTesterCount() <= 0)
                ->tooltip(function (): string {
                    if ($this->activeTesterCount() <= 0) {
                        return 'Belum ada tester aktif yang bisa dicopy.';
                    }

                    return 'Copy daftar email tester aktif.';
                })
                ->modalHeading('Daftar Email Tester')
                ->modalDescription('Copy daftar email ini, lalu masukkan ke Google Play Console sebagai tester closed testing.')
                ->modalContent(fn () => new HtmlString($this->emailCopyBoxHtml()))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),

            Action::make('input_app_link')
                ->label('Input Link Closed Testing')
                ->icon('heroicon-o-link')
                ->color('primary')
                ->disabled(fn (): bool =>
                    $this->activeTesterCount() < 12
                    || filled($this->getRecord()->start_date)
                )
                ->tooltip(function (): string {
                    $app = $this->getRecord();

                    if (filled($app->start_date)) {
                        return 'Link tidak bisa diubah karena sesi testing sudah dimulai.';
                    }

                    if ($this->activeTesterCount() < 12) {
                        return 'Minimal harus ada 12 tester aktif terlebih dahulu.';
                    }

                    return 'Input link closed testing dari Google Play Console.';
                })
                ->form([
                    Forms\Components\TextInput::make('app_link')
                        ->label('Link Closed Testing')
                        ->required()
                        ->url()
                        ->maxLength(255)
                        ->default(fn () => $this->getRecord()->app_link)
                        ->placeholder('Contoh: https://play.google.com/apps/testing/...')
                        ->helperText('Isi link ini setelah email tester dimasukkan ke Google Play Console.'),
                ])
                ->action(function (array $data): void {
                    App::findOrFail($this->recordId)->update([
                        'app_link' => $data['app_link'],
                    ]);

                    Notification::make()
                        ->title('Link closed testing berhasil disimpan')
                        ->success()
                        ->send();
                }),

            Action::make('start_testing')
                ->label('Mulai Sesi Testing')
                ->icon('heroicon-o-play-circle')
                ->color('success')
                ->disabled(fn (): bool =>
                    $this->activeTesterCount() < 12
                    || blank($this->getRecord()->app_link)
                    || filled($this->getRecord()->start_date)
                )
                ->tooltip(function (): string {
                    $app = $this->getRecord();

                    if (filled($app->start_date)) {
                        return 'Sesi testing sudah dimulai.';
                    }

                    if ($this->activeTesterCount() < 12) {
                        return 'Minimal harus ada 12 tester aktif terlebih dahulu.';
                    }

                    if (blank($app->app_link)) {
                        return 'Input link closed testing terlebih dahulu.';
                    }

                    return 'Mulai sesi testing.';
                })
                ->form([
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Tanggal Mulai Testing')
                        ->required()
                        ->default(now())
                        ->minDate(now())
                        ->helperText('Tanggal selesai akan otomatis dihitung 14 hari setelah tanggal mulai.'),
                ])
                ->requiresConfirmation()
                ->modalHeading('Mulai Sesi Testing?')
                ->modalDescription('Tanggal selesai testing akan otomatis dibuat 14 hari setelah tanggal mulai.')
                ->modalSubmitActionLabel('Ya, Mulai Sesi')
                ->action(function (array $data): void {
                    $startDate = Carbon::parse($data['start_date']);
                    $endDate = $startDate->copy()->addDays(14);

                    App::findOrFail($this->recordId)->update([
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'testing_status' => 'in_progress',
                    ]);

                    Notification::make()
                        ->title('Sesi testing berhasil dimulai!')
                        ->body('Tanggal selesai otomatis: ' . $endDate->format('d M Y'))
                        ->success()
                        ->send();
                }),

            Action::make('testing_started')
                ->label(fn (): string => 'Sesi Testing Dimulai: ' . Carbon::parse($this->getRecord()->start_date)->format('d M Y'))
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->disabled()
                ->visible(fn (): bool => filled($this->getRecord()->start_date)),
        ];
    }

    protected function activeTesterCount(): int
    {
        return App::findOrFail($this->recordId)
            ->testers()
            ->where('status', 'active')
            ->count();
    }

    protected function testerEmailsText(): string
    {
        return App::findOrFail($this->recordId)
            ->testers()
            ->where('status', 'active')
            ->with('tester')
            ->get()
            ->pluck('tester.email')
            ->filter()
            ->implode("\n");
    }

    protected function emailCopyBoxHtml(): string
    {
        $emails = e($this->testerEmailsText());

        return <<<HTML
            <div
                x-data="{
                    copied: false,
                    copy() {
                        navigator.clipboard.writeText(this.\$refs.emails.value);
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2000);
                    }
                }"
                style="display: flex; flex-direction: column; gap: 12px;"
            >
                <textarea
                    x-ref="emails"
                    readonly
                    style="width: 100%; min-height: 260px; border-radius: 12px; border: 1px solid #d1d5db; padding: 12px; font-family: monospace; font-size: 14px;"
                >{$emails}</textarea>

                <button
                    type="button"
                    x-on:click="copy()"
                    style="background: #2563eb; color: white; padding: 10px 14px; border-radius: 10px; font-weight: 600;"
                >
                    Copy Semua Email
                </button>

                <p x-show="copied" style="color: #16a34a; font-size: 14px; font-weight: 600;">
                    Email berhasil disalin.
                </p>
            </div>
        HTML;
    }

    protected function getViewData(): array
    {
        return [
            'record' => $this->getRecord(),
        ];
    }
}