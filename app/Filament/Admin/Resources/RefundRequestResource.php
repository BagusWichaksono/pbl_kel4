<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\RefundRequestResource\Pages;
use App\Models\RefundRequest;
use App\Support\AppNotifier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RefundRequestResource extends Resource
{
    protected static ?string $model = RefundRequest::class;

    protected static ?string $modelLabel = 'Pengajuan Refund';

    protected static ?string $pluralModelLabel = 'Pengajuan Refund';

    protected static ?string $slug = 'pengajuan-refund';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static ?string $navigationLabel = 'Pengajuan Refund';

    protected static ?string $navigationGroup = 'Keuangan';

    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        return $user !== null && in_array($user->role, ['admin', 'super_admin']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $pendingCount = static::getModel()::query()
            ->where('status', RefundRequest::STATUS_PENDING)
            ->count();

        return $pendingCount > 0 ? (string) $pendingCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Pengajuan')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),

                Tables\Columns\TextColumn::make('developer.name')
                    ->label('Developer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('application.title')
                    ->label('Aplikasi')
                    ->searchable()
                    ->placeholder('Aplikasi dihapus'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR', locale: 'id')
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Tujuan Refund')
                    ->searchable()
                    ->description(fn (RefundRequest $record): string => trim(($record->account_name ?? '') . ' - ' . ($record->account_number ?? ''), ' -'))
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (?string $state): string => self::statusColor($state))
                    ->formatStateUsing(fn (?string $state): string => self::statusLabel($state))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];

                        if (str_contains('pending', $search) || str_contains('menunggu', $search)) {
                            $matched[] = RefundRequest::STATUS_PENDING;
                        }

                        if (str_contains('disetujui', $search) || str_contains('approve', $search) || str_contains('selesai', $search)) {
                            $matched[] = RefundRequest::STATUS_APPROVED;
                        }

                        if (str_contains('ditolak', $search) || str_contains('reject', $search)) {
                            $matched[] = RefundRequest::STATUS_REJECTED;
                        }

                        return filled($matched)
                            ? $query->whereIn('status', $matched)
                            : $query->where('status', 'like', "%{$search}%");
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        RefundRequest::STATUS_PENDING => 'Menunggu',
                        RefundRequest::STATUS_APPROVED => 'Disetujui',
                        RefundRequest::STATUS_REJECTED => 'Ditolak',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Detail')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Refund')
                    ->modalDescription('Pengajuan akan ditandai selesai. Pastikan proses pengembalian dana sudah dilakukan.')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Catatan Admin')
                            ->rows(3)
                            ->placeholder('Contoh: Refund sudah ditransfer via BCA.'),
                    ])
                    ->action(fn (RefundRequest $record, array $data) => self::approveRefund($record, $data['admin_note'] ?? null))
                    ->visible(fn (RefundRequest $record): bool => $record->status === RefundRequest::STATUS_PENDING),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Refund')
                    ->form([
                        Forms\Components\Textarea::make('admin_note')
                            ->label('Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(fn (RefundRequest $record, array $data) => self::rejectRefund($record, $data['admin_note']))
                    ->visible(fn (RefundRequest $record): bool => $record->status === RefundRequest::STATUS_PENDING),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Refund')
                    ->schema([
                        Infolists\Components\TextEntry::make('developer.name')
                            ->label('Developer'),

                        Infolists\Components\TextEntry::make('application.title')
                            ->label('Aplikasi')
                            ->placeholder('Aplikasi dihapus'),

                        Infolists\Components\TextEntry::make('amount')
                            ->label('Nominal')
                            ->money('IDR', locale: 'id'),

                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (?string $state): string => self::statusColor($state))
                            ->formatStateUsing(fn (?string $state): string => self::statusLabel($state)),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Diajukan Pada')
                            ->dateTime('d M Y, H:i'),

                        Infolists\Components\TextEntry::make('processed_at')
                            ->label('Diproses Pada')
                            ->dateTime('d M Y, H:i')
                            ->placeholder('-'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Alasan dan Tujuan Dana')
                    ->schema([
                        Infolists\Components\TextEntry::make('reason')
                            ->label('Alasan Refund')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('bank_name')
                            ->label('Bank / E-Wallet')
                            ->placeholder('-'),

                        Infolists\Components\TextEntry::make('account_name')
                            ->label('Atas Nama')
                            ->placeholder('-'),

                        Infolists\Components\TextEntry::make('account_number')
                            ->label('Nomor Rekening / E-Wallet')
                            ->copyable()
                            ->placeholder('-'),

                        Infolists\Components\TextEntry::make('admin_note')
                            ->label('Catatan Admin')
                            ->columnSpanFull()
                            ->placeholder('-'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['developer', 'application', 'processor']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRefundRequests::route('/'),
        ];
    }

    private static function approveRefund(RefundRequest $record, ?string $adminNote): void
    {
        DB::transaction(function () use ($record, $adminNote): void {
            $record->update([
                'status' => RefundRequest::STATUS_APPROVED,
                'admin_note' => $adminNote,
                'processed_by' => Auth::id(),
                'processed_at' => now(),
            ]);

            if ($record->application) {
                $record->application->update([
                    'payment_status' => 'refunded',
                    'testing_status' => 'rejected',
                ]);
            }
        });

        Notification::make()
            ->title('Refund disetujui')
            ->success()
            ->send();

        AppNotifier::database(
            $record->developer,
            'Refund disetujui',
            'Pengajuan refund untuk aplikasi ' . ($record->application?->title ?? '-') . ' sudah disetujui admin. Status aplikasi ditandai refunded.',
            'success',
        );
    }

    private static function rejectRefund(RefundRequest $record, string $adminNote): void
    {
        $record->update([
            'status' => RefundRequest::STATUS_REJECTED,
            'admin_note' => $adminNote,
            'processed_by' => Auth::id(),
            'processed_at' => now(),
        ]);

        Notification::make()
            ->title('Refund ditolak')
            ->danger()
            ->send();

        AppNotifier::database(
            $record->developer,
            'Refund ditolak',
            'Pengajuan refund untuk aplikasi ' . ($record->application?->title ?? '-') . ' ditolak. Alasan: ' . $adminNote,
            'danger',
        );
    }

    private static function statusColor(?string $state): string
    {
        return match ($state) {
            RefundRequest::STATUS_PENDING => 'warning',
            RefundRequest::STATUS_APPROVED => 'success',
            RefundRequest::STATUS_REJECTED => 'danger',
            default => 'gray',
        };
    }

    private static function statusLabel(?string $state): string
    {
        return match ($state) {
            RefundRequest::STATUS_PENDING => 'Menunggu',
            RefundRequest::STATUS_APPROVED => 'Disetujui',
            RefundRequest::STATUS_REJECTED => 'Ditolak',
            default => '-',
        };
    }
}
