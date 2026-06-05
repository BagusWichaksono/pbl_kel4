<?php

namespace App\Filament\Developer\Resources;

use App\Filament\Developer\Resources\LaporanHarianAppResource\Pages;
use App\Models\App;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\HtmlString;
use Filament\Tables\Filters\SelectFilter;

class LaporanHarianAppResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Laporan Harian Tester';

    protected static ?string $modelLabel = 'Laporan Harian';

    protected static ?string $pluralModelLabel = 'Laporan Harian Tester';

    protected static ?string $navigationGroup = 'Testing';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('developer_id', Auth::id())
            ->whereIn('testing_status', ['in_progress', 'completed'])
            ->withCount('testers');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('report_card')
                        ->label('')
                        ->getStateUsing(fn (App $record): string => $record->title ?? 'Aplikasi')
                        ->formatStateUsing(function ($state, App $record): HtmlString {
                            $title = e($record->title ?? 'Aplikasi');
                            $platform = e($record->platform ?? 'Platform');

                            $description = trim(strip_tags($record->description ?? ''));
                            $description = $description !== ''
                                ? e($description)
                                : 'Pilih aplikasi ini untuk melihat detail laporan harian dari para tester.';

                            $filled = (int) ($record->testers_count ?? 0);
                            $maxTester = max((int) ($record->max_testers ?? 20), 1);
                            $percent = min(100, (int) round(($filled / $maxTester) * 100));

                            $testingStatus = $record->testing_status ?? 'in_progress';

                            $statusLabel = match ($testingStatus) {
                                'completed' => 'Testing Selesai',
                                'in_progress' => 'Testing Berjalan',
                                default => 'Aktif',
                            };

                            $statusStyle = match ($testingStatus) {
                                'completed' => 'background:#ecfdf5;color:#047857;border:1px solid #bbf7d0;',
                                'in_progress' => 'background:var(--tesyuk-secondary);color:var(--tesyuk-primary);border:1px solid rgba(var(--tesyuk-primary-rgb), 0.24);',
                                default => 'background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;',
                            };

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

                            $totalReports = 0;

                            if (Schema::hasTable('daily_reports')) {
                                $query = DB::table('daily_reports');

                                if (Schema::hasColumn('daily_reports', 'app_id')) {
                                    $query->where('app_id', $record->id);
                                    $totalReports = $query->count();
                                } elseif (Schema::hasColumn('daily_reports', 'application_id')) {
                                    $query->where('application_id', $record->id);
                                    $totalReports = $query->count();
                                }
                            }

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

                                    <div style="padding:1.15rem 1.15rem 1rem;display:flex;flex-direction:column;flex:1;">
                                        <div style="
                                            display:flex;
                                            align-items:center;
                                            justify-content:space-between;
                                            gap:.75rem;
                                        ">
                                            <div style="
                                                font-size:1.05rem;
                                                font-weight:800;
                                                line-height:1.4;
                                                color:#0f172a;
                                                word-break:break-word;
                                            ">
                                                {$title}
                                            </div>

                                            <span style="
                                                flex-shrink:0;
                                                font-size:.72rem;
                                                font-weight:800;
                                                padding:.36rem .65rem;
                                                border-radius:999px;
                                                {$statusStyle}
                                            ">
                                                {$statusLabel}
                                            </span>
                                        </div>

                                        <div style="
                                            margin-top:.85rem;
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
                                                <div style="font-size:.72rem;color:#64748b;font-weight:700;">Tester</div>
                                                <div style="margin-top:.2rem;font-size:.9rem;font-weight:800;color:#0f172a;">{$filled}</div>
                                            </div>

                                            <div style="border:1px solid #e2e8f0;background:#f8fafc;border-radius:16px;padding:.75rem;">
                                                <div style="font-size:.72rem;color:#64748b;font-weight:700;">Target</div>
                                                <div style="margin-top:.2rem;font-size:.9rem;font-weight:800;color:#0f172a;">{$maxTester}</div>
                                            </div>

                                            <div style="border:1px solid #e2e8f0;background:#f8fafc;border-radius:16px;padding:.75rem;">
                                                <div style="font-size:.72rem;color:#64748b;font-weight:700;">Laporan</div>
                                                <div style="margin-top:.2rem;font-size:.9rem;font-weight:800;color:#0f172a;">{$totalReports}</div>
                                            </div>
                                        </div>

                                        <div style="margin-top:1rem;">
                                            <div style="
                                                display:flex;
                                                align-items:center;
                                                justify-content:space-between;
                                                margin-bottom:.48rem;
                                            ">
                                                <span style="font-size:.78rem;font-weight:800;color:#475569;">Progress Tester</span>
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

                SelectFilter::make('testing_status')
                    ->label('Status Testing')
                    ->options([
                        'in_progress' => 'Testing Berjalan',
                        'completed' => 'Testing Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('lihat_laporan')
                    ->label('Lihat Laporan')
                    ->icon('heroicon-o-document-magnifying-glass')
                    ->color('primary')
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
                    ->url(fn (App $record) => DailyReportResource::getUrl('index', [
                        'tableFilters' => [
                            'app_id' => [
                                'value' => $record->id,
                            ],
                        ],
                    ])),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('Belum Ada Laporan Harian')
            ->emptyStateDescription('Aplikasi yang sedang atau sudah testing akan muncul di halaman ini.')
            ->paginated([6, 9, 18, 36]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanHarianApps::route('/'),
        ];
    }
}