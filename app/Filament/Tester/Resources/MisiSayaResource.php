<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\MisiSayaResource\Pages;
use App\Models\ApplicationTester;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class MisiSayaResource extends Resource
{
    protected static ?string $model = ApplicationTester::class;

    protected static ?string $modelLabel = 'Misi Saya';
    protected static ?string $pluralModelLabel = 'Daftar Misi Saya';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Misi Saya';

    // ─── FILTER: HANYA TAMPILKAN MISI MILIK TESTER YANG LOGIN ───
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tester_id', Auth::id());
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('application.title')
                    ->label('Nama Aplikasi')
                    ->weight('bold')
                    ->searchable(),

                Tables\Columns\TextColumn::make('application.developer.name')
                    ->label('Developer')
                    ->color('gray'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status Misi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Diambil')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // ─── TOMBOL LIHAT INSTRUKSI (MISI HARIAN) ───
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Tugas')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->form([
                        Forms\Components\TextInput::make('app.title')
                            ->label('Aplikasi')
                            ->disabled(),
                        Forms\Components\Textarea::make('app.description') // Asumsi ada field description di tabel App
                            ->label('Instruksi / Misi Harian')
                            ->disabled()
                            ->rows(5),
                    ]),

                // ─── TOMBOL KIRIM LAPORAN / BUKTI ───
                Tables\Actions\Action::make('kirimLaporan')
                    ->label('Kirim Laporan Akhir')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->button()
                    // Kunci tombol (disabled) jika belum lewat 14 hari sejak misi diambil
                    ->disabled(fn (ApplicationTester $record) => now()->diffInDays($record->created_at) < 14)
                    // Tambahkan keterangan kenapa tombolnya tidak bisa diklik
                    ->tooltip(fn (ApplicationTester $record) => now()->diffInDays($record->created_at) < 14 
                        ? 'Laporan akhir hanya bisa dikirim setelah 14 hari masa testing.' 
                        : 'Klik untuk mengirim laporan testing')
                    // Tombol tetap disembunyikan kalau statusnya sudah completed
                    ->visible(fn (ApplicationTester $record) => $record->status === 'active')
                    ->form([
                        Forms\Components\FileUpload::make('proof_image')
                            ->label('Bukti Screenshot Testing')
                            ->image()
                            ->required()
                            ->directory('proofs')
                            ->maxSize(5120) // Maksimal 5MB
                            ->helperText('Unggah screenshot saat kamu mencoba aplikasi ini.'),
                            
                        Forms\Components\Textarea::make('feedback')
                            ->label('Feedback & Laporan Bug')
                            ->required()
                            ->rows(5)
                            ->placeholder('Jelaskan pengalamanmu menggunakan aplikasi ini. Apakah ada fitur yang error/bug?')
                            ->minLength(50),
                    ])
                    ->action(function (array $data, ApplicationTester $record) {
                        $record->update([
                            'proof_image' => $data['proof_image'],
                            'feedback' => $data['feedback'],
                            'status' => 'completed',
                        ]);

                        Notification::make()
                            ->title('Laporan Berhasil Dikirim!')
                            ->body('Terima kasih telah menyelesaikan misi. Laporanmu akan ditinjau.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMisiSayas::route('/'),
        ];
    }
}