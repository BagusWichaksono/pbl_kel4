<?php

namespace App\Providers\Filament\Developer;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class DeveloperPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->spa()
            ->id('developer')
            ->path('developer')
            ->brandName(new HtmlString('<span style="background: linear-gradient(135deg, #5374ac, #2f456f); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; letter-spacing: -0.02em;">TesYuk!</span>'))            ->authGuard('web')
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
            ->profile()
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Developer/Resources'), for: 'App\\Filament\\Developer\\Resources')
            ->discoverPages(in: app_path('Filament/Developer/Pages'), for: 'App\\Filament\\Developer\\Pages')
            ->pages([
                \Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Developer/Widgets'), for: 'App\\Filament\\Developer\\Widgets')
            ->widgets([
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
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => "
                <style> 
                    /* 1. BACKGROUND & LAYOUT (Vibe Landing Page) */
                    body, .fi-layout {
                        background: linear-gradient(180deg, #e4eff8 0%, #ffffff 100%) !important;
                        background-attachment: fixed !important;
                    }
                    .dark body, .dark .fi-layout {
                        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
                    }
                    .fi-main { background: transparent !important; }

                    /* 2. TOPBAR (Bening & Clean) */
                    .fi-topbar { 
                        background: transparent !important; 
                        border-bottom: none !important; 
                        box-shadow: none !important; 
                        backdrop-filter: none !important; 
                    }

                    /* 3. SIDEBAR (Glassmorphism) */
                    .fi-sidebar {
                        background: rgba(255, 255, 255, 0.4) !important;
                        backdrop-filter: blur(12px) !important;
                        border-right: none !important;
                        box-shadow: 4px 0 24px -5px rgba(83, 116, 172, 0.05) !important;
                    }
                    .dark .fi-sidebar { background: rgba(30, 41, 59, 0.4) !important; }

                    /* Transisi dasar dengan efek 'Pegas' (Bouncy) */
                    .fi-sidebar-item-button, .custom-card-stats { 
                        border-radius: 9999px !important; 
                        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
                        transform-origin: center center !important;
                    }

                    /* EFEK TEKAN FISIK (Tactile Feedback) */
                    .fi-sidebar-item-button:active, .custom-card-stats:active {
                        transform: scale(0.95) !important;
                        transition: transform 0.1s ease-out !important;
                    }

                    /* Icon membesar halus saat hover */
                    .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                        transform: scale(1.15) !important;
                        transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
                    }

                    /* Hover Sidebar Light Mode */
                    html:not(.dark) .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover {
                        background-color: rgba(83, 116, 172, 0.08) !important;
                        transform: translateY(-2px) scale(1.01) !important; 
                        box-shadow: 0 8px 20px -4px rgba(83, 116, 172, 0.15) !important;
                    }

                    /* Hover Sidebar Dark Mode */
                    .dark .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover {
                        background-color: rgba(255, 255, 255, 0.05) !important;
                        transform: translateY(-2px) scale(1.01) !important;
                        box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.4) !important;
                    }

                    /* Hover Kartu Statistik */
                    .custom-card-stats:hover {
                        transform: translateY(-5px) scale(1.02) !important;
                        box-shadow: 0 15px 30px -5px rgba(83, 116, 172, 0.15) !important;
                    }
                    .dark .custom-card-stats:hover {
                        box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.5) !important;
                    }

                    /* ========================================= */

                    .fi-topbar .fi-input-wrp { border-radius: 9999px !important; }

                    /* 4. MODAL & TABEL (Rounded corners) */
                    .fi-ta-ctn, .fi-modal-window, .fi-section {
                        border-radius: 24px !important;
                        border: 1px solid rgba(83, 116, 172, 0.1) !important;
                        box-shadow: 0 10px 30px -5px rgba(83, 116, 172, 0.05) !important;
                        background: rgba(255, 255, 255, 0.8) !important;
                        backdrop-filter: blur(10px) !important;
                    }
                    .dark .fi-ta-ctn, .dark .fi-modal-window, .dark .fi-section {
                        background: rgba(30, 41, 59, 0.8) !important;
                        border-color: rgba(255,255,255,0.05) !important;
                    }

                    /* 5. HIGHLIGHT MENU AKTIF */
                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: #eff5fa !important;
                        border: 1px solid #d1e1f1 !important;
                        box-shadow: 0 4px 15px -3px rgba(83, 116, 172, 0.15) !important;
                        font-weight: 700 !important;
                    }
                    html:not(.dark) .fi-sidebar-item-active .fi-sidebar-item-icon { color: #5374ac !important; }

                    .dark .fi-sidebar-item-active .fi-sidebar-item-button {
                        background-color: rgba(83, 116, 172, 0.15) !important;
                        border: 1px solid rgba(83, 116, 172, 0.3) !important;
                        box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.4) !important;
                        font-weight: 700 !important;
                    }

                    /* 7. WIDGET CUSTOM CARD STATS */
                    .custom-card-stats {
                        background: white !important;
                        border: 1px solid rgba(83, 116, 172, 0.1) !important;
                        border-radius: 20px !important;
                        padding: 1.5rem !important;
                        display: flex !important;
                        align-items: center !important;
                        gap: 1rem !important;
                        box-shadow: 0 4px 20px -2px rgba(83, 116, 172, 0.08) !important;
                    }
                    .dark .custom-card-stats { background: rgba(30, 41, 59, 0.8) !important; backdrop-filter: blur(10px) !important; border-color: rgba(255,255,255,0.05) !important; }
                    .stat-label { color: #6b7280; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin: 0; }
                    .stat-value { color: #141c33; font-size: 1.25rem; font-weight: 800; margin: 0; }
                    .dark .stat-value { color: #f8fafc !important; }
                    .icon-bg { background: #eff5fa; padding: 0.75rem; border-radius: 12px; color: #5374ac; }
                    .dark .icon-bg { background: rgba(83, 116, 172, 0.2) !important; color: #8bafd0 !important; }
                </style>
                "
            )

            ->renderHook(
                \Filament\View\PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (request()->routeIs('filament.developer.pages.dashboard')) {
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
                                </div>
                            </div>
                        ");
                    }
                }
            );
    }
}
