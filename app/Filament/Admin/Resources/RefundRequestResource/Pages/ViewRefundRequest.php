<?php

namespace App\Filament\Admin\Resources\RefundRequestResource\Pages;

use App\Filament\Admin\Resources\RefundRequestResource;
use App\Models\RefundRequest;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRefundRequest extends ViewRecord
{
    protected static string $resource = RefundRequestResource::class;

    protected static string $view = 'filament.admin.pages.view-refund-request';

    public function getTitle(): string
    {
        return 'Detail Refund';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(RefundRequestResource::getUrl('index')),

            Actions\Action::make('process')
                ->label('Proses Refund')
                ->icon('heroicon-o-arrow-right-circle')
                ->color('warning')
                ->url(fn (): string => RefundRequestResource::getUrl('process', ['record' => $this->record]))
                ->visible(fn (): bool => $this->record->status === RefundRequest::STATUS_PENDING),
        ];
    }

    public function getRefundProofUrl(): ?string
    {
        $path = $this->getRefundProofPath();

        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    public function getRefundProofPath(): ?string
    {
        /** @var RefundRequest $record */
        $record = $this->record;
        $path = $record->refund_proof;

        if (is_string($path) && str_starts_with(trim($path), '[')) {
            $decoded = json_decode($path, true);
            $path = is_array($decoded) ? ($decoded[0] ?? null) : $path;
        }

        if (is_array($path)) {
            $path = $path[0] ?? null;
        }

        return filled($path) ? (string) $path : null;
    }

    public function statusLabel(?string $state): string
    {
        return match ($state) {
            RefundRequest::STATUS_PENDING => 'Menunggu',
            RefundRequest::STATUS_APPROVED => 'Disetujui',
            RefundRequest::STATUS_REJECTED => 'Ditolak',
            default => '-',
        };
    }

    public function statusStyle(?string $state): string
    {
        return match ($state) {
            RefundRequest::STATUS_PENDING => 'background:#fffbeb;color:#92400e;border:1px solid #fde68a;',
            RefundRequest::STATUS_APPROVED => 'background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;',
            RefundRequest::STATUS_REJECTED => 'background:#fef2f2;color:#be123c;border:1px solid #fecdd3;',
            default => 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;',
        };
    }
}
