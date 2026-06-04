<?php

namespace App\Providers\Filament\Admin;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Navigation\NavigationGroup;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->spa()
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName(new HtmlString('<span style="background: linear-gradient(135deg, #5374ac, #2f456f); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; letter-spacing: -0.02em;">TesYuk!</span>'))
            ->colors([
                'primary' => [
                    50 => '#eff5fa', 100 => '#d1e1f1', 200 => '#b3cce2', 300 => '#8bafd0',
                    400 => '#6b92be', 500 => '#5374ac', 600 => '#425d8a', 700 => '#2f456f',
                    800 => '#1e2d49', 900 => '#141c33',
                ],
                'danger' => Color::Rose,
                'success' => Color::Emerald,
                'gray' => Color::Slate,
            ])
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('Verifikasi')->collapsible(false),
                NavigationGroup::make('Manajemen Testing')->collapsible(false),
                NavigationGroup::make('Keuangan')->collapsible(false),
                NavigationGroup::make('Bantuan')->collapsible(false),
                NavigationGroup::make('Akun')->collapsible(false),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                \Filament\Pages\Dashboard::class,
            ])
            // ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                \App\Filament\Admin\Widgets\AdminRevenueChart::class,
                \App\Filament\Admin\Widgets\AdminAppsChart::class,
                \App\Filament\Admin\Widgets\AdminUsersChart::class,
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
            ->authGuard('web')
            
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
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
                        backdrop-filter: none !important;
                    }

                    .fi-sidebar {
                        background-color: #ffffff !important;
                        backdrop-filter: none !important;
                        border-right: 1px solid #f1f5f9 !important;
                        box-shadow: none !important;
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
                        color: #2f456f !important;
                    }

                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: #eff5fa !important;
                        border: 1px solid #d1e1f1 !important;
                        box-shadow: none !important;
                        transform: none !important;
                    }

                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label {
                        color: #2f456f !important;
                        font-weight: 700 !important;
                    }

                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
                        color: #5374ac !important;
                    }

                    .dark .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover {
                        background-color: #334155 !important;
                        border-color: #475569 !important;
                        transform: none !important;
                        box-shadow: none !important;
                    }

                    .dark .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: rgba(83, 116, 172, 0.18) !important;
                        border: 1px solid rgba(139, 175, 208, 0.28) !important;
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
                        border: 1px solid rgba(83, 116, 172, 0.1) !important;
                        box-shadow: 0 4px 20px -5px rgba(83, 116, 172, 0.05) !important;
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
                        background-color: #f0f7ff !important;
                    }

                    .fi-dropdown-list-item-label {
                        font-weight: 600 !important;
                    }
                </style>
                "
            )

            ->renderHook(
                \Filament\View\PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (request()->routeIs('filament.admin.pages.dashboard')) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        $userName = $user?->name ?? 'Admin';

                        $hour = now()->timezone('Asia/Jakarta')->format('H');
                        $greeting = match(true) {
                            $hour >= 5 && $hour < 11 => 'Selamat Pagi',
                            $hour >= 11 && $hour < 15 => 'Selamat Siang',
                            $hour >= 15 && $hour < 18 => 'Selamat Sore',
                            default => 'Selamat Malam',
                        };

                        $urlApp = \App\Filament\Admin\Resources\AppResource::getUrl('index');
                        $urlPayment = \App\Filament\Admin\Resources\TransactionResource::getUrl('index');
                        
                        $totalRevenue = \App\Models\App::query()->where('payment_status', '=', 'valid', 'and')->count() * 300000;
                        $totalRevenueFormatted = number_format($totalRevenue, 0, ',', '.');
                        
                        $totalApps = \App\Models\App::query()->where('payment_status', '=', 'valid', 'and')->count();
                        $activeTesters = \App\Models\User::query()->where('role', '=', 'tester', 'and')->count();
                        $pendingWithdrawals = \App\Models\Withdrawal::query()->where('status', '=', 'pending', 'and')->count();

                        return new HtmlString("
                            <div style='margin-bottom: 2rem; display: flex; flex-direction: column; gap: 1.5rem;'>
                                <div style='background: linear-gradient(135deg, #141c33 0%, #2f456f 50%, #5374ac 100%); border-radius: 24px; padding: 3rem; color: white; position: relative; overflow: hidden; box-shadow: 0 20px 40px -15px rgba(20,28,51,0.4);'>
                                    <div style='position: relative; z-index: 10;'>
                                        <h2 style='font-size: 2.25rem; font-weight: 800; margin: 0; letter-spacing: -0.02em;'>{$greeting}, {$userName}!</h2>
                                        <p style='margin-top: 0.75rem; color: #cbdcf0; max-width: 550px; font-size: 1.125rem; line-height: 1.6;'>Pusat Kendali Aplikasi. Pastikan semua pembayaran dan antrean aplikasi developer tervalidasi dengan baik hari ini.</p>
                                    </div>
                                    <div style='position: absolute; right: -20px; top: -20px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; filter: blur(40px);'></div>
                                    <div style='position: absolute; left: 60%; bottom: -50px; width: 150px; height: 150px; background: rgba(255,255,255,0.02); border-radius: 50%; filter: blur(30px);'></div>
                                </div>

                                <div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;'>
                                    
                                    <a href='{$urlPayment}' class='custom-card-stats' style='text-decoration: none; color: inherit; cursor: pointer;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Cek Antrean</p>
                                            <p class='stat-value' style='font-size: 1.1rem;'>Validasi Pembayaran</p>
                                        </div>
                                    </a>

                                    <a href='{$urlApp}' class='custom-card-stats' style='text-decoration: none; color: inherit; cursor: pointer;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Cek Aplikasi</p>
                                            <p class='stat-value' style='font-size: 1.1rem;'>Validasi Aplikasi</p>
                                        </div>
                                    </a>
                                    
                                    <a href='#' class='custom-card-stats' style='text-decoration: none; color: inherit; cursor: pointer;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Total Pendapatan</p>
                                            <p class='stat-value' style='font-size: 1.1rem;'>Rp {$totalRevenueFormatted}</p>
                                        </div>
                                    </a>
                                    
                                    <a href='#' class='custom-card-stats' style='text-decoration: none; color: inherit; cursor: pointer;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Aplikasi Valid</p>
                                            <p class='stat-value' style='font-size: 1.1rem;'>{$totalApps} Aplikasi</p>
                                        </div>
                                    </a>
                                    
                                    <a href='#' class='custom-card-stats' style='text-decoration: none; color: inherit; cursor: pointer;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Tester Aktif</p>
                                            <p class='stat-value' style='font-size: 1.1rem;'>{$activeTesters} User</p>
                                        </div>
                                    </a>
                                    
                                    <a href='#' class='custom-card-stats' style='text-decoration: none; color: inherit; cursor: pointer;'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>
                                        </div>
                                        <div>
                                            <p class='stat-label'>Pending Withdraw</p>
                                            <p class='stat-value' style='font-size: 1.1rem;'>{$pendingWithdrawals} Request</p>
                                        </div>
                                    </a>

                                </div>
                            </div>
                        ");
                    }
                }
            );
    }
}