<?php

namespace App\Filament\Tester\Resources;

use Illuminate\Support\Facades\Auth;
use App\Filament\Tester\Resources\TestingReportResource\Pages;
use App\Models\TestingReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TestingReportResource extends Resource
{
    protected static ?string $model = TestingReport::class;

    protected static ?string $modelLabel = 'Laporan Bug';
    protected static ?string $pluralModelLabel = 'Laporan Bug';
    protected static ?string $navigationIcon = 'heroicon-o-bug-ant';
    protected static ?string $navigationGroup = 'Menu';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kirim Laporan Bug')
                    ->schema([
                        Forms\Components\Select::make('application_tester_id')
                            ->label('Pilih Misi Aplikasi')
                            ->relationship('applicationTester', 'id', fn ($query) => $query->where('tester_id', Auth::id()))
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->application->app_name)
                            ->required()
                            ->searchable(),

                        Forms\Components\Textarea::make('catatan')
                            ->label('Detail Bug/Temuan')
                            ->required()
                            ->rows(5),

                        Forms\Components\FileUpload::make('file_bukti')
                            ->label('Bukti Screenshot')
                            ->image()
                            ->directory('testing-reports'),
                    ])
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Supaya tester cuma bisa lihat laporannya sendiri
        return parent::getEloquentQuery()->whereHas('applicationTester', function ($query) {
            $query->where('tester_id', Auth::id());
        });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestingReports::route('/'),
            'create' => Pages\CreateTestingReport::route('/create'),
            'edit' => Pages\EditTestingReport::route('/{record}/edit'),
        ];
    }

    // 1. MATIKAN FITUR TAMBAH MANUAL
    public static function canCreate(): bool
    {
        return false; 
    }

    // 2. SESUAIKAN TABEL & TOMBOL
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('app.title') // Ganti dengan relasi aplikasi kamu
                    ->label('Aplikasi yang Diuji')
                    ->weight('bold'),
                
                // Asumsi kamu punya kolom 'title' atau 'periode' untuk menyimpan teks "Minggu 1" / "Minggu 2"
                Tables\Columns\TextColumn::make('title') 
                    ->label('Periode Laporan')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',      // Belum diisi
                        'submitted' => 'warning', // Sudah diisi, nunggu admin
                        'approved' => 'success',  // Diterima
                        default => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => strtoupper($state)),
            ])
            ->actions([
                // KUNCI UTAMA: Kita pakai EditAction tapi diubah namanya jadi "Isi Laporan"
                Tables\Actions\EditAction::make()
                    ->label(fn ($record) => $record->status === 'pending' ? 'Isi Laporan' : 'Edit Laporan')
                    ->icon('heroicon-m-pencil-square')
                    ->button()
                    ->color(fn ($record) => $record->status === 'pending' ? 'primary' : 'gray'),
            ])
            ->emptyStateHeading('Belum Ada Misi Aktif')
            ->emptyStateDescription('Slot laporan Minggu 1 dan Minggu 2 akan otomatis muncul di sini setelah kamu disetujui menjalankan sebuah misi aplikasi.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->defaultSort('created_at', 'asc');
    }
}