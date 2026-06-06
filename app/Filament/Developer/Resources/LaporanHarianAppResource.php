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
                'md' => 1,
                'xl' => 2,
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
                                            width:64px;
                                            height:64px;
                                            border-radius:18px;
                                            object-fit:contain;
                                            padding:7px;
                                            border:1px solid rgba(var(--tesyuk-accent-rgb),.16);
                                            box-shadow:0 14px 26px -22px rgba(0,0,0,.45);
                                            background:#ffffff;
                                        '
                                    >
                                "
                                : "
                                    <div style='
                                        width:64px;
                                        height:64px;
                                        border-radius:18px;
                                        background:var(--tesyuk-ink);
                                        border:1px solid rgba(var(--tesyuk-accent-rgb),.18);
                                        color:#ffffff;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        font-size:1.35rem;
                                        font-weight:800;
                                        letter-spacing:.04em;
                                        box-shadow:0 14px 26px -22px rgba(0,0,0,.45);
                                    '>
                                        {$initials}
                                    </div>
                                ";

                            $totalReports = 0;
                            $reportingTesters = 0;
                            $bugReports = 0;
                            $latestReportLabel = 'Belum ada laporan';
                            $reportCoverage = 0;

                            if (Schema::hasTable('daily_reports')) {
                                $query = DB::table('daily_reports');

                                if (Schema::hasColumn('daily_reports', 'app_id')) {
                                    $query->where('app_id', $record->id);
                                } elseif (Schema::hasColumn('daily_reports', 'application_id')) {
                                    $query->where('application_id', $record->id);
                                }

                                $totalReports = (clone $query)->count();
                                $reportingTesters = (clone $query)->distinct('tester_id')->count('tester_id');

                                if (Schema::hasColumn('daily_reports', 'bug_report')) {
                                    $bugReports = (clone $query)
                                        ->whereNotNull('bug_report')
                                        ->where('bug_report', '!=', '')
                                        ->count();
                                }

                                $latestReport = (clone $query)
                                    ->orderByDesc('report_date')
                                    ->orderByDesc('created_at')
                                    ->first();

                                if ($latestReport?->report_date) {
                                    $latestReportLabel = \Carbon\Carbon::parse($latestReport->report_date)->translatedFormat('d M Y');
                                }
                            }

                            if ($filled > 0) {
                                $reportCoverage = min(100, (int) round(($reportingTesters / $filled) * 100));
                            }

                            return new HtmlString(<<<HTML
                                <div style="
                                    height:100%;
                                    display:grid;
                                    grid-template-columns:7px minmax(0,1fr);
                                    border-radius:22px;
                                    overflow:hidden;
                                    border:1px solid rgba(var(--tesyuk-accent-rgb),.14);
                                    background:#ffffff;
                                    box-shadow:0 14px 36px -28px rgba(var(--tesyuk-ink-rgb),.42);
                                    transition:all .2s ease;
                                ">
                                    <div style="
                                        background:linear-gradient(180deg,var(--tesyuk-ink),var(--tesyuk-primary));
                                    "></div>

                                    <div style="padding:1.15rem 1.15rem 1rem;display:flex;flex-direction:column;gap:1rem;">
                                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
                                            <div style="display:flex;align-items:center;gap:.85rem;min-width:0;">
                                                {$appVisual}
                                                <div style="min-width:0;">
                                                    <div style="font-size:.72rem;font-weight:900;letter-spacing:.08em;text-transform:uppercase;color:var(--tesyuk-primary);">Pusat Laporan</div>
                                                    <div style="margin-top:.18rem;font-size:1.08rem;font-weight:850;line-height:1.32;color:var(--tesyuk-ink);word-break:break-word;">{$title}</div>
                                                </div>
                                            </div>

                                            <span style="flex-shrink:0;font-size:.72rem;font-weight:850;padding:.38rem .66rem;border-radius:999px;{$statusStyle}">
                                                {$statusLabel}
                                            </span>
                                        </div>

                                        <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                                            <span style="font-size:.74rem;font-weight:800;padding:.38rem .64rem;border-radius:999px;{$platformStyle}">{$platform}</span>
                                            <span style="font-size:.74rem;font-weight:800;padding:.38rem .64rem;border-radius:999px;background:#f8fafc;color:#475569;border:1px solid #e2e8f0;">Terakhir: {$latestReportLabel}</span>
                                        </div>

                                        <div style="color:#64748b;font-size:.82rem;line-height:1.55;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                            {$description}
                                        </div>

                                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:.62rem;">
                                            <div style="background:#fffafa;border:1px solid rgba(var(--tesyuk-accent-rgb),.15);border-radius:16px;padding:.78rem;">
                                                <div style="font-size:.68rem;color:#64748b;font-weight:800;text-transform:uppercase;letter-spacing:.04em;">Laporan</div>
                                                <div style="margin-top:.25rem;font-size:1.18rem;font-weight:900;color:var(--tesyuk-ink);">{$totalReports}</div>
                                            </div>

                                            <div style="background:#fffafa;border:1px solid rgba(var(--tesyuk-accent-rgb),.15);border-radius:16px;padding:.78rem;">
                                                <div style="font-size:.68rem;color:#64748b;font-weight:800;text-transform:uppercase;letter-spacing:.04em;">Tester Kirim</div>
                                                <div style="margin-top:.25rem;font-size:1.18rem;font-weight:900;color:var(--tesyuk-ink);">{$reportingTesters}</div>
                                            </div>

                                            <div style="background:#fffafa;border:1px solid rgba(var(--tesyuk-accent-rgb),.15);border-radius:16px;padding:.78rem;">
                                                <div style="font-size:.68rem;color:#64748b;font-weight:800;text-transform:uppercase;letter-spacing:.04em;">Bug</div>
                                                <div style="margin-top:.25rem;font-size:1.18rem;font-weight:900;color:var(--tesyuk-primary);">{$bugReports}</div>
                                            </div>
                                        </div>

                                        <div>
                                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.48rem;">
                                                <span style="font-size:.78rem;font-weight:850;color:#475569;">Cakupan laporan tester</span>
                                                <span style="font-size:.82rem;font-weight:850;color:var(--tesyuk-primary);">{$reportingTesters} / {$filled}</span>
                                            </div>

                                            <div style="height:8px;background:#f1f5f9;border-radius:999px;overflow:hidden;">
                                                <div style="height:100%;width:{$reportCoverage}%;background:linear-gradient(90deg,var(--tesyuk-ink) 0%,var(--tesyuk-primary) 100%);border-radius:999px;"></div>
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
