<?php

namespace App\Filament\Tester\Pages;

use App\Models\ApplicationTester;
use App\Models\TestingReport;
use App\Models\Review;
use App\Models\TesterProfile;
use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MisiAktif extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static string $view = 'filament.tester.pages.misi-aktif';

    protected static ?string $title = 'Misi Aktif Saya';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Mengambil data misi (ApplicationTester) milik tester yang sedang login
                ApplicationTester::query()->where('tester_id', Auth::id())
            )
            ->columns([
                TextColumn::make('application.name')
                    ->label('Nama Aplikasi')
                    ->searchable(),
                    
                TextColumn::make('status')
                    ->label('Status Misi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'accepted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->actions([
                // ACTION 1: UNGGAH BUKTI
                Action::make('unggah_bukti')
                    ->label('Unggah Bukti')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->modalHeading('Kirim Bukti Pengujian')
                    ->modalDescription('Unggah screenshot atau video, serta jelaskan temuan bug atau feedback kamu.')
                    ->form([
                        FileUpload::make('file_bukti')
                            ->label('File Bukti (Image/Video)')
                            ->directory('bukti-pengujian')
                            ->acceptedFileTypes(['image/*', 'video/mp4'])
                            ->maxSize(10240) // 10MB
                            ->required(),
                            
                        Textarea::make('catatan')
                            ->label('Catatan / Penjelasan Bug')
                            ->placeholder('Jelaskan langkah-langkah menemukan bug atau kesan penggunaan aplikasi...')
                            ->rows(4)
                            ->required(),
                    ])
                    ->action(function (array $data, ApplicationTester $record) {
                        TestingReport::create([
                            'application_tester_id' => $record->id,
                            'file_bukti' => $data['file_bukti'],
                            'catatan' => $data['catatan'],
                            'status' => 'pending',
                        ]);

                        Notification::make()
                            ->title('Bukti Pengujian Berhasil Dikirim!')
                            ->body('Silakan tunggu verifikasi dari Developer.')
                            ->success()
                            ->send();
                    })
                    // Sembunyikan tombol jika tester sudah pernah mengirim bukti untuk misi ini
                    ->visible(fn (ApplicationTester $record) => !TestingReport::where('application_tester_id', $record->id)->exists()),

                // ACTION 2: KIRIM ULASAN
                Action::make('kirim_ulasan')
                    ->label('Kirim Ulasan')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->modalHeading('Berikan Ulasan Aplikasi')
                    ->modalDescription('Bagaimana pengalamanmu menguji aplikasi ini secara keseluruhan?')
                    ->form([
                        Select::make('rating')
                            ->label('Rating Bintang')
                            ->options([
                                5 => '⭐⭐⭐⭐⭐ (5/5) - Sangat Baik',
                                4 => '⭐⭐⭐⭐ (4/5) - Baik',
                                3 => '⭐⭐⭐ (3/5) - Cukup',
                                2 => '⭐⭐ (2/5) - Buruk',
                                1 => '⭐ (1/5) - Sangat Buruk',
                            ])
                            ->required(),
                            
                        Textarea::make('komentar')
                            ->label('Komentar & Saran')
                            ->placeholder('Tuliskan kesan, pesan, atau saran untuk developer...')
                            ->rows(3)
                            ->required(),
                    ])
                    ->action(function (array $data, ApplicationTester $record) {
                        // Cari ID TesterProfile dari User yang sedang login
                        $testerProfile = TesterProfile::where('user_id', Auth::id())->first();

                        if (!$testerProfile) {
                            Notification::make()
                                ->title('Gagal!')
                                ->body('Profil Tester tidak ditemukan.')
                                ->danger()
                                ->send();
                            return;
                        }

                        Review::create([
                            'app_id' => $record->application_id, // Diambil dari relasi misi
                            'tester_profile_id' => $testerProfile->id,
                            'rating' => $data['rating'],
                            'komentar' => $data['komentar'],
                        ]);

                        Notification::make()
                            ->title('Ulasan Berhasil Dikirim!')
                            ->success()
                            ->send();
                    })
                    // LOGIKA WAKTU +7 HARI
                    ->visible(function (ApplicationTester $record) {
                        // 1. Cek apakah waktu saat ini sudah melewati (waktu misi dibuat + 7 hari)
                        $sudah7Hari = Carbon::parse($record->created_at)->addDays(7)->isPast();

                        // 2. Pastikan tombol hilang jika tester sudah pernah mengirim ulasan
                        $testerProfile = TesterProfile::where('user_id', Auth::id())->first();
                        $sudahDiulas = false;
                        
                        if ($testerProfile) {
                            $sudahDiulas = Review::where('app_id', $record->application_id)
                                                 ->where('tester_profile_id', $testerProfile->id)
                                                 ->exists();
                        }

                        // Tombol hanya muncul JIKA sudah 7 hari DAN belum diulas
                        return $sudah7Hari && !$sudahDiulas;
                    }),
            ])
            ->emptyStateHeading('Belum ada misi aktif')
            ->emptyStateDescription('Silakan cari aplikasi yang membutuhkan tester di menu Cari Misi.');
    }
}