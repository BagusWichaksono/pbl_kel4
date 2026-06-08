<?php

namespace App\Filament\Admin\Resources\RefundRequestResource\Pages;

use App\Filament\Admin\Resources\RefundRequestResource;
use App\Models\RefundRequest;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class ProcessRefund extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = RefundRequestResource::class;

    protected static string $view = 'filament.admin.pages.process-refund';

    public int $recordId;

    public ?array $data = [];

    public function mount(int|string $record): void
    {
        $refundRequest = RefundRequest::with(['developer', 'application'])->findOrFail($record);

        $this->recordId = $refundRequest->id;

        if ($refundRequest->status !== RefundRequest::STATUS_PENDING) {
            Notification::make()
                ->title('Pengajuan refund sudah diproses')
                ->warning()
                ->send();

            $this->redirect(RefundRequestResource::getUrl('index'));

            return;
        }

        $this->form->fill([
            'decision' => RefundRequest::STATUS_APPROVED,
            'admin_note' => '',
            'refund_proof' => null,
        ]);
    }

    public function getTitle(): string
    {
        return 'Proses Refund - ' . ($this->getRecord()->application?->title ?? 'Aplikasi');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Keputusan Refund')
                    ->description('Pilih hasil peninjauan refund dan isi catatan untuk developer.')
                    ->schema([
                        Select::make('decision')
                            ->label('Keputusan')
                            ->required()
                            ->live()
                            ->native(false)
                            ->options([
                                RefundRequest::STATUS_APPROVED => 'Setujui dan kirim bukti refund',
                                RefundRequest::STATUS_REJECTED => 'Tolak refund',
                            ]),

                        Textarea::make('admin_note')
                            ->label(fn (Get $get): string => $get('decision') === RefundRequest::STATUS_REJECTED
                                ? 'Alasan Penolakan'
                                : 'Catatan / Alasan Refund')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull()
                            ->placeholder('Tulis catatan agar developer memahami keputusan admin.'),

                        FileUpload::make('refund_proof')
                            ->label('Bukti Refund')
                            ->disk('public')
                            ->directory('refund-proofs')
                            ->image()
                            ->imagePreviewHeight('220')
                            ->maxSize(2048)
                            ->required(fn (Get $get): bool => $get('decision') === RefundRequest::STATUS_APPROVED)
                            ->visible(fn (Get $get): bool => $get('decision') === RefundRequest::STATUS_APPROVED)
                            ->columnSpanFull()
                            ->helperText('Upload screenshot atau foto bukti transfer refund.'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $record = $this->getRecord();
        $data = $this->form->getState();

        if ($data['decision'] === RefundRequest::STATUS_APPROVED) {
            RefundRequestResource::approveRefund(
                $record,
                $data['admin_note'],
                $data['refund_proof'],
            );
        } else {
            RefundRequestResource::rejectRefund($record, $data['admin_note']);
        }

        $this->redirect(RefundRequestResource::getUrl('index'));
    }

    public function getCancelUrl(): string
    {
        return RefundRequestResource::getUrl('index');
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

    public function getRecord(): RefundRequest
    {
        return RefundRequest::with(['developer', 'application'])->findOrFail($this->recordId);
    }
}
