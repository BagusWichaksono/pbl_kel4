<?php

namespace App\Filament\Developer\Resources\TransactionResource\Pages;

use App\Filament\Developer\Resources\TransactionResource;
use App\Models\App;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;

class RequestRefund extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = TransactionResource::class;

    protected static string $view = 'filament.developer.pages.request-refund';

    public int $recordId;

    public ?array $data = [];

    public function mount(int|string $record): void
    {
        $app = App::with('latestRefundRequest')->findOrFail($record);

        abort_unless(
            (int) $app->developer_id === (int) Auth::id(),
            403,
            'Anda tidak memiliki akses ke halaman ini.'
        );

        $this->recordId = $app->id;

        if (! TransactionResource::canRequestRefund($app)) {
            Notification::make()
                ->title('Refund belum bisa diajukan')
                ->body(TransactionResource::refundRequestTooltip($app) ?? 'Silakan cek status refund aplikasi ini.')
                ->warning()
                ->send();

            $this->redirect(TransactionResource::getUrl('index'));

            return;
        }

        $this->form->fill([
            'reason' => '',
            'bank_name' => '',
            'account_name' => Auth::user()?->name ?? '',
            'account_number' => '',
        ]);
    }

    public function getTitle(): string
    {
        return 'Ajukan Refund - ' . $this->getRecord()->title;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Detail Pengajuan')
                    ->description('Jelaskan alasan refund agar admin bisa meninjau request dengan jelas.')
                    ->schema([
                        Textarea::make('reason')
                            ->label('Alasan Refund')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull()
                            ->placeholder('Contoh: Pengujian dibatalkan dan dana ingin dikembalikan.'),
                    ]),

                Section::make('Tujuan Transfer Refund')
                    ->description('Masukkan rekening atau e-wallet tempat dana refund akan diterima.')
                    ->schema([
                        TextInput::make('bank_name')
                            ->label('Bank / E-Wallet')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: BCA, Mandiri, DANA'),

                        TextInput::make('account_name')
                            ->label('Atas Nama')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('account_number')
                            ->label('Nomor Rekening / E-Wallet')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState();

        if (TransactionResource::createRefundRequest($record, $data)) {
            $this->redirect(TransactionResource::getUrl('index'));
        }
    }

    public function getCancelUrl(): string
    {
        return TransactionResource::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url($this->getCancelUrl()),
        ];
    }

    public function getRecord(): App
    {
        return App::with('latestRefundRequest')->findOrFail($this->recordId);
    }
}
