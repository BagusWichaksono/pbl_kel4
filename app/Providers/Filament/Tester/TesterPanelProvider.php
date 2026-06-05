<?php

namespace App\Providers\Filament\Tester;

use App\Support\AppPalette;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class TesterPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $chartWidgets = array_values(array_filter([
            \App\Filament\Tester\Widgets\TesterPointsChart::class,
            \App\Filament\Tester\Widgets\TesterPointsOutChart::class,
            \App\Filament\Tester\Widgets\TesterMissionsChart::class,
        ], fn (string $widget): bool => class_exists($widget)));

        return $panel
            ->spa()
            ->id('tester')
            ->path('tester')
            ->brandName('TesYuk!')
            ->brandLogo(new HtmlString(AppPalette::brandLogoHtml(asset(AppPalette::LOGO_ASSET))))
            ->brandLogoHeight('3.25rem')
            ->profile(\App\Filament\Tester\Pages\CustomEditProfile::class)
            ->authGuard('web')
            ->darkMode(false)
            ->colors(AppPalette::filamentColors())
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('Misi')->collapsible(false),
                NavigationGroup::make('Poin')->collapsible(false),
                NavigationGroup::make('Bantuan')->collapsible(false),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => AppPalette::cssVariablesStyle() . "
                <style>
                    html {
                        color-scheme: light;
                    }

                    body,
                    .fi-layout {
                        background:
                            radial-gradient(circle at top left, rgba(var(--tesyuk-accent-rgb), 0.10), transparent 320px),
                            linear-gradient(180deg, #fff7f7 0%, #f8fafc 46%, #ffffff 100%) !important;
                    }

                    .fi-main {
                        background: transparent !important;
                    }

                    .fi-topbar {
                        background: transparent !important;
                        border-bottom: none !important;
                        box-shadow: none !important;
                    }

                    .fi-sidebar {
                        background:
                            radial-gradient(circle at top left, rgba(255, 255, 255, 0.16), transparent 210px),
                            linear-gradient(180deg, var(--tesyuk-ink) 0%, var(--tesyuk-primary) 62%, #5f1010 100%) !important;
                        border-right: 0 !important;
                        box-shadow: 18px 0 45px -35px rgba(var(--tesyuk-ink-rgb), 0.9) !important;
                    }

                    .fi-sidebar-header {
                        margin-bottom: 1rem !important;
                        padding-top: 50px !important;
                        padding-bottom: 50px !important;
                        border-bottom: 1px solid rgba(238, 238, 238, 0.08) !important;
                    }

                    .fi-sidebar-header a,
                    .fi-sidebar-header .fi-logo,
                    .fi-sidebar-header .fi-logo-link {
                        gap: 1.25rem !important;
                    }

                    .fi-sidebar-header img {
                        margin-right: 0.5rem !important;
                    }

                    .fi-sidebar-header .fi-logo-text,
                    .fi-sidebar-header .fi-brand-name {
                        margin-left: 0.5rem !important;
                    }

                    .fi-sidebar-nav {
                        padding-top: 0.75rem !important;
                    }

                    .fi-logo,
                    .tesyuk-brand-logo {
                        width: 100% !important;
                    }

                    .tesyuk-brand-logo {
                        display: flex;
                        align-items: center;
                        gap: 0.75rem;
                        color: #ffffff;
                    }

                    .tesyuk-brand-logo-image {
                        width: 48px;
                        height: 48px;
                        border-radius: 18px;
                        object-fit: cover;
                        background: rgba(255, 255, 255, 0.92);
                        padding: 4px;
                        box-shadow: 0 14px 28px -18px rgba(0, 0, 0, 0.7);
                    }

                    .tesyuk-brand-logo-text {
                        font-size: 1.25rem;
                        font-weight: 900;
                        letter-spacing: -0.02em;
                        color: #ffffff;
                    }

                    .fi-sidebar-nav {
                        gap: 1rem !important;
                        padding: 1.05rem 1rem 0.75rem !important;
                    }

                    .fi-sidebar-nav-groups {
                        gap: 0.85rem !important;
                    }

                    .fi-sidebar-group {
                        border-radius: 18px;
                    }

                    .fi-sidebar-group-label {
                        padding: 0 0.72rem !important;
                        color: rgba(255, 255, 255, 0.52) !important;
                        font-size: 0.68rem !important;
                        font-weight: 850 !important;
                        letter-spacing: 0.08em !important;
                        text-transform: uppercase !important;
                    }

                    .fi-sidebar-group-items {
                        gap: 0.28rem !important;
                    }

                    .fi-sidebar-item-button {
                        border-radius: 16px !important;
                        margin: 0 !important;
                        padding: 0.68rem 0.85rem !important;
                        border: 1px solid transparent !important;
                        box-shadow: none !important;
                        transition: background-color 0.18s ease, border-color 0.18s ease, transform 0.18s ease !important;
                    }

                    .fi-sidebar-item-button:hover {
                        background: rgba(255, 255, 255, 0.10) !important;
                        border-color: rgba(255, 255, 255, 0.12) !important;
                        transform: translateX(2px);
                    }

                    .fi-sidebar-item-button .fi-sidebar-item-label {
                        color: rgba(255, 255, 255, 0.82) !important;
                        font-weight: 700 !important;
                    }

                    .fi-sidebar-item-button .fi-sidebar-item-icon {
                        color: rgba(255, 255, 255, 0.64) !important;
                    }

                    .fi-sidebar-item-active .fi-sidebar-item-button {
                        background: linear-gradient(135deg, var(--tesyuk-secondary), #ffffff) !important;
                        border-color: rgba(255, 255, 255, 0.70) !important;
                        box-shadow: 0 12px 26px -18px rgba(0, 0, 0, 0.75) !important;
                    }

                    .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label {
                        color: var(--tesyuk-primary) !important;
                        font-weight: 850 !important;
                    }

                    .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
                        color: var(--tesyuk-accent) !important;
                    }

                    .fi-sidebar-group-collapse-button {
                        display: none !important;
                    }

                    .fi-ta-ctn,
                    .fi-modal-window,
                    .fi-section {
                        border-radius: 24px !important;
                        border: 1px solid rgba(var(--tesyuk-accent-rgb), 0.12) !important;
                        box-shadow: 0 4px 20px -5px rgba(var(--tesyuk-accent-rgb), 0.05) !important;
                        background-color: white !important;
                    }

                    .custom-card-stats {
                        background: white !important;
                        border: 1px solid rgba(var(--tesyuk-accent-rgb), 0.12) !important;
                        border-radius: 20px !important;
                        padding: 1.5rem !important;
                        display: flex !important;
                        align-items: center !important;
                        gap: 1rem !important;
                        box-shadow: 0 4px 20px -2px rgba(var(--tesyuk-accent-rgb), 0.08) !important;
                        transition: transform 0.3s ease, box-shadow 0.3s ease !important;
                    }

                    .custom-card-stats:hover {
                        transform: translateY(-3px) !important;
                        box-shadow: 0 10px 25px -5px rgba(var(--tesyuk-accent-rgb), 0.12) !important;
                    }

                    .stat-label {
                        color: #6b7280;
                        font-size: 0.75rem;
                        font-weight: 700;
                        text-transform: uppercase;
                        margin: 0;
                    }

                    .stat-value {
                        color: var(--tesyuk-ink);
                        font-size: 1.25rem;
                        font-weight: 800;
                        margin: 0;
                    }

                    .icon-bg {
                        background: var(--tesyuk-secondary);
                        padding: 0.75rem;
                        border-radius: 12px;
                        color: var(--tesyuk-accent);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .fi-topbar .fi-user-menu {
                        display: none !important;
                    }

                    .fi-dropdown-panel button,
                    .fi-dropdown-panel a {
                        background-color: unset;
                    }

                    .fi-dropdown-list-item:hover {
                        background-color: var(--tesyuk-secondary) !important;
                    }

                    .fi-dropdown-list-item-label {
                        font-weight: 600 !important;
                    }

                    .tesyuk-sidebar-profile {
                        margin: 0 1rem 1rem;
                        border-radius: 22px;
                        border: 1px solid rgba(255, 255, 255, 0.14);
                        background: rgba(255, 255, 255, 0.10);
                        padding: 0.85rem;
                        color: #ffffff;
                        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
                    }

                    .tesyuk-sidebar-profile-link {
                        display: grid;
                        grid-template-columns: 46px minmax(0, 1fr);
                        gap: 0.72rem;
                        align-items: center;
                        color: inherit;
                        text-decoration: none;
                    }

                    .tesyuk-sidebar-avatar,
                    .tesyuk-sidebar-avatar-fallback {
                        width: 46px;
                        height: 46px;
                        border-radius: 999px;
                        border: 2px solid rgba(255, 255, 255, 0.70);
                        background: var(--tesyuk-secondary);
                        color: var(--tesyuk-primary);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 0.9rem;
                        font-weight: 900;
                        object-fit: cover;
                    }

                    .tesyuk-sidebar-profile-name {
                        color: #ffffff;
                        font-size: 0.92rem;
                        font-weight: 850;
                        line-height: 1.25;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .tesyuk-sidebar-profile-meta {
                        margin-top: 0.12rem;
                        color: rgba(255, 255, 255, 0.62);
                        font-size: 0.72rem;
                        font-weight: 650;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        white-space: nowrap;
                    }

                    .tesyuk-sidebar-profile-actions {
                        display: grid;
                        grid-template-columns: 1fr 42px;
                        gap: 0.45rem;
                        margin-top: 0.75rem;
                    }

                    .tesyuk-sidebar-profile-button {
                        border-radius: 999px;
                        background: rgba(255, 255, 255, 0.14);
                        color: #ffffff;
                        border: 1px solid rgba(255, 255, 255, 0.16);
                        padding: 0.5rem 0.7rem;
                        font-size: 0.74rem;
                        font-weight: 800;
                        text-align: center;
                        text-decoration: none;
                    }

                    .tesyuk-sidebar-logout {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        width: 100%;
                        border-radius: 999px;
                        background: var(--tesyuk-secondary);
                        color: var(--tesyuk-primary);
                        border: 0;
                        padding: 0.5rem 0.65rem;
                        font-weight: 900;
                        cursor: pointer;
                    }
                </style>
                "
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => AppPalette::sharedSidebarCss()
            )
            ->renderHook(
                PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (! request()->is('tester') && ! request()->is('tester/')) {
                        return null;
                    }

                    $user = Auth::user();
                    $userId = $user?->id;
                    $userName = e($user?->name ?? 'tester');
                    $userPoints = (int) ($user?->testerProfile?->points ?? 0);

                    $hour = now()->timezone('Asia/Jakarta')->format('H');

                    $greeting = match (true) {
                        $hour >= 5 && $hour < 11 => 'Selamat Pagi',
                        $hour >= 11 && $hour < 15 => 'Selamat Siang',
                        $hour >= 15 && $hour < 18 => 'Selamat Sore',
                        default => 'Selamat Malam',
                    };

                    $svg = fn (string $path): string => "
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1.75rem;height:1.75rem;'>
                            {$path}
                        </svg>
                    ";

                    $icons = [
                        'money' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />"),
                        'rocket' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.63 8.41m5.96 5.96a14.926 14.926 0 0 1-5.96 5.96m0-11.92L4.5 9.75l-2.25 4.5 5.25-.75m2.13-5.09 5.96 5.96m-5.96 5.96L8.25 21.75l-4.5-2.25.75-5.25' />"),
                        'check' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />"),
                        'camera' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M6.827 6.175A2.31 2.31 0 0 1 9.186 4.5h5.628a2.31 2.31 0 0 1 2.359 1.675l.513 1.864a2.25 2.25 0 0 0 2.17 1.661h.394A2.25 2.25 0 0 1 22.5 11.95v5.8A2.25 2.25 0 0 1 20.25 20H3.75A2.25 2.25 0 0 1 1.5 17.75v-5.8A2.25 2.25 0 0 1 3.75 9.7h.394a2.25 2.25 0 0 0 2.17-1.661l.513-1.864Z' /><path stroke-linecap='round' stroke-linejoin='round' d='M15.75 13.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z' />"),
                        'clock' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />"),
                    ];

                    $urlPenukaran = \App\Filament\Tester\Resources\PenukaranPoinResource::getUrl('index');
                    $urlMisi = \App\Filament\Tester\Resources\MisiSayaResource::getUrl('index');
                    $urlCariMisi = \App\Filament\Tester\Resources\CariMisiResource::getUrl('index');

                    $misiAktif = 0;
                    $misiSelesai = 0;
                    $laporanHariIni = 0;
                    $pendingWithdrawals = 0;

                    if (Schema::hasTable('application_testers')) {
                        $misiAktif = DB::table('application_testers')
                            ->where('tester_id', $userId)
                            ->where('status', 'active')
                            ->count();

                        $misiSelesai = DB::table('application_testers')
                            ->where('tester_id', $userId)
                            ->where('status', 'completed')
                            ->count();
                    }

                    if (Schema::hasTable('withdrawals')) {
                        $pendingWithdrawals = DB::table('withdrawals')
                            ->where('tester_id', $userId)
                            ->where('status', 'pending')
                            ->sum('amount_rp');
                    }

                    $pendingWithdrawalsFormatted = number_format((int) $pendingWithdrawals, 0, ',', '.');

                    if (Schema::hasTable('daily_reports')) {
                        $dailyQuery = DB::table('daily_reports');

                        if (Schema::hasColumn('daily_reports', 'tester_id')) {
                            $dailyQuery->where('tester_id', $userId);
                        }

                        if (Schema::hasColumn('daily_reports', 'report_date')) {
                            $dailyQuery->whereDate('report_date', today());
                        } else {
                            $dailyQuery->whereDate('created_at', today());
                        }

                        $laporanHariIni = $dailyQuery->count();
                    }

                    $activeMissions = collect();

                    if (Schema::hasTable('application_testers') && Schema::hasTable('applications')) {
                        $activeMissions = DB::table('application_testers')
                            ->join('applications', 'application_testers.application_id', '=', 'applications.id')
                            ->where('application_testers.tester_id', $userId)
                            ->where('application_testers.status', 'active')
                            ->select(
                                'application_testers.*',
                                'applications.title as app_title'
                            )
                            ->latest('application_testers.created_at')
                            ->limit(3)
                            ->get();
                    }

                    $missionRows = '';

                    foreach ($activeMissions as $mission) {
                        $title = e($mission->app_title ?? 'Aplikasi');

                        $startDate = isset($mission->created_at)
                            ? \Carbon\Carbon::parse($mission->created_at)->startOfDay()
                            : now()->startOfDay();

                        $today = now()->startOfDay();

                        $day = (int) min(14, max(1, floor($startDate->diffInDays($today)) + 1));
                        $percent = (int) round(($day / 14) * 100);

                        $missionRows .= "
                            <div style='padding:1rem 0;border-bottom:1px solid #e2e8f0;'>
                                <div style='display:flex;align-items:center;justify-content:space-between;gap:1rem;'>
                                    <div>
                                        <div style='font-weight:800;color:#0f172a;'>{$title}</div>
                                        <div style='font-size:0.8rem;color:#64748b;margin-top:0.2rem;'>Hari ke-{$day} dari 14</div>
                                    </div>
                                    <span style='font-size:0.75rem;font-weight:800;padding:0.35rem 0.7rem;border-radius:999px;background:var(--tesyuk-secondary);color:var(--tesyuk-primary);'>Aktif</span>
                                </div>
                                <div style='height:8px;background:#e2e8f0;border-radius:999px;margin-top:0.8rem;overflow:hidden;'>
                                    <div style='height:100%;width:{$percent}%;background:var(--tesyuk-accent);border-radius:999px;'></div>
                                </div>
                            </div>
                        ";
                    }

                    if ($missionRows === '') {
                        $missionRows = "
                            <div style='padding:1.5rem;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:18px;'>
                                Belum ada misi aktif. Cari misi baru untuk mulai mengumpulkan poin.
                            </div>
                        ";
                    }

                    $activeMission = $activeMissions->first();

                    $currentDay = 0;
                    $progressPercent = 0;
                    $remainingDays = 14;
                    $activeMissionTitle = 'Belum ada misi aktif';

                    if ($activeMission) {
                        $activeMissionTitle = e($activeMission->app_title ?? 'Aplikasi');

                        $startDate = isset($activeMission->created_at)
                            ? \Carbon\Carbon::parse($activeMission->created_at)->startOfDay()
                            : now()->startOfDay();

                        $today = now()->startOfDay();

                        $currentDay = (int) min(14, max(1, floor($startDate->diffInDays($today)) + 1));
                        $progressPercent = (int) round(($currentDay / 14) * 100);
                        $remainingDays = max(0, 14 - $currentDay);
                    }

                    $dailyStatusLabel = $laporanHariIni > 0 ? 'Sudah Lapor' : 'Belum Lapor';
                    $dailyStatusColor = $laporanHariIni > 0 ? '#047857' : '#b45309';
                    $dailyStatusBg = $laporanHariIni > 0 ? '#ecfdf5' : '#fffbeb';
                    $dailyStatusBorder = $laporanHariIni > 0 ? '#bbf7d0' : '#fde68a';

                    return new HtmlString(<<<HTML
<div style="margin-bottom:2rem;display:flex;flex-direction:column;gap:1.5rem;">
    <div style="background:linear-gradient(135deg, var(--tesyuk-ink) 0%, var(--tesyuk-ink) 68%, var(--tesyuk-primary) 88%, var(--tesyuk-accent) 100%);border-radius:24px;padding:3rem;color:white;position:relative;overflow:hidden;box-shadow:0 20px 40px -15px rgba(var(--tesyuk-ink-rgb),0.4);">
        <div style="position:relative;z-index:10;display:flex;justify-content:space-between;align-items:center;gap:1.5rem;">
            <div>
                <h2 style="font-size:2.25rem;font-weight:800;margin:0;letter-spacing:-0.02em;">{$greeting}, {$userName}!</h2>
                <p style="margin-top:0.75rem;color:rgba(var(--tesyuk-secondary-rgb), 0.78);max-width:600px;font-size:1.125rem;line-height:1.6;">
                    Jalankan misi harian, upload screenshot sebagai absensi, dan kumpulkan reward poin setelah testing selesai.
                </p>
            </div>

            <div class="hidden md:block" style="padding-right:1.5rem;color:rgba(255,255,255,0.72);">
                {$icons['rocket']}
            </div>
        </div>
        <div style="position:absolute;right:-20px;top:-20px;width:200px;height:200px;background:rgba(255,255,255,0.06);border-radius:50%;filter:blur(40px);"></div>
        <div style="position:absolute;right:120px;bottom:-50px;width:150px;height:150px;background:rgba(var(--tesyuk-accent-rgb),0.2);border-radius:50%;filter:blur(40px);"></div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:1rem;">
        <a href="{$urlPenukaran}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
            <div class="icon-bg">{$icons['money']}</div>
            <div>
                <p class="stat-label">Saldo Poin</p>
                <p class="stat-value">{$userPoints} pts</p>
            </div>
        </a>

        <a href="{$urlMisi}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
            <div class="icon-bg">{$icons['rocket']}</div>
            <div>
                <p class="stat-label">Misi Aktif</p>
                <p class="stat-value">{$misiAktif}</p>
            </div>
        </a>

        <a href="{$urlMisi}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
            <div class="icon-bg">{$icons['check']}</div>
            <div>
                <p class="stat-label">Misi Selesai</p>
                <p class="stat-value">{$misiSelesai}</p>
            </div>
        </a>

        <a href="{$urlMisi}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
            <div class="icon-bg">{$icons['camera']}</div>
            <div>
                <p class="stat-label">Laporan Hari Ini</p>
                <p class="stat-value">{$laporanHariIni}</p>
            </div>
        </a>

        <a href="{$urlPenukaran}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
            <div class="icon-bg">{$icons['clock']}</div>
            <div>
                <p class="stat-label">Penarikan Diproses</p>
                <p class="stat-value">Rp {$pendingWithdrawalsFormatted}</p>
            </div>
        </a>
    </div>

    <div style="display:grid;grid-template-columns:1.2fr 0.8fr;gap:1.5rem;">
        <div class="fi-section" style="padding:1.5rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <div>
                    <h3 style="font-size:1.15rem;font-weight:800;margin:0;color:#0f172a;">Misi Aktif</h3>
                    <p style="font-size:0.9rem;color:#64748b;margin:0.25rem 0 0;">Pantau progres testing aplikasi yang sedang kamu jalankan.</p>
                </div>
                <a href="{$urlMisi}" style="font-size:0.85rem;font-weight:800;color:var(--tesyuk-primary);text-decoration:none;">Lihat Misi</a>
            </div>

            {$missionRows}
        </div>

        <div class="fi-section" style="padding:1.5rem;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;">
                <div>
                    <h3 style="font-size:1.15rem;font-weight:800;margin:0;color:#0f172a;">Statistik Misi</h3>
                    <p style="font-size:0.9rem;color:#64748b;line-height:1.6;margin-top:0.4rem;">
                        Ringkasan progres misi testing aktif kamu.
                    </p>
                </div>

                <span style="font-size:.74rem;font-weight:800;padding:.38rem .68rem;border-radius:999px;background:{$dailyStatusBg};color:{$dailyStatusColor};border:1px solid {$dailyStatusBorder};white-space:nowrap;">
                    {$dailyStatusLabel}
                </span>
            </div>

            <div style="margin-top:1rem;padding:1rem;border-radius:18px;background:#f8fafc;border:1px solid #e2e8f0;">
                <div style="font-size:.78rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.04em;">
                    Misi Aktif
                </div>

                <div style="margin-top:.35rem;font-size:1rem;font-weight:850;color:#0f172a;line-height:1.35;">
                    {$activeMissionTitle}
                </div>

                <div style="margin-top:1rem;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:.45rem;">
                        <span style="font-size:.82rem;font-weight:800;color:#475569;">Progress 14 Hari</span>
                        <span style="font-size:.82rem;font-weight:900;color:var(--tesyuk-primary);">{$progressPercent}%</span>
                    </div>

                    <div style="height:10px;background:#e2e8f0;border-radius:999px;overflow:hidden;">
                        <div style="height:100%;width:{$progressPercent}%;background:linear-gradient(90deg,var(--tesyuk-accent),var(--tesyuk-primary));border-radius:999px;"></div>
                    </div>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:.75rem;margin-top:.9rem;">
                <div style="border:1px solid #e2e8f0;background:#ffffff;border-radius:16px;padding:.85rem;">
                    <div style="font-size:.72rem;color:#64748b;font-weight:800;">Hari Ini</div>
                    <div style="font-size:1.15rem;font-weight:900;color:#0f172a;margin-top:.2rem;">{$currentDay}</div>
                </div>

                <div style="border:1px solid #e2e8f0;background:#ffffff;border-radius:16px;padding:.85rem;">
                    <div style="font-size:.72rem;color:#64748b;font-weight:800;">Sisa Hari</div>
                    <div style="font-size:1.15rem;font-weight:900;color:#0f172a;margin-top:.2rem;">{$remainingDays}</div>
                </div>

                <div style="border:1px solid #e2e8f0;background:#ffffff;border-radius:16px;padding:.85rem;">
                    <div style="font-size:.72rem;color:#64748b;font-weight:800;">Target</div>
                    <div style="font-size:1.15rem;font-weight:900;color:#0f172a;margin-top:.2rem;">14</div>
                </div>
            </div>

            <div style="display:flex;gap:.7rem;flex-wrap:wrap;margin-top:1rem;">
                <a href="{$urlMisi}" style="display:inline-flex;padding:0.78rem 1rem;border-radius:999px;background:var(--tesyuk-accent);color:white;font-weight:800;text-decoration:none;">
                    Lihat Misi
                </a>

                <a href="{$urlCariMisi}" style="display:inline-flex;padding:0.78rem 1rem;border-radius:999px;background:var(--tesyuk-secondary);color:var(--tesyuk-primary);font-weight:800;text-decoration:none;border:1px solid rgba(var(--tesyuk-primary-rgb), 0.24);">
                    Cari Misi Baru
                </a>
            </div>
        </div>
    </div>
</div>
HTML);
                }
            )
            ->renderHook(
                PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (! request()->is('tester/penukaran-poins*')) {
                        return null;
                    }

                    $user = Auth::user();
                    $points = (int) ($user?->testerProfile?->points ?? 0);
                    $estimatedBalance = 'Rp' . number_format($points * 1000, 0, ',', '.');

                    $walletIcon = "
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1.7rem;height:1.7rem;'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M21 12.75V9.75A2.25 2.25 0 0 0 18.75 7.5H5.25A2.25 2.25 0 0 1 3 5.25m18 7.5h-4.5a2.25 2.25 0 0 0 0 4.5H21m0-4.5v6A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V5.25m0 0A2.25 2.25 0 0 1 5.25 3h12A2.25 2.25 0 0 1 19.5 5.25V7.5' />
                        </svg>
                    ";

                    $bankIcon = "
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1.35rem;height:1.35rem;'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M3.75 21h16.5M4.5 10.5h15M5.25 21V10.5M9.75 21V10.5M14.25 21V10.5M18.75 21V10.5M12 3 3.75 8.25h16.5L12 3Z' />
                        </svg>
                    ";

                    $clockIcon = "
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1.35rem;height:1.35rem;'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />
                        </svg>
                    ";

                    $checkIcon = "
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1.35rem;height:1.35rem;'>
                            <path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />
                        </svg>
                    ";

                    return new HtmlString(<<<HTML
<div style="margin-bottom:1.5rem;">
    <div style="background:linear-gradient(135deg, var(--tesyuk-ink) 0%, var(--tesyuk-ink) 68%, var(--tesyuk-primary) 88%, var(--tesyuk-accent) 100%);border-radius:26px;padding:2rem;color:white;position:relative;overflow:hidden;box-shadow:0 22px 45px -25px rgba(15,23,42,.45);">
        <div style="position:absolute;right:-60px;top:-60px;width:190px;height:190px;border-radius:999px;background:rgba(255,255,255,.08);filter:blur(10px);"></div>
        <div style="position:absolute;left:-50px;bottom:-70px;width:180px;height:180px;border-radius:999px;background:rgba(255,255,255,.06);filter:blur(12px);"></div>

        <div style="position:relative;z-index:2;display:grid;grid-template-columns:1.3fr .7fr;gap:1.5rem;align-items:center;">
            <div>
                <div style="width:58px;height:58px;border-radius:18px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);margin-bottom:1rem;">
                    {$walletIcon}
                </div>

                <h2 style="font-size:1.85rem;font-weight:850;margin:0;letter-spacing:-.02em;">
                    Kelola Reward Poin
                </h2>

                <p style="margin:.65rem 0 0;color:#dbeafe;line-height:1.6;max-width:620px;font-size:.98rem;">
                    Tukarkan poin dari misi testing ke e-wallet. Pastikan nomor e-wallet dan nama pemilik akun sudah benar sebelum mengajukan pencairan.
                </p>
            </div>

            <div style="background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);border-radius:22px;padding:1.15rem;backdrop-filter:blur(10px);">
                <div style="font-size:.78rem;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#cbd5e1;">
                    Saldo Tersedia
                </div>

                <div style="font-size:2rem;font-weight:900;margin-top:.25rem;">
                    {$points} poin
                </div>

                <div style="font-size:.92rem;color:#dbeafe;margin-top:.25rem;">
                    Estimasi pencairan {$estimatedBalance}
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top:1rem;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:1rem;">
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:20px;padding:1rem;display:flex;gap:.85rem;align-items:flex-start;box-shadow:0 12px 30px -24px rgba(15,23,42,.28);">
            <div style="width:42px;height:42px;border-radius:14px;background:var(--tesyuk-secondary);color:var(--tesyuk-accent);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                {$bankIcon}
            </div>
            <div>
                <div style="font-weight:850;color:#0f172a;">1. Ajukan Pencairan</div>
                <div style="font-size:.84rem;color:#64748b;line-height:1.55;margin-top:.2rem;">Isi e-wallet, atas nama, dan jumlah poin yang ingin dicairkan.</div>
            </div>
        </div>

        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:20px;padding:1rem;display:flex;gap:.85rem;align-items:flex-start;box-shadow:0 12px 30px -24px rgba(15,23,42,.28);">
            <div style="width:42px;height:42px;border-radius:14px;background:var(--tesyuk-secondary);color:var(--tesyuk-accent);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                {$clockIcon}
            </div>
            <div>
                <div style="font-weight:850;color:#0f172a;">2. Diproses Admin</div>
                <div style="font-size:.84rem;color:#64748b;line-height:1.55;margin-top:.2rem;">Admin mengecek detail pengajuan dan melakukan pembayaran di luar aplikasi.</div>
            </div>
        </div>

        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:20px;padding:1rem;display:flex;gap:.85rem;align-items:flex-start;box-shadow:0 12px 30px -24px rgba(15,23,42,.28);">
            <div style="width:42px;height:42px;border-radius:14px;background:var(--tesyuk-secondary);color:var(--tesyuk-accent);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                {$checkIcon}
            </div>
            <div>
                <div style="font-weight:850;color:#0f172a;">3. Status Selesai</div>
                <div style="font-size:.84rem;color:#64748b;line-height:1.55;margin-top:.2rem;">Setelah bukti pembayaran diunggah, status pencairan berubah menjadi selesai.</div>
            </div>
        </div>
    </div>
</div>
HTML);
                }
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                function (): HtmlString {
                    $user = Auth::user();
                    $points = (int) ($user?->testerProfile?->points ?? 0);

                    return new HtmlString(AppPalette::sidebarProfileHtml(
                        name: $user?->name ?? 'Tester',
                        email: $user?->email ?? 'tester@tesyuk.local',
                        metaLines: ["{$points} poin tersedia"],
                        profileUrl: filament()->getProfileUrl() ?? url('/tester/profile'),
                        logoutUrl: filament()->getLogoutUrl(),
                        csrfToken: csrf_token(),
                        avatarUrl: $user?->getFilamentAvatarUrl(),
                        fallbackInitials: 'TS',
                    ));
                }
            )
            ->discoverResources(in: app_path('Filament/Tester/Resources'), for: 'App\\Filament\\Tester\\Resources')
            ->discoverPages(in: app_path('Filament/Tester/Pages'), for: 'App\\Filament\\Tester\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets($chartWidgets)
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
