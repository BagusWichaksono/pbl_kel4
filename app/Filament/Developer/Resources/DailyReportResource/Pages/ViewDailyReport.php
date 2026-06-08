<?php

namespace App\Filament\Developer\Resources\DailyReportResource\Pages;

use App\Filament\Developer\Resources\DailyReportResource;
use App\Models\DailyReport;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewDailyReport extends ViewRecord
{
    protected static string $resource = DailyReportResource::class;

    protected static string $view = 'filament.developer.pages.view-daily-report';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Laporan Harian')
                ->modalDescription('Laporan harian ini akan ditandai valid.')
                ->visible(fn (): bool => ($this->record->status ?? DailyReport::STATUS_PENDING) === DailyReport::STATUS_PENDING)
                ->action(fn () => DailyReportResource::approveReport($this->record)),

            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reject Laporan Harian')
                ->modalDescription('Berikan alasan agar tester dapat memperbaiki dan mengirim ulang laporan.')
                ->form([
                    Textarea::make('rejection_reason')
                        ->label('Alasan Reject')
                        ->required()
                        ->rows(3),
                ])
                ->visible(fn (): bool => ($this->record->status ?? DailyReport::STATUS_PENDING) === DailyReport::STATUS_PENDING)
                ->action(fn (array $data) => DailyReportResource::rejectReport($this->record, $data['rejection_reason'])),
        ];
    }
}
