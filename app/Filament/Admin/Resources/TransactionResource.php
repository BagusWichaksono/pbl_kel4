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

    protected static ?string $modelLabel = 'Validasi Pembayaran';
    protected static ?string $pluralModelLabel = 'Validasi Pembayaran';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Pembayaran Developer';
    protected static ?string $navigationGroup = 'Validasi & Keuangan';
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
                Tables\Columns\TextColumn::make('application.name')
                    ->label('Aplikasi')
                    ->searchable(),

                // Nominal Pembayaran
                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR', locale: 'id') // Otomatis format Rp
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

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
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Validasi',
                        'approved' => 'Lunas',
                        'rejected' => 'Ditolak',
                        default => 'Unknown',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Pengajuan')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc') // Yang paling baru di atas
            ->filters([
                // Filter cepat untuk mencari yang pending saja
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Menunggu Validasi',
                        'approved' => 'Lunas',
                        'rejected' => 'Ditolak',
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
                        
                        // 2. TODO: Kamu bisa tambahkan logika update status aplikasi di sini
                        // $record->application->update(['status' => 'payment_verified']);
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

    // Tambahkan ini di dalam class TransactionResource (di bawah $navigationSort)
    public static function shouldRegisterNavigation(): bool
    {
        // Menyembunyikan menu ini dari Sidebar Admin
        return false; 
    }
}