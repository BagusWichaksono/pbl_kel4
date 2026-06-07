<?php

namespace App\Providers\Filament\Developer;

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

                    .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button .fi-sidebar-item-label {
                        color: #475569 !important;
                        font-weight: 600 !important;
                    }

                    .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button .fi-sidebar-item-icon {
                        color: #64748b !important;
                    }

                    .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover {
                        background-color: #f1f5f9 !important;
                        border-color: #e2e8f0 !important;
                        transform: none !important;
                        box-shadow: none !important;
                    }

                    .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                    .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                        color: var(--tesyuk-primary) !important;
                    }

                    .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: var(--tesyuk-secondary) !important;
                        border: 1px solid rgba(var(--tesyuk-primary-rgb), 0.24) !important;
                        box-shadow: none !important;
                        transform: none !important;
                    }

                    .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label {
                        color: var(--tesyuk-primary) !important;
                        font-weight: 700 !important;
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

                    .developer-action-grid {
                        display: grid !important;
                        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                        gap: 1rem !important;
                    }

                    .developer-action-button {
                        background: #ffffff !important;
                        border: 1px solid #e2e8f0 !important;
                        border-radius: 999px !important;
                        padding: 1rem 1.2rem !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: space-between !important;
                        gap: 1rem !important;
                        box-shadow: 0 18px 34px -28px rgba(15, 23, 42, .38) !important;
                        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease !important;
                    }

                    .developer-action-button:hover {
                        transform: translateY(-2px) !important;
                        border-color: rgba(var(--tesyuk-primary-rgb), .22) !important;
                        box-shadow: 0 24px 42px -30px rgba(var(--tesyuk-primary-rgb), .48) !important;
                    }

                    .developer-action-button-main {
                        background: var(--tesyuk-primary) !important;
                        border-color: var(--tesyuk-primary) !important;
                        color: #ffffff !important;
                    }

                    .developer-action-button-main .stat-label,
                    .developer-action-button-main .stat-value {
                        color: #ffffff !important;
                    }

                    .developer-action-button-main .stat-label {
                        opacity: .72 !important;
                    }

                    .developer-action-button .icon-bg {
                        border-radius: 999px !important;
                        flex-shrink: 0 !important;
                    }

                    .developer-action-button-main .icon-bg {
                        background: rgba(255,255,255,.14) !important;
                        color: #ffffff !important;
                    }

                    .developer-action-arrow {
                        width: 2rem !important;
                        height: 2rem !important;
                        border-radius: 999px !important;
                        display: flex !important;
                        align-items: center !important;
                        justify-content: center !important;
                        flex-shrink: 0 !important;
                        background: #f8fafc !important;
                        color: var(--tesyuk-primary) !important;
                    }

                    .developer-action-button-main .developer-action-arrow {
                        background: rgba(255,255,255,.16) !important;
                        color: #ffffff !important;
                    }

                    @media (max-width: 768px) {
                        .developer-action-grid {
                            grid-template-columns: 1fr !important;
                        }
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
                        
                        $logoDevUrl = asset('assets/logo-developer.png');
                        
                        return new HtmlString("
                            <div style='margin-bottom: 2rem; display: flex; flex-direction: column; gap: 1.5rem;'>
                                <div style='background: linear-gradient(135deg, var(--tesyuk-ink) 0%, var(--tesyuk-ink) 68%, var(--tesyuk-primary) 88%, var(--tesyuk-accent) 100%); border-radius: 24px; padding: 3rem; color: white; position: relative; overflow: hidden; box-shadow: 0 20px 40px -15px rgba(var(--tesyuk-ink-rgb),0.4);'>
                                    <div style='position: relative; z-index: 10;'>
                                        <h2 style='font-size: 2.25rem; font-weight: 800; margin: 0; letter-spacing: -0.02em;'>{$greeting}, {$userName}!</h2>
                                        <p style='margin-top: 0.75rem; color: rgba(var(--tesyuk-secondary-rgb), 0.78); max-width: 550px; font-size: 1.125rem; line-height: 1.6;'>
                                            Wujudkan aplikasi impianmu. Pantau progres verifikasi, kelola pengujian, dan siapkan aplikasimu untuk meluncur ke tangan pengguna.
                                        </p>
                                    </div>
                                    
                                    <img src='{$logoDevUrl}' alt='Developer Logo' style='position: absolute; right: 2rem; bottom: -1.5rem; height: 110%; z-index: 5; object-fit: contain; pointer-events: none;'>
                                    <div style='position: absolute; right: -20px; top: -20px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; filter: blur(40px); z-index: 1;'></div>
                                </div>

                                <div class='developer-action-grid'>
                                    
                                    <a href='{$urlAddApp}' class='developer-action-button developer-action-button-main' style='text-decoration: none;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 4.5v15m7.5-7.5h-15' /></svg>
                                        </div>
                                        <div style='min-width:0;flex:1;'>
                                            <p class='stat-label'>Mulai Proyek</p>
                                            <p class='stat-value'>Daftarkan Aplikasi Baru</p>
                                        </div>
                                        <div class='developer-action-arrow'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.2' stroke='currentColor' style='width:1rem;height:1rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3' /></svg>
                                        </div>
                                    </a>

                                    <a href='{$urlHistory}' class='developer-action-button' style='text-decoration: none; color: inherit;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12h3.75M9 15h3.375M9 18h3.375m1.875-12h7.5c.621 0 1.125.504 1.125 1.125v13.5c0 .621-.504 1.125-1.125 1.125h-7.5c-.621 0-1.125-.504-1.125-1.125V7.125c0-.621.504-1.125 1.125-1.125zm-8.25 1.5H5.25A2.25 2.25 0 003 3.75v16.5a2.25 2.25 0 002.25 2.25h13.5a2.25 2.25 0 002.25-2.25V14.25' /></svg>
                                        </div>
                                        <div style='min-width:0;flex:1;'>
                                            <p class='stat-label'>Cek Status</p>
                                            <p class='stat-value'>Riwayat & Progres Testing</p>
                                        </div>
                                        <div class='developer-action-arrow'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.2' stroke='currentColor' style='width:1rem;height:1rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3' /></svg>
                                        </div>
                                    </a>

                                </div>

                                <div style='background:linear-gradient(to right, #eff6ff, #ffffff);border:1px solid #bfdbfe;border-left:4px solid #3b82f6;border-radius:16px;padding:1.25rem 1.5rem;display:flex;gap:1rem;align-items:flex-start;'>
                                <div style='color:#3b82f6;flex-shrink:0;margin-top:2px;'>
                                    <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2.5' stroke='currentColor' style='width:1.5rem;height:1.5rem;'>
                                        <path stroke-linecap='round' stroke-linejoin='round' d='M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z' />
                                    </svg>
                                </div>
                                <div>
                                    <div style='font-size:1rem;font-weight:800;color:#1e3a8a;'>Informasi Grafik</div>
                                    <div style='font-size:.875rem;color:#1d4ed8;line-height:1.6;margin-top:.25rem;'>
                                        Grafik di bawah menampilkan data 6 bulan terakhir. Anda dapat mengarahkan kursor ke titik atau batang grafik untuk melihat detail angka per bulan dengan lebih jelas.
                                    </div>
                                </div>
                            </div>
                            </div>
                        ");
                    }
                }
            )
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn (): string => \Illuminate\Support\Facades\Blade::render('<livewire:support-chat-widget role="developer" />')
            );
    }
}
