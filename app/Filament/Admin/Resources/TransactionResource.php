<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransactionResource\Pages;
use App\Models\Transaction; // <-- SESUAIKAN DENGAN NAMA MODEL KAMU
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $modelLabel = 'Pembayaran Developer';

    protected static ?string $pluralModelLabel = 'Pembayaran Developer';
    
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationLabel = 'Pembayaran Developer';
    
    protected static ?string $navigationGroup = 'Keuangan';
    
    protected static ?int $navigationSort = 1;

    // ─── AKSES: Admin & Superadmin Boleh Masuk ────────────────────────
    public static function canViewAny(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user !== null && in_array($user->role, ['admin', 'super_admin']);
    }

    // Blokir fitur "Buat Baru" karena admin cuma memvalidasi, bukan bikin transaksi
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Kita kosongkan saja karena validasi akan dilakukan langsung dari tabel
                // tanpa perlu masuk ke halaman Edit
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Nama Developer (Asumsi relasi ke user bernama 'developer')
                Tables\Columns\TextColumn::make('developer.name')
                    ->label('Nama Developer')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Kolom Nama Aplikasi (Asumsi relasi ke aplikasi)
                Tables\Columns\TextColumn::make('application.title')
                    ->label('Aplikasi')
                    ->searchable(),

                // Nominal Pembayaran
                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR', locale: 'id') // Otomatis format Rp
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $num = preg_replace('/[^0-9]/', '', $search);
                        if ($num !== '') {
                            return $query->where('amount', 'like', "%{$num}%");
                        }
                        return $query->where('amount', 'like', "%{$search}%");
                    }),

                // Preview Bukti Transfer
                Tables\Columns\ImageColumn::make('payment_proof')
                    ->label('Bukti Transfer')
                    ->square()
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->defaultImageUrl(url('/images/no-image.png')), // Fallback jika kosong

                // Status Pembayaran dengan Badge Warna-Warni
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'valid' => 'success',
                        'invalid' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Validasi',
                        'valid' => 'Lunas',
                        'invalid' => 'Ditolak',
                        default => 'Unknown',
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        $search = strtolower($search);
                        $matched = [];
                        if (str_contains('menunggu', $search) || str_contains('pending', $search)) $matched[] = 'pending';
                        if (str_contains('lunas', $search) || str_contains('valid', $search)) $matched[] = 'valid';
                        if (str_contains('ditolak', $search) || str_contains('invalid', $search)) $matched[] = 'invalid';
                        
                        if (count($matched) > 0) {
                            return $query->whereIn('status', $matched);
                        }
                        return $query->where('status', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereRaw("DATE_FORMAT(created_at, '%d %e %M %b %Y %m') LIKE ?", ["%{$search}%"]);
                    }),
            ])
            ->defaultSort('created_at', 'desc') // Yang paling baru di atas
            ->filters([
                // Tambahkan filter bawaan
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Menunggu Validasi',
                        'valid' => 'Pembayaran Valid',
                        'invalid' => 'Tidak Sah',
                    ]),
            ])
            ->actions([
                // TOMBOL: LIHAT BUKTI FULL SCREEN
                Action::make('lihatBukti')
                    ->label('Cek Bukti')
                    ->icon('heroicon-m-eye')
                    ->color('info')
                    ->modalHeading('Bukti Transfer')
                    ->modalContent(fn ($record) => view('filament.admin.components.image-preview', ['image' => $record->payment_proof]))
                    ->modalSubmitAction(false) // Hilangkan tombol submit
                    ->modalCancelActionLabel('Tutup'),

                // TOMBOL: TERIMA PEMBAYARAN
                Action::make('terima')
                    ->label('Terima')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Validasi Pembayaran')
                    ->modalDescription('Apakah bukti transfer ini sudah valid dan dana sudah masuk ke rekening? Status aplikasi akan dilanjutkan.')
                    ->action(function ($record) {
                        // 1. Ubah status transaksi
                        $record->update(['status' => 'approved']);
                        
                        // Notify Developer
                        $developer = $record->developer ?? $record->application->developer ?? null;
                        if ($developer) {
                            \Filament\Notifications\Notification::make()
                                ->title('Pembayaran Diterima')
                                ->body('Pembayaran untuk aplikasi Anda telah divalidasi oleh Admin. Aplikasi Anda sekarang siap untuk diproses!')
                                ->success()
                                ->sendToDatabase($developer);
                        }
                    })
                    ->visible(fn ($record) => $record->status === 'pending'), // Hanya muncul jika masih pending

                // TOMBOL: TOLAK PEMBAYARAN
                Action::make('tolak')
                    ->label('Tolak')
                    ->icon('heroicon-m-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pembayaran')
                    ->modalDescription('Apakah Anda yakin ingin menolak pembayaran ini? Developer harus mengunggah ulang bukti transfer.')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);
                        
                        // Notify Developer
                        $developer = $record->developer ?? $record->application->developer ?? null;
                        if ($developer) {
                            \Filament\Notifications\Notification::make()
                                ->title('Pembayaran Ditolak')
                                ->body('Pembayaran untuk aplikasi Anda ditolak. Silakan periksa kembali bukti transfer Anda.')
                                ->danger()
                                ->sendToDatabase($developer);
                        }
                    })
                    ->visible(fn ($record) => $record->status === 'pending'),
            ])
            ->emptyStateHeading('Belum Ada Transaksi')
            ->emptyStateDescription('Tidak ada pembayaran dari developer yang perlu divalidasi saat ini.')
            ->emptyStateIcon('heroicon-o-banknotes');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            // Kita cuma butuh halaman List (Tabel) saja
            'index' => Pages\ListTransactions::route('/'),
        ];
    }
}