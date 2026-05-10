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
    protected static ?string $pluralModelLabel = 'Misi Saya';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Misi Saya';

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
                // ─── 1. TOMBOL CARA AKSES (INFO / TERSIER) ───
                Tables\Actions\Action::make('cekEmail')
                    ->label('Cara Akses')
                    ->icon('heroicon-o-envelope')
                    ->color('gray')
                    ->button()   // Jadikan bentuk tombol
                    ->outlined() // Jadikan transparan dengan garis luar
                    ->modalHeading('Prosedur Akses Aplikasi')
                    ->modalDescription('Karena kebijakan baru Google Play, link download tidak ditampilkan di sini. Developer akan mendaftarkan email kamu ke Google Play Console. Link undangan resmi akan otomatis dikirim oleh Google ke email kamu setelah kuota 20 tester terpenuhi. Silakan cek Inbox/Spam email kamu secara berkala.')
                    ->modalSubmitAction(false)
                    ->visible(fn (ApplicationTester $record) => $record->status === 'active'),

                // ─── 2. TOMBOL LIHAT TUGAS (SEKUNDER) ───
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Tugas')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->button()   // Jadikan bentuk tombol
                    ->outlined() // Jadikan transparan dengan garis luar
                    ->form([
                        Forms\Components\TextInput::make('nama_aplikasi_display')
                            ->label('Aplikasi')
                            ->formatStateUsing(fn ($record) => $record->application->app_name ?? $record->application->title)
                            ->disabled(),
                        Forms\Components\Textarea::make('deskripsi_display')
                            ->label('Instruksi / Misi Harian')
                            ->formatStateUsing(fn ($record) => $record->application->description)
                            ->disabled()
                            ->rows(5),
                    ]),

                // ─── 3. TOMBOL KIRIM LAPORAN (PRIMER / UTAMA) ───
                Tables\Actions\Action::make('kirimLaporan')
                    ->label('Kirim Laporan Akhir')
                    ->icon('heroicon-s-paper-airplane') // Pakai icon solid (s-) agar lebih tegas
                    ->color('primary')
                    ->button() // Tetap solid button (blok warna penuh)
                    ->disabled(fn (ApplicationTester $record) => now()->diffInDays($record->created_at) < 14)
                    ->tooltip(fn (ApplicationTester $record) => now()->diffInDays($record->created_at) < 14 
                        ? 'Laporan akhir hanya bisa dikirim setelah 14 hari masa testing.' 
                        : 'Klik untuk mengirim laporan testing')
                    ->visible(fn (ApplicationTester $record) => $record->status === 'active')
                    ->form([
                        Forms\Components\FileUpload::make('proof_image')
                            ->label('Bukti Screenshot Testing')
                            ->image()
                            ->required()
                            ->directory('proofs')
                            ->maxSize(5120),
                            
                        Forms\Components\Textarea::make('feedback')
                            ->label('Feedback & Laporan Bug')
                            ->required()
                            ->rows(5)
                            ->placeholder('Jelaskan pengalamanmu menggunakan aplikasi ini...')
                            ->minLength(50),
                    ])
                    ->action(function (array $data, ApplicationTester $record) {
                        $record->update([
                            'proof_image' => $data['proof_image'],
                            'feedback' => $data['feedback'],
                            'status' => 'completed',
                        ]);

                        \Filament\Notifications\Notification::make()
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