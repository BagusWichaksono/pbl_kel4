<?php

namespace App\Providers\Filament\Developer;

use App\Support\AppPalette;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class DeveloperPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->spa()
            ->id('developer')
            ->path('developer')
            ->brandName('TesYuk!')
            ->brandLogo(new HtmlString(AppPalette::brandLogoHtml(asset(AppPalette::LOGO_ASSET))))
            ->brandLogoHeight('3rem')
            ->profile(\App\Filament\Developer\Pages\CustomEditProfile::class)            
            ->authGuard('web')
            ->darkMode(false)
            ->colors(AppPalette::filamentColors())
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('Testing')->collapsible(false),
                NavigationGroup::make('Transaksi')->collapsible(false),
                NavigationGroup::make('Laporan')->collapsible(false),
                NavigationGroup::make('Akun')->collapsible(false),
            ])
            ->navigationItems([
                NavigationItem::make('Pengaturan Akun')
                    ->url(fn (): string => filament()->getProfileUrl() ?? '#')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->group('Akun')
                    ->isActiveWhen(fn (): bool => request()->url() === filament()->getProfileUrl())
                    ->sort(1),
            ])
            ->discoverResources(in: app_path('Filament/Developer/Resources'), for: 'App\\Filament\\Developer\\Resources')
            ->discoverPages(in: app_path('Filament/Developer/Pages'), for: 'App\\Filament\\Developer\\Pages')
            ->pages([
                \Filament\Pages\Dashboard::class,
                // \App\Filament\Developer\Pages\CustomEditProfile::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Developer/Widgets'), for: 'App\\Filament\\Developer\\Widgets')
            ->widgets([
                \App\Filament\Developer\Widgets\DeveloperAppsChart::class,
                \App\Filament\Developer\Widgets\DeveloperTestersChart::class,
                \App\Filament\Developer\Widgets\DeveloperReportsChart::class,
            ])
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
            ])
            ->databaseNotifications()
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => AppPalette::cssVariablesStyle() . "
                <style>
                    body,
                    .fi-layout {
                        background-color: #f8fafc !important;
                    }

                    .dark body,
                    .dark .fi-layout {
                        background-color: #0f172a !important;
                    }

                    .fi-main {
                        background: transparent !important;
                    }

                    .fi-topbar {
                        background: transparent !important;
                        border-bottom: none !important;
                        box-shadow: none !important;
                        backdrop-filter: none !important;
                    }

                    .fi-sidebar {
                        background-color: #ffffff !important;
                        backdrop-filter: none !important;
                        border-right: 1px solid #f1f5f9 !important;
                        box-shadow: none !important;
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

                    .dark .fi-sidebar {
                        background-color: #1e293b !important;
                        border-right: 1px solid #334155 !important;
                    }

                    .fi-sidebar-item-button {
                        border-radius: 9999px !important;
                        margin-bottom: 4px !important;
                        padding: 0.6rem 1rem !important;
                        border: 1px solid transparent !important;
                        box-shadow: none !important;
                        transition: all 0.22s ease !important;
                        transform: none !important;
                    }

                    .fi-sidebar-item-button:active {
                        transform: scale(0.98) !important;
                    }

                    .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                        transform: none !important;
                    }

                    html:not(.dark) .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button .fi-sidebar-item-label {
                        color: #475569 !important;
                        font-weight: 600 !important;
                    }

                    html:not(.dark) .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button .fi-sidebar-item-icon {
                        color: #64748b !important;
                    }

                    html:not(.dark) .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover {
                        background-color: #f1f5f9 !important;
                        border-color: #e2e8f0 !important;
                        transform: none !important;
                        box-shadow: none !important;
                    }

                    html:not(.dark) .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                    html:not(.dark) .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                        color: var(--tesyuk-primary) !important;
                    }

                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: var(--tesyuk-secondary) !important;
                        border: 1px solid rgba(var(--tesyuk-primary-rgb), 0.24) !important;
                        box-shadow: none !important;
                        transform: none !important;
                    }

                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label {
                        color: var(--tesyuk-primary) !important;
                        font-weight: 700 !important;
                    }

                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
                        color: var(--tesyuk-accent) !important;
                    }

                    .dark .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover {
                        background-color: #334155 !important;
                        border-color: #475569 !important;
                        transform: none !important;
                        box-shadow: none !important;
                    }

                    .dark .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: rgba(var(--tesyuk-accent-rgb), 0.18) !important;
                        border: 1px solid rgba(var(--tesyuk-accent-rgb), 0.12) !important;
                        box-shadow: none !important;
                    }

                    .dark .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label,
                    .dark .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
                        color: #dbeafe !important;
                        font-weight: 700 !important;
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

                    .dark .fi-ta-ctn,
                    .dark .fi-modal-window,
                    .dark .fi-section {
                        background-color: #1e293b !important;
                        border-color: #334155 !important;
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

                    .dark .custom-card-stats {
                        background: #1e293b !important;
                        border-color: #334155 !important;
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

                    .dark .stat-value {
                        color: #f8fafc !important;
                    }

                    .icon-bg {
                        background: var(--tesyuk-secondary);
                        padding: 0.75rem;
                        border-radius: 12px;
                        color: var(--tesyuk-accent);
                    }

                    .dark .icon-bg {
                        background: rgba(var(--tesyuk-accent-rgb), 0.2) !important;
                        color: var(--tesyuk-accent) !important;
                    }

                    .fi-topbar .fi-user-menu {
                        background: transparent !important;
                        border: none !important;
                        padding: 0 !important;
                    }

                    .fi-topbar .fi-user-menu > button {
                        background-color: #ffffff !important;
                        border: 1px solid #e2e8f0 !important;
                        border-radius: 9999px !important;
                        padding: 4px !important;
                    }

                    .dark .fi-topbar .fi-user-menu > button {
                        background-color: #ffffff !important;
                        border-color: #e2e8f0 !important;
                    }

                    .fi-topbar .fi-user-menu .fi-avatar {
                        background-color: #020617 !important;
                        color: #ffffff !important;
                        border-radius: 9999px !important;
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
                </style>
                "
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => AppPalette::sharedSidebarCss()
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                function (): HtmlString {
                    $user = Auth::user();

                    return new HtmlString(AppPalette::sidebarProfileHtml(
                        name: $user?->name ?? 'Developer',
                        email: $user?->email ?? 'developer@tesyuk.local',
                        metaLines: ['Developer Panel'],
                        profileUrl: filament()->getProfileUrl() ?? url('/developer'),
                        logoutUrl: filament()->getLogoutUrl(),
                        csrfToken: csrf_token(),
                        avatarUrl: $user?->getFilamentAvatarUrl(),
                        fallbackInitials: 'DV',
                    ));
                }
            )
            ->renderHook(
                PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (request()->routeIs('filament.developer.pages.dashboard')) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        $userName = $user?->name ?? 'Developer';

                        $hour = now()->timezone('Asia/Jakarta')->format('H');
                        $greeting = match(true) {
                            $hour >= 5 && $hour < 11 => 'Selamat Pagi',
                            $hour >= 11 && $hour < 15 => 'Selamat Siang',
                            $hour >= 15 && $hour < 18 => 'Selamat Sore',
                            default => 'Selamat Malam',
                        };

                        $urlAddApp = \App\Filament\Developer\Resources\AppResource::getUrl('create');
                        $urlHistory = \App\Filament\Developer\Resources\TestingReportResource::getUrl('index');
                        
                        $devId = $user?->id;
                        $totalApps = \App\Models\App::query()->where('developer_id', '=', $devId, 'and')->count();
                        $totalTesters = \App\Models\ApplicationTester::query()->whereHas('application', fn($q) => $q->where('developer_id', '=', $devId, 'and'))
                            ->where('status', '!=', 'rejected', 'and')
                            ->count();
                        $totalReports = \App\Models\DailyReport::query()->whereHas('application', fn($q) => $q->where('developer_id', '=', $devId, 'and'))->count();

                        return new HtmlString("
                            <div style='margin-bottom: 2rem; display: flex; flex-direction: column; gap: 1.5rem;'>
                                <div style='background: linear-gradient(135deg, var(--tesyuk-ink) 0%, var(--tesyuk-ink) 68%, var(--tesyuk-primary) 88%, var(--tesyuk-accent) 100%); border-radius: 24px; padding: 3rem; color: white; position: relative; overflow: hidden; box-shadow: 0 20px 40px -15px rgba(var(--tesyuk-ink-rgb),0.4);'>
                                    <div style='position: relative; z-index: 10;'>
                                        <h2 style='font-size: 2.25rem; font-weight: 800; margin: 0; letter-spacing: -0.02em;'>{$greeting}, {$userName}!</h2>
                                        <p style='margin-top: 0.75rem; color: rgba(var(--tesyuk-secondary-rgb), 0.78); max-width: 550px; font-size: 1.125rem; line-height: 1.6;'>
                                            Wujudkan aplikasi impianmu. Pantau progres verifikasi, kelola pengujian, dan siapkan aplikasimu untuk meluncur ke tangan pengguna.
                                        </p>
                                    </div>
                                    <div style='position: absolute; right: -20px; top: -20px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; filter: blur(40px);'></div>
                                </div>

                                <div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;'>
                                    
                                    <a href='{$urlAddApp}' class='custom-card-stats' style='text-decoration: none; color: inherit;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 4.5v15m7.5-7.5h-15' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Mulai Proyek</p>
                                            <p class='stat-value'>Daftarkan Aplikasi Baru</p>
                                        </div>
                                    </a>

                                    <a href='{$urlHistory}' class='custom-card-stats' style='text-decoration: none; color: inherit;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12h3.75M9 15h3.375M9 18h3.375m1.875-12h7.5c.621 0 1.125.504 1.125 1.125v13.5c0 .621-.504 1.125-1.125 1.125h-7.5c-.621 0-1.125-.504-1.125-1.125V7.125c0-.621.504-1.125 1.125-1.125zm-8.25 1.5H5.25A2.25 2.25 0 003 3.75v16.5a2.25 2.25 0 002.25 2.25h13.5a2.25 2.25 0 002.25-2.25V14.25' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Cek Status</p>
                                            <p class='stat-value'>Riwayat & Progres Testing</p>
                                        </div>
                                    </a>
                                    
                                    <a href='#' class='custom-card-stats' style='text-decoration: none; color: inherit;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.45 15.04 15.04 0 01.06-.312m-2.24 2.39a4.499 4.499 0 00-1.757 4.306 4.433 4.433 0 002.723-2.023c-2.03-2.03-2.023-2.722-2.023-2.722z' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Total Aplikasi Anda</p>
                                            <p class='stat-value'>{$totalApps} Aplikasi</p>
                                        </div>
                                    </a>
                                    
                                    <a href='#' class='custom-card-stats' style='text-decoration: none; color: inherit;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Total Tester Terlibat</p>
                                            <p class='stat-value'>{$totalTesters} Tester</p>
                                        </div>
                                    </a>
                                    
                                    <a href='#' class='custom-card-stats' style='text-decoration: none; color: inherit;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Laporan Masuk</p>
                                            <p class='stat-value'>{$totalReports} Laporan</p>
                                        </div>
                                    </a>

                                </div>
                            </div>
                        ");
                    }
                }
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => '
                <a href="/developer/hubungi-admin" style="position: fixed; bottom: 30px; right: 30px; width: 64px; height: 64px; background: linear-gradient(135deg, var(--tesyuk-secondary), #ffffff); border: 1px solid rgba(var(--tesyuk-primary-rgb), 0.24); color: var(--tesyuk-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 12px 30px -5px rgba(0,0,0,0.25); z-index: 9999; text-decoration: none; transition: all 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);" onmouseover="this.style.transform=\'scale(1.1) translateY(-4px)\'; this.style.boxShadow=\'0 20px 40px -5px rgba(0,0,0,0.3)\'; this.style.background=\'#ffffff\';" onmouseout="this.style.transform=\'none\'; this.style.boxShadow=\'0 12px 30px -5px rgba(0,0,0,0.25)\'; this.style.background=\'linear-gradient(135deg, var(--tesyuk-secondary), #ffffff)\';">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width: 32px; height: 32px;">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z" />
                    </svg>
                    <span style="position: absolute; top: -4px; right: -4px; display: flex; height: 18px; width: 18px; align-items: center; justify-content: center; border-radius: 50%; background-color: var(--tesyuk-accent); color: white; font-size: 10px; font-weight: bold; border: 2px solid white; display: none;" id="chat-notification-badge"></span>
                </a>
                <script>
                    // Only show badge if needed, can implement real-time unread fetch later
                </script>
                '
            );
    }
}
