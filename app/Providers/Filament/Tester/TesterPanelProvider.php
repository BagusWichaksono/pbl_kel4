<?php

namespace App\Providers\Filament\Tester;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

class TesterPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tester')
            ->path('tester')
            ->brandName(new HtmlString('<span style="background: linear-gradient(135deg, #5374ac, #2f456f); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; letter-spacing: -0.02em;">TesYuk!</span>'))
            ->authGuard('web')
            ->colors([
                'primary' => [
                    50 => '#eff5fa',
                    100 => '#d1e1f1',
                    200 => '#b3cce2',
                    300 => '#8bafd0',
                    400 => '#6b92be',
                    500 => '#5374ac',
                    600 => '#425d8a',
                    700 => '#2f456f',
                    800 => '#1e2d49',
                    900 => '#141c33',
                ],
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->font('Poppins')
            ->profile(\App\Filament\Tester\Pages\CustomEditProfile::class)

            // Sidebar collapse dimatikan supaya tidak ada tombol/dropdown collapse.
            // Jangan aktifkan: ->sidebarCollapsibleOnDesktop()

            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => "
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
                    }

                    .fi-sidebar {
                        background-color: #ffffff !important;
                        border-right: 1px solid #f1f5f9 !important;
                        box-shadow: none !important;
                    }

                    .dark .fi-sidebar {
                        background-color: #1e293b !important;
                        border-right: 1px solid #334155 !important;
                    }

                    .fi-sidebar-item-button {
                        border-radius: 9999px !important;
                        transition: all 0.3s ease !important;
                        margin-bottom: 4px !important;
                        border: none !important;
                        box-shadow: none !important;
                        padding: 0.6rem 1rem !important;
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
                    }

                    html:not(.dark) .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                    html:not(.dark) .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                        color: #0f172a !important;
                    }

                    .dark .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover {
                        background-color: #334155 !important;
                    }

                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: #86a0cd !important;
                        border: none !important;
                        box-shadow: 0 4px 15px -3px rgba(83, 116, 172, 0.4) !important;
                    }

                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label,
                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
                        color: #ffffff !important;
                        font-weight: 700 !important;
                    }

                    .dark .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: #425d8a !important;
                    }

                    .dark .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label,
                    .dark .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
                        color: #ffffff !important;
                    }

                    .fi-sidebar-group-collapse-button {
                        display: none !important;
                    }

                    .fi-ta-ctn,
                    .fi-modal-window,
                    .fi-section {
                        border-radius: 24px !important;
                        border: 1px solid rgba(83, 116, 172, 0.1) !important;
                        box-shadow: 0 4px 20px -5px rgba(83, 116, 172, 0.05) !important;
                        background-color: white !important;
                    }

                    .dark .fi-ta-ctn,
                    .dark .fi-modal-window,
                    .dark .fi-section {
                        background-color: #1e293b !important;
                    }

                    .custom-card-stats {
                        background: white !important;
                        border: 1px solid rgba(83, 116, 172, 0.1) !important;
                        border-radius: 20px !important;
                        padding: 1.5rem !important;
                        display: flex !important;
                        align-items: center !important;
                        gap: 1rem !important;
                        box-shadow: 0 4px 20px -2px rgba(83, 116, 172, 0.08) !important;
                        transition: transform 0.3s ease, box-shadow 0.3s ease !important;
                    }

                    .custom-card-stats:hover {
                        transform: translateY(-3px) !important;
                        box-shadow: 0 10px 25px -5px rgba(83, 116, 172, 0.12) !important;
                    }

                    .dark .custom-card-stats {
                        background: #1e293b !important;
                    }

                    .stat-label {
                        color: #6b7280;
                        font-size: 0.75rem;
                        font-weight: 700;
                        text-transform: uppercase;
                        margin: 0;
                    }

                    .stat-value {
                        color: #141c33;
                        font-size: 1.25rem;
                        font-weight: 800;
                        margin: 0;
                    }

                    .dark .stat-value {
                        color: #f8fafc !important;
                    }

                    .icon-bg {
                        background: #eff5fa;
                        padding: 0.75rem;
                        border-radius: 12px;
                        color: #5374ac;
                    }

                    .dark .icon-bg {
                        background: rgba(83, 116, 172, 0.2) !important;
                        color: #8bafd0 !important;
                    }

                    /* User menu kanan atas */
                    .fi-topbar .fi-user-menu {
                        background: transparent !important;
                        border: none !important;
                        padding: 0 !important;
                    }

                    /* Hanya tombol avatar di topbar, bukan tombol di dropdown */
                    .fi-topbar .fi-user-menu > button {
                        background-color: #ffffff !important;
                        border: 1px solid #e2e8f0 !important;
                        border-radius: 9999px !important;
                        padding: 4px !important;
                    }

                    /* Dark mode: lingkaran luar avatar tetap putih */
                    .dark .fi-topbar .fi-user-menu > button {
                        background-color: #ffffff !important;
                        border-color: #e2e8f0 !important;
                    }

                    /* Avatar bagian dalam tetap gelap */
                    .fi-topbar .fi-user-menu .fi-avatar {
                        background-color: #020617 !important;
                        color: #ffffff !important;
                        border-radius: 9999px !important;
                    }

                    /* Jangan ubah tombol di dropdown */
                    .fi-dropdown-panel button,
                    .fi-dropdown-panel a {
                        background-color: unset;
                    }

                    .fi-dropdown-list-item:hover {
                        background-color: #f0f7ff !important;
                    }

                    .fi-dropdown-list-item-label {
                        font-weight: 600 !important;
                    }
                </style>
                "
            )

            ->renderHook(
                PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (! request()->routeIs('filament.tester.pages.dashboard')) {
                        return null;
                    }

                    $user = Auth::user();
                    $userName = $user?->name ?? 'tester';
                    $userPoints = $user?->testerProfile?->points ?? 0;

                    $hour = now()->format('H');
                    $greeting = match (true) {
                        $hour >= 5 && $hour < 11 => 'Selamat Pagi',
                        $hour >= 11 && $hour < 15 => 'Selamat Siang',
                        $hour >= 15 && $hour < 18 => 'Selamat Sore',
                        default => 'Selamat Malam',
                    };

                    $urlPenukaran = \App\Filament\Tester\Resources\PenukaranPoinResource::getUrl('index');
                    $urlMisi = \App\Filament\Tester\Resources\MisiSayaResource::getUrl('index');

                    $misiAktif = \App\Models\ApplicationTester::where('tester_id', $user?->id)
                        ->where('status', 'active')
                        ->count();

                    return new HtmlString("
                        <div style='margin-bottom: 2rem; display: flex; flex-direction: column; gap: 1.5rem;'>
                            <div style='background: linear-gradient(135deg, #141c33 0%, #2f456f 50%, #5374ac 100%); border-radius: 24px; padding: 3rem; color: white; position: relative; overflow: hidden; box-shadow: 0 20px 40px -15px rgba(20,28,51,0.4);'>
                                <div style='position: relative; z-index: 10; display: flex; justify-content: space-between; align-items: center;'>
                                    <div>
                                        <h2 style='font-size: 2.25rem; font-weight: 800; margin: 0; letter-spacing: -0.02em;'>{$greeting}, {$userName}!</h2>
                                        <p style='margin-top: 0.75rem; color: #cbdcf0; max-width: 500px; font-size: 1.125rem; line-height: 1.6;'>
                                            Kualitas aplikasi ada di tanganmu. Mari bantu developer membangun aplikasi terbaik hari ini.
                                        </p>
                                    </div>

                                    <div class='hidden md:block' style='padding-right: 2rem;'>
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='1.2' stroke='currentColor' style='width: 160px; height: 160px; color: rgba(255,255,255,0.7); transform: rotate(15deg) translateY(-10px);'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.631 8.41m5.96 5.96a14.926 14.926 0 0 1-5.841 2.58m-.119-8.54a6 6 0 0 0-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 0 0-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 0 1-2.448-2.45 15.04 15.04 0 0 1 .06-.312m-2.24 2.39a4.499 4.499 0 0 0-1.757 4.306 4.433 4.433 0 0 0 2.723-2.023c-2.03-2.03-2.023-2.722-2.023-2.722Z' />
                                        </svg>
                                    </div>
                                </div>

                                <div style='position: absolute; right: -20px; top: -20px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; filter: blur(40px);'></div>
                                <div style='position: absolute; right: 120px; bottom: -50px; width: 150px; height: 150px; background: rgba(83,116,172,0.2); border-radius: 50%; filter: blur(40px);'></div>
                            </div>

                            <div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;'>
                                <a href='{$urlPenukaran}' class='custom-card-stats' style='text-decoration: none; color: inherit; cursor: pointer;'>
                                    <div class='icon-bg'>
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z' />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class='stat-label'>Saldo Poin</p>
                                        <p class='stat-value'>{$userPoints} pts</p>
                                    </div>
                                </a>

                                <a href='{$urlMisi}' class='custom-card-stats' style='text-decoration: none; color: inherit; cursor: pointer;'>
                                    <div class='icon-bg'>
                                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'>
                                            <path stroke-linecap='round' stroke-linejoin='round' d='M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75' />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class='stat-label'>Misi Aktif</p>
                                        <p class='stat-value'>{$misiAktif} Tugas</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    ");
                }
            )

            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn () => new HtmlString(
                    '<div class="hidden md:flex items-center text-sm font-semibold mr-3">
                        <span class="text-gray-900 dark:text-white" style="margin-left: 4px;">' . e(Auth::user()?->name ?? 'tester') . '</span>
                    </div>'
                )
            )

            ->navigationGroups([
                NavigationGroup::make('Menu')->collapsible(false),
                NavigationGroup::make('Aktivitas Testing')->collapsible(false),
                NavigationGroup::make('Reward Tester')->collapsible(false),
                NavigationGroup::make('Akun & Bantuan')->collapsible(false),
            ])

            ->navigationItems([
                NavigationItem::make('Profil')
                    ->url(fn (): string => filament()->getProfileUrl() ?? '#')
                    ->icon('heroicon-o-user-circle')
                    ->group('Akun & Bantuan')
                    ->isActiveWhen(fn () => request()->url() === filament()->getProfileUrl())
                    ->sort(2),
            ])

            ->discoverResources(in: app_path('Filament/Tester/Resources'), for: 'App\\Filament\\Tester\\Resources')
            ->discoverPages(in: app_path('Filament/Tester/Pages'), for: 'App\\Filament\\Tester\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])

            // Sengaja tidak pakai discoverWidgets supaya dashboard tidak dobel.
            // Widget PenukaranPoinStats tetap bisa dipanggil manual dari halaman Penukaran Poin.

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