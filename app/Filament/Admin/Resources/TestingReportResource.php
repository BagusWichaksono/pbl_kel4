<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TestingReportResource\Pages;
use App\Models\TestingReport;
use App\Models\PointHistory;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;

class TestingReportResource extends Resource
{
    protected static ?string $model = TestingReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Pembayaran Tester';
    protected static ?string $pluralModelLabel = 'Daftar Pembayaran Tester';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applicationTester.application.name')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('applicationTester.tester.name')
                    ->label('Nama Tester')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'disetujui' => 'success',
                        'pending' => 'warning',
                        'ditolak' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Dikirim')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat Detail'),

                Tables\Actions\Action::make('bayar_tester')
                    ->label('Bayar')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Poin ke Tester')
                    ->modalDescription('Masukkan jumlah poin yang akan diberikan sebagai honor atas pengujian ini.')
                    ->form([
                        TextInput::make('jumlah_poin')
                            ->label('Nominal Poin')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('Poin'),
                    ])
                    ->action(function (TestingReport $record, array $data) {
                        $jumlahPoin = $data['jumlah_poin'];

                        // Menarik data dari relasi ApplicationTester
                        $testerId = $record->applicationTester->tester_id;
                        $testerName = $record->applicationTester->tester->name;
                        $appName = $record->applicationTester->application->name;

                        // 1. Catat riwayat penambahan poin
                        PointHistory::create([
                            'tester_id' => $testerId,
                            'amount' => $jumlahPoin,
                            'type' => 'credit',
                            'description' => 'Honor pengujian aplikasi: ' . $appName,
                        ]);

                        // Panggil model TesterProfile berdasarkan user_id (testerId)
                        $testerProfile = \App\Models\TesterProfile::where('user_id', $testerId)->first();

                        if ($testerProfile) {
                            // Jika profilnya sudah ada, tambahkan poinnya ke kolom 'points'
                            $testerProfile->increment('points', $jumlahPoin);
                        } else {
                            // Jika tester belum punya profil (data baru), buatkan otomatis
                            \App\Models\TesterProfile::create([
                                'user_id' => $testerId,
                                'points' => $jumlahPoin,
                            ]);
                        }

                        // Ubah status laporan pengujian
                        $record->update(['status' => 'disetujui']);

                        // Notifikasi Berhasil
                        Notification::make()
                            ->title('Pembayaran Berhasil')
                            ->body("Berhasil mengirim {$jumlahPoin} poin kepada {$testerName}.")
                            ->success()
                            ->send();
                    })
                    // Hanya muncul jika laporan statusnya 'pending'
                    ->visible(fn (TestingReport $record) => $record->status === 'pending'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Laporan')
                    ->schema([
                        TextEntry::make('applicationTester.application.name')->label('Aplikasi yang Diuji'),
                        TextEntry::make('applicationTester.tester.name')->label('Penguji (Tester)'),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'disetujui' => 'success',
                                'pending' => 'warning',
                                'ditolak' => 'danger',
                                default => 'gray',
                            }),
                    ])->columns(3),

                Section::make('Bukti Pengujian')
                    ->schema([
                        ImageEntry::make('file_bukti')
                            ->label('Bukti Screenshot')
                            ->columnSpanFull()
                            ->height(300),

                        TextEntry::make('catatan')
                            ->label('Catatan/Ulasan Laporan')
                            ->columnSpanFull(),

                        TextEntry::make('alasan_penolakan')
                            ->label('Alasan Penolakan (Jika ada)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestingReports::route('/'),
            'view' => Pages\ViewTestingReport::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}
