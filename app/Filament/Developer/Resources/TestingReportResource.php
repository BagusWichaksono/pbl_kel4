<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\TestingReportResource\Pages;
use App\Models\TestingReport;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TestingReportResource extends Resource
{
    protected static ?string $model = TestingReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Hasil Pengujian';
    protected static ?string $pluralModelLabel = 'Daftar Hasil Pengujian';
    

    // ─── DEVELOPER CUMA BISA LIHAT APLIKASINYA SENDIRI ───
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('applicationTester.application', function (Builder $query) {
            $query->where('developer_id', Auth::id());
        });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('applicationTester.application.name')
                    ->label('Nama Aplikasi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('applicationTester.user.name')
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
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                // Bisa ditambah filter status nanti kalau butuh
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat Detail'),
            ])
            ->bulkActions([
                // Sebaiknya Developer jangan bisa hapus (Delete) laporan tester. 
                // Biar jadi arsip yang transparan. Kalau setuju, hapus bagian ini.
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
                        TextEntry::make('applicationTester.app.name')->label('Aplikasi yang Diuji'),
                        TextEntry::make('applicationTester.user.name')->label('Penguji (Tester)'),
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

    // Menonaktifkan fitur Buat dan Edit (Murni Read-Only)
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }
}