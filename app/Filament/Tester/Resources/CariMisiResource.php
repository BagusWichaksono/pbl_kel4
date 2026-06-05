<?php

namespace App\Filament\Tester\Resources;

use App\Filament\Tester\Resources\CariMisiResource\Pages;
use App\Models\App;
use App\Models\ApplicationTester;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\HtmlString;

class CariMisiResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationLabel = 'Cari Misi';

    protected static ?string $pluralModelLabel = 'Cari Misi';

    protected static ?string $modelLabel = 'Misi';

    protected static ?string $navigationGroup = 'Misi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query): Builder {
                $query
                    ->where('payment_status', 'valid')
                    ->with(['developer'])
                    ->withCount('testers');

                if (Schema::hasColumn('applications', 'testing_status')) {
                    $query->where(function (Builder $query) {
                        $query
                            ->where('testing_status', 'open')
                            ->orWhereNull('testing_status');
                    });
                }

                return $query;
            })
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('mission_card')
                        ->label('')
                        ->getStateUsing(fn (App $record): string => $record->title ?? 'Aplikasi')
                        ->formatStateUsing(function ($state, App $record): HtmlString {
                            $title = e($record->title ?? 'Aplikasi');

                            $rawDeveloperName = $record->developer?->name ?? 'Developer';
                            $developerName = e($rawDeveloperName);

                            $developerInitial = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $rawDeveloperName), 0, 1));
                            $developerInitial = $developerInitial !== '' ? $developerInitial : 'D';

                            $developerAvatar = "
                                <span style='
                                    width:32px;
                                    height:32px;
                                    border-radius:999px;
                                    background:linear-gradient(135deg,var(--tesyuk-secondary),rgba(var(--tesyuk-primary-rgb), 0.24));
                                    color:var(--tesyuk-primary);
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    flex-shrink:0;
                                    font-size:.82rem;
                                    font-weight:900;
                                    border:1px solid rgba(var(--tesyuk-primary-rgb), 0.24);
                                '>
                                    {$developerInitial}
                                </span>
                            ";

                            $platform = e($record->platform ?? 'Platform');

                            $description = trim(strip_tags($record->description ?? ''));
                            $description = $description !== ''
                                ? e($description)
                                : 'Ikuti misi testing selama 14 hari, unggah screenshot harian, dan bantu developer menemukan bug.';

                            $filled = (int) ($record->testers_count ?? 0);
                            $maxTester = max((int) ($record->max_testers ?? 20), 1);
                            $percent = min(100, (int) round(($filled / $maxTester) * 100));
                            $remaining = max(0, $maxTester - $filled);

                            $isRegistered = ApplicationTester::query()
                                ->where('application_id', $record->id)
                                ->where('tester_id', Auth::id())
                                ->exists();

                            $isFull = $filled >= $maxTester;

                            $platformStyle = match (strtolower($platform)) {
                                'android' => 'background:var(--tesyuk-secondary);color:var(--tesyuk-primary);border:1px solid rgba(var(--tesyuk-primary-rgb), 0.24);',
                                'web' => 'background:#f5f3ff;color:#6d28d9;border:1px solid #ddd6fe;',
                                'ios' => 'background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;',
                                default => 'background:var(--tesyuk-secondary);color:var(--tesyuk-primary);border:1px solid rgba(var(--tesyuk-primary-rgb), 0.24);',
                            };

                            $initials = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $record->title ?? 'Aplikasi'), 0, 2));
                            $initials = $initials !== '' ? $initials : 'AP';

                            $imageColumns = [
                                'app_icon',
                                'logo',
                                'app_logo',
                                'thumbnail',
                            ];

                            $imagePath = null;

                            foreach ($imageColumns as $column) {
                                if (Schema::hasColumn('applications', $column) && filled($record->{$column})) {
                                    $imagePath = $record->{$column};
                                    break;
                                }
                            }

                            $imageUrl = null;

                            if ($imagePath) {
                                $imageUrl = str_starts_with($imagePath, 'http')
                                    ? $imagePath
                                    : asset('storage/' . $imagePath);
                            }

                            $appVisual = $imageUrl
                                ? "
                                    <img
                                        src='{$imageUrl}'
                                        alt='{$title}'
                                        style='
                                            width:96px;
                                            height:96px;
                                            border-radius:26px;
                                            object-fit:contain;
                                            padding:10px;
                                            border:1px solid rgba(255,255,255,.22);
                                            box-shadow:0 18px 30px -18px rgba(0,0,0,.55);
                                            background:#ffffff;
                                        '
                                    >
                                "
                                : "
                                    <div style='
                                        width:96px;
                                        height:96px;
                                        border-radius:26px;
                                        background:rgba(255,255,255,.14);
                                        border:1px solid rgba(255,255,255,.18);
                                        color:#ffffff;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        font-size:2rem;
                                        font-weight:800;
                                        letter-spacing:.04em;
                                        backdrop-filter:blur(10px);
                                        box-shadow:0 18px 30px -18px rgba(0,0,0,.45);
                                    '>
                                        {$initials}
                                    </div>
                                ";

                            return new HtmlString(<<<HTML
                                <div style="
                                    height:100%;
                                    display:flex;
                                    flex-direction:column;
                                    border-radius:24px;
                                    overflow:hidden;
                                    border:1px solid #e5e7eb;
                                    background:#ffffff;
                                    box-shadow:0 16px 40px -24px rgba(15,23,42,.28);
                                    transition:all .2s ease;
                                ">
                                    <div style="
                                        background:linear-gradient(135deg, var(--tesyuk-ink) 0%, var(--tesyuk-ink) 68%, var(--tesyuk-primary) 88%, var(--tesyuk-accent) 100%);
                                        min-height:170px;
                                        position:relative;
                                        padding:1rem;
                                        display:flex;
                                        flex-direction:column;
                                        justify-content:space-between;
                                    ">
                                        <div style="display:flex;align-items:flex-start;justify-content:flex-end;gap:.75rem;position:relative;z-index:2;">
                                            <span style="
                                                font-size:.76rem;
                                                font-weight:800;
                                                padding:.42rem .72rem;
                                                border-radius:999px;
                                                {$platformStyle}
                                            ">
                                                {$platform}
                                            </span>
                                        </div>

                                        <div style="margin-top:1rem;position:relative;z-index:2;">
                                            {$appVisual}
                                        </div>

                                        <div style="
                                            position:absolute;
                                            right:-18px;
                                            top:-18px;
                                            width:120px;
                                            height:120px;
                                            background:rgba(255,255,255,.08);
                                            border-radius:999px;
                                            filter:blur(8px);
                                        "></div>
                                    </div>

                                    <div style="padding:1.15rem 1.15rem .85rem;display:flex;flex-direction:column;flex:1;">
                                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:.75rem;">
                                            <div style="min-width:0;flex:1;">
                                                <div style="
                                                    font-size:1.05rem;
                                                    font-weight:800;
                                                    line-height:1.4;
                                                    color:#0f172a;
                                                    word-break:break-word;
                                                ">
                                                    {$title}
                                                </div>

                                                <div style="
                                                    display:flex;
                                                    align-items:center;
                                                    gap:.55rem;
                                                    margin-top:.55rem;
                                                    color:#64748b;
                                                    font-size:.84rem;
                                                ">
                                                    {$developerAvatar}

                                                    <div style="line-height:1.25;">
                                                        <div style="font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.04em;color:#94a3b8;">
                                                            Developer
                                                        </div>
                                                        <div style="font-size:.86rem;font-weight:700;color:#475569;">
                                                            {$developerName}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div style="
                                            margin-top:.95rem;
                                            color:#475569;
                                            font-size:.82rem;
                                            line-height:1.55;
                                            min-height:2.7rem;
                                            display:-webkit-box;
                                            -webkit-line-clamp:2;
                                            -webkit-box-orient:vertical;
                                            overflow:hidden;
                                        ">
                                            {$description}
                                        </div>

                                        <div style="
                                            margin-top:1rem;
                                            display:grid;
                                            grid-template-columns:repeat(3,1fr);
                                            gap:.6rem;
                                        ">
                                            <div style="border:1px solid #e2e8f0;background:#f8fafc;border-radius:16px;padding:.75rem;">
                                                <div style="font-size:.72rem;color:#64748b;font-weight:700;">Durasi</div>
                                                <div style="margin-top:.2rem;font-size:.9rem;font-weight:800;color:#0f172a;">14 Hari</div>
                                            </div>

                                            <div style="border:1px solid #e2e8f0;background:#f8fafc;border-radius:16px;padding:.75rem;">
                                                <div style="font-size:.72rem;color:#64748b;font-weight:700;">Reward</div>
                                                <div style="margin-top:.2rem;font-size:.9rem;font-weight:800;color:#0f172a;">10 Poin</div>
                                            </div>

                                            <div style="border:1px solid #e2e8f0;background:#f8fafc;border-radius:16px;padding:.75rem;">
                                                <div style="font-size:.72rem;color:#64748b;font-weight:700;">Sisa Slot</div>
                                                <div style="margin-top:.2rem;font-size:.9rem;font-weight:800;color:#0f172a;">{$remaining}</div>
                                            </div>
                                        </div>

                                        <div style="margin-top:1rem;">
                                            <div style="
                                                display:flex;
                                                align-items:center;
                                                justify-content:space-between;
                                                margin-bottom:.48rem;
                                            ">
                                                <span style="font-size:.78rem;font-weight:800;color:#475569;">Kuota Tester</span>
                                                <span style="font-size:.82rem;font-weight:800;color:var(--tesyuk-primary);">{$filled} / {$maxTester}</span>
                                            </div>

                                            <div style="height:10px;background:#e2e8f0;border-radius:999px;overflow:hidden;">
                                                <div style="
                                                    height:100%;
                                                    width:{$percent}%;
                                                    background:linear-gradient(90deg,var(--tesyuk-accent) 0%,var(--tesyuk-primary) 100%);
                                                    border-radius:999px;
                                                "></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            HTML);
                        })
                        ->html()
                        ->searchable(false),
                ]),
            ])
            ->filters([
                SelectFilter::make('platform')
                    ->label('Platform')
                    ->options([
                        'Android' => 'Android',
                        'Web' => 'Web',
                        'iOS' => 'iOS',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('daftarMisi')
                    ->label(function (App $record): string {
                        $isRegistered = ApplicationTester::query()
                            ->where('application_id', $record->id)
                            ->where('tester_id', Auth::id())
                            ->exists();

                        return $isRegistered ? 'Sudah Diambil' : 'Ambil Misi';
                    })
                    ->icon(function (App $record): string {
                        $isRegistered = ApplicationTester::query()
                            ->where('application_id', $record->id)
                            ->where('tester_id', Auth::id())
                            ->exists();

                        return $isRegistered ? 'heroicon-o-check-circle' : 'heroicon-o-plus-circle';
                    })
                    ->color(function (App $record): string {
                        $isRegistered = ApplicationTester::query()
                            ->where('application_id', $record->id)
                            ->where('tester_id', Auth::id())
                            ->exists();

                        return $isRegistered ? 'gray' : 'success';
                    })
                    ->disabled(function (App $record): bool {
                        return ApplicationTester::query()
                            ->where('application_id', $record->id)
                            ->where('tester_id', Auth::id())
                            ->exists();
                    })
                    ->button()
                    ->extraAttributes([
                        'style' => '
                            width:210px;
                            max-width:210px;
                            min-width:210px;
                            justify-content:center;
                            border-radius:999px;
                            min-height:42px;
                            font-weight:800;
                            margin:.65rem auto 0;
                            padding-left:1rem;
                            padding-right:1rem;
                            box-shadow:none;
                        ',
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Ambil Misi Pengujian')
                    ->modalDescription('Apakah kamu yakin ingin mengambil misi ini? Pastikan kamu siap menjalankan laporan harian sesuai ketentuan.')
                    ->modalSubmitActionLabel('Ya, Ambil Misi')
                    ->modalCancelActionLabel('Batal')
                    ->action(function (App $record): void {
                        $appId = $record->id;
                        $userId = Auth::id();

                        $isRegistered = ApplicationTester::query()
                            ->where('application_id', $appId)
                            ->where('tester_id', $userId)
                            ->exists();

                        if ($isRegistered) {
                            Notification::make()
                                ->title('Sudah Terdaftar')
                                ->body("Kamu sudah mengambil misi aplikasi \"{$record->title}\".")
                                ->warning()
                                ->send();

                            return;
                        }

                        $testerCount = ApplicationTester::query()
                            ->where('application_id', $appId)
                            ->count();

                        $maxTester = (int) ($record->max_testers ?? 20);

                        if ($testerCount >= $maxTester) {
                            Notification::make()
                                ->title('Slot Penuh')
                                ->body("Maaf, slot tester untuk aplikasi \"{$record->title}\" sudah penuh.")
                                ->danger()
                                ->send();

                            return;
                        }

                        if ($record->end_date && $record->end_date->isPast()) {
                            Notification::make()
                                ->title('Sesi Berakhir')
                                ->body("Sesi testing untuk aplikasi \"{$record->title}\" sudah berakhir.")
                                ->danger()
                                ->send();

                            return;
                        }

                        ApplicationTester::create([
                            'application_id' => $appId,
                            'tester_id' => $userId,
                            'status' => 'active',
                        ]);

                        Notification::make()
                            ->title('Misi Berhasil Diambil')
                            ->body("Kamu berhasil mengambil misi aplikasi \"{$record->title}\".")
                            ->success()
                            ->send();

                        if ($record->developer) {
                            Notification::make()
                                ->title('Tester Baru Bergabung')
                                ->body((Auth::user()?->name ?? 'Tester') . ' telah bergabung sebagai tester pada aplikasi ' . $record->title)
                                ->info()
                                ->sendToDatabase($record->developer);
                        }
                    }),
            ])
            ->emptyStateIcon('heroicon-o-magnifying-glass')
            ->emptyStateHeading('Belum ada misi tersedia')
            ->emptyStateDescription('Misi yang sudah divalidasi admin akan muncul di halaman ini.')
            ->paginated([6, 9, 18, 36]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCariMisis::route('/'),
        ];
    }
}
