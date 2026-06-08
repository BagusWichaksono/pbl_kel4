<?php

namespace App\Filament\Developer\Resources\TransactionResource\Pages;

use App\Filament\Developer\Resources\TransactionResource;
use App\Models\App;
use App\Models\RefundRequest;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected static string $view = 'filament.developer.pages.view-transaction';

    public function getTitle(): string
    {
        return 'Detail Transaksi';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Kembali')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(TransactionResource::getUrl('index')),
        ];
    }

    public function getPaymentProofUrl(): ?string
    {
        $path = $this->getPaymentProofPath();

        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    public function getPaymentProofPath(): ?string
    {
        /** @var App $record */
        $record = $this->record;
        $path = $record->payment_proof;

        return $this->normalizeFilePath($path);
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
        return $this->normalizeFilePath($this->getLatestRefundRequest()?->refund_proof);
    }

    public function getLatestRefundRequest(): ?RefundRequest
    {
        /** @var App $record */
        $record = $this->record;

        return $record->latestRefundRequest()->first();
    }

    public function paymentStatusLabel(?string $state): string
    {
        return match ($state) {
            'pending' => 'Menunggu',
            'valid', 'approved' => 'Valid',
            'invalid' => 'Tidak Sah',
            'refunded' => 'Refunded',
            default => '-',
        };
    }

    public function paymentStatusStyle(?string $state): string
    {
        return match ($state) {
            'pending' => 'background:#fffbeb;color:#92400e;border:1px solid #fde68a;',
            'valid', 'approved' => 'background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;',
            'invalid', 'refunded' => 'background:#fef2f2;color:#be123c;border:1px solid #fecdd3;',
            default => 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;',
        };
    }

    public function refundStatusLabel(?string $state): string
    {
        return match ($state) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => 'Belum Ada',
        };
    }

    public function refundStatusStyle(?string $state): string
    {
        return match ($state) {
            'pending' => 'background:#fffbeb;color:#92400e;border:1px solid #fde68a;',
            'approved' => 'background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;',
            'rejected' => 'background:#fef2f2;color:#be123c;border:1px solid #fecdd3;',
            default => 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;',
        };
    }

    private function normalizeFilePath(mixed $path): ?string
    {
        if (is_string($path) && str_starts_with(trim($path), '[')) {
            $decoded = json_decode($path, true);
            $path = is_array($decoded) ? ($decoded[0] ?? null) : $path;
        }

        if (is_array($path)) {
            $path = $path[0] ?? null;
        }

        return filled($path) ? (string) $path : null;
    }
}
