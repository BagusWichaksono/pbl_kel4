<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\MisiSayaResource\Pages;
use App\Models\ApplicationTester;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class MisiSayaResource extends Resource
{
    // Resource ini menggunakan model ApplicationTester (pivot tester–aplikasi)
    protected static ?string $model = ApplicationTester::class;

    protected static ?string $modelLabel       = 'Misi';
    protected static ?string $pluralModelLabel = 'Misi Saya';

    protected static ?string $navigationIcon  = 'heroicon-o-rocket-launch';
    protected static ?string $navigationLabel = 'Misi Saya';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // Hanya tampilkan misi milik tester yang sedang login
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                    ->where('tester_id', Auth::id())
                    ->with(['application', 'application.developer'])
            )
            ->columns([
                // ── Nama Aplikasi ────────────────────────────────────────
                Tables\Columns\TextColumn::make('application.title')
                    ->label('Nama Aplikasi')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),

                // ── Developer ────────────────────────────────────────────
                Tables\Columns\TextColumn::make('application.developer.name')
                    ->label('Developer')
                    ->icon('heroicon-o-user')
                    ->color('gray'),

                // ── Platform ─────────────────────────────────────────────
                Tables\Columns\TextColumn::make('application.platform')
                    ->label('Platform')
                    ->badge(),

                // ── Status pendaftaran tester ─────────────────────────────
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Kamu')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active'    => 'success',
                        'completed' => 'info',
                        'dropped'   => 'danger',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active'    => 'Aktif',
                        'completed' => 'Selesai',
                        'dropped'   => 'Keluar',
                        default     => $state,
                    }),

                // ── Jumlah tester saat ini ────────────────────────────────
                Tables\Columns\TextColumn::make('application.max_testers')
                    ->label('Jumlah Tester')
                    ->formatStateUsing(function ($state, ApplicationTester $record): string {
                        $count = $record->application->testers()->count();
                        $max   = $record->application->max_testers;
                        return "{$count} / {$max}";
                    })
                    ->badge()
                    ->color(fn ($state, ApplicationTester $record): string =>
                        $record->application->isFull() ? 'success' : 'warning'
                    ),

                // ── Link Aplikasi (kolom utama fitur baru) ────────────────
                Tables\Columns\TextColumn::make('application.app_url')
                    ->label('Link Aplikasi')
                    ->formatStateUsing(function ($state, ApplicationTester $record): HtmlString {
                        $app = $record->application;

                        if (empty($state)) {
                            if (! $app->isFull()) {
                                $pending = $app->max_testers - $app->testers()->count();
                                return new HtmlString(
                                    "<span class='text-yellow-500 text-sm'>⏳ Menunggu {$pending} tester lagi mendaftar</span>"
                                );
                            }
                            return new HtmlString(
                                "<span class='text-gray-400 text-sm'>⌛ Slot penuh, menunggu developer mengirim link...</span>"
                            );
                        }

                        // Link sudah tersedia — tampilkan sebagai tombol klik
                        $escaped = e($state);
                        return new HtmlString(
                            "<a href='{$escaped}' target='_blank' "
                            . "class='inline-flex items-center gap-1 px-3 py-1 rounded-lg "
                            . "bg-primary-500 text-white text-sm font-medium hover:bg-primary-600 transition'>"
                            . "🔗 Buka Aplikasi</a>"
                        );
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Terdaftar Sejak')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active'    => 'Aktif',
                        'completed' => 'Selesai',
                        'dropped'   => 'Keluar',
                    ]),
            ])
            ->actions([
                // Tombol detail — buka modal info lengkap aplikasi
                Tables\Actions\Action::make('lihat_detail')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (ApplicationTester $record) => $record->application->title)
                    ->modalContent(function (ApplicationTester $record): HtmlString {
                        $app        = $record->application;
                        $testerCount = $app->testers()->count();
                        $isFull     = $app->isFull();
                        $hasUrl     = $app->hasAppUrl();

                        $urlSection = '';
                        if ($hasUrl) {
                            $escaped    = e($app->app_url);
                            $urlSection = "
                                <div class='mt-4 p-3 bg-green-50 border border-green-200 rounded-lg'>
                                    <p class='text-sm font-semibold text-green-700 mb-1'>🔗 Link Aplikasi</p>
                                    <a href='{$escaped}' target='_blank'
                                        class='text-primary-600 hover:underline break-all text-sm'>
                                        {$escaped}
                                    </a>
                                </div>";
                        } elseif ($isFull) {
                            $urlSection = "
                                <div class='mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg'>
                                    <p class='text-sm text-yellow-700'>⌛ Slot sudah penuh. Menunggu developer mengirim link aplikasi...</p>
                                </div>";
                        } else {
                            $remaining  = $app->max_testers - $testerCount;
                            $urlSection = "
                                <div class='mt-4 p-3 bg-gray-50 border border-gray-200 rounded-lg'>
                                    <p class='text-sm text-gray-500'>⏳ Menunggu {$remaining} tester lagi mendaftar sebelum link bisa dikirim.</p>
                                </div>";
                        }

                        $desc = nl2br(e($app->description));
                        return new HtmlString("
                            <div class='space-y-3 text-sm'>
                                <div class='grid grid-cols-2 gap-2'>
                                    <div>
                                        <p class='text-gray-400 text-xs uppercase tracking-wide'>Developer</p>
                                        <p class='font-medium'>{$app->developer->name}</p>
                                    </div>
                                    <div>
                                        <p class='text-gray-400 text-xs uppercase tracking-wide'>Platform</p>
                                        <p class='font-medium'>{$app->platform}</p>
                                    </div>
                                    <div>
                                        <p class='text-gray-400 text-xs uppercase tracking-wide'>Tester</p>
                                        <p class='font-medium'>{$testerCount} / {$app->max_testers}</p>
                                    </div>
                                    <div>
                                        <p class='text-gray-400 text-xs uppercase tracking-wide'>Status Pengujian</p>
                                        <p class='font-medium capitalize'>{$app->testing_status}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class='text-gray-400 text-xs uppercase tracking-wide mb-1'>Deskripsi</p>
                                    <p class='text-gray-700 leading-relaxed'>{$desc}</p>
                                </div>
                                {$urlSection}
                            </div>
                        ");
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('Belum Ada Misi')
            ->emptyStateDescription('Kamu belum mendaftar ke aplikasi manapun. Pergi ke "Cari Misi" untuk mulai.')
            ->emptyStateIcon('heroicon-o-rocket-launch');
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

    // Tester hanya bisa lihat data miliknya sendiri
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('tester_id', Auth::id());
    }
}
