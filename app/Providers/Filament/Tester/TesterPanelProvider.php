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
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;

class TesterPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('tester')
            ->path('tester')
            ->login()
            ->profile()
            ->brandName(new HtmlString('<span style="background: linear-gradient(135deg, #5374ac, #2f456f); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; letter-spacing: -0.02em;">TesYuk!</span>'))            ->authGuard('web')
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
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

            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => "
                <style>
                    @keyframes smoothSlideUp {
                        0% { opacity: 0; transform: translateY(30px); }
                        100% { opacity: 1; transform: translateY(0); }
                    }
                    .fi-main { animation: smoothSlideUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

                    /* Fix Pojok Kiri Banner & Kontainer */
                    .fi-main-ctn { padding-top: 0 !important; }
                    .fi-topbar { background: rgba(255, 255, 255, 0.95) !important; z-index: 30 !important; }
                    .dark .fi-topbar { background: rgba(20, 28, 51, 0.95) !important; }

                    /* Style Widget Pintar (Support Dark Mode) */
                    .custom-card-stats {
                        background: white !important;
                        border: 1px solid rgba(83, 116, 172, 0.1) !important;
                        border-radius: 20px !important;
                        padding: 1.5rem !important;
                        display: flex !important;
                        align-items: center !important;
                        gap: 1rem !important;
                        transition: all 0.3s ease;
                    }

                    .dark .custom-card-stats {
                        background: #1e293b !important; /* Warna Slate 800 */
                        border-color: rgba(255, 255, 255, 0.1) !important;
                    }

                    /* Warna Teks Stat */
                    .stat-label { color: #6b7280; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; margin: 0; }
                    .stat-value { color: #141c33; font-size: 1.25rem; font-weight: 800; margin: 0; }

                    .dark .stat-label { color: #94a3b8 !important; }
                    .dark .stat-value { color: #f8fafc !important; }

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

                    .fi-btn { border-radius: 9999px !important; font-weight: 600 !important; }
                </style>
                "
            )

            ->renderHook(
                \Filament\View\PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (request()->routeIs('filament.tester.pages.dashboard')) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        $userName = $user?->name ?? 'Tester';
                        $userPoints = $user?->points ?? 0;

                        $hour = now()->format('H');
                        $greeting = match(true) {
                            $hour >= 5 && $hour < 11 => 'Selamat Pagi',
                            $hour >= 11 && $hour < 15 => 'Selamat Siang',
                            $hour >= 15 && $hour < 18 => 'Selamat Sore',
                            default => 'Selamat Malam',
                        };

                        $misiAktif = \App\Models\ApplicationTester::where('tester_id', $user?->id)->where('status', 'active')->count();
                        $misiSelesai = \App\Models\ApplicationTester::where('tester_id', $user?->id)->where('status', 'completed')->count();

                        return new HtmlString("
                            <div style='margin-bottom: 2rem; display: flex; flex-direction: column; gap: 1.5rem;'>
                                <div style='background: linear-gradient(135deg, #141c33 0%, #2f456f 50%, #5374ac 100%); border-radius: 24px; padding: 3rem; color: white; position: relative; overflow: hidden; box-shadow: 0 20px 40px -15px rgba(20,28,51,0.4);'>
                                    <div style='position: relative; z-index: 10;'>
                                        <h2 style='font-size: 2.25rem; font-weight: 800; margin: 0; letter-spacing: -0.02em;'>{$greeting}, {$userName}!</h2>
                                        <p style='margin-top: 0.75rem; color: #cbdcf0; max-width: 500px; font-size: 1.125rem; line-height: 1.6;'>Kualitas aplikasi ada di tanganmu. Mari bantu developer membangun aplikasi terbaik hari ini.</p>
                                        <div style='margin-top: 2rem;'>
                                            <a href='/tester/cari-misis' style='display: inline-block; padding: 0.75rem 2rem; background: white; color: #141c33; font-weight: 700; border-radius: 9999px; text-decoration: none; font-size: 0.95rem;'>Cari Misi Baru</a>
                                        </div>
                                    </div>
                                    <div style='position: absolute; right: -20px; top: -20px; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; filter: blur(40px);'></div>
                                    <div style='position: absolute; left: -20px; bottom: -20px; width: 180px; height: 180px; background: rgba(83,116,172,0.2); border-radius: 50%; filter: blur(50px);'></div>
                                </div>

                                <div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem;'>
                                    <div class='custom-card-stats'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z' /></svg>
                                        </div>
                                        <div><p class='stat-label'>Saldo Poin</p><p class='stat-value'>{$userPoints} pts</p></div>
                                    </div>

                                    <div class='custom-card-stats'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75' /></svg>
                                        </div>
                                        <div><p class='stat-label'>Misi Aktif</p><p class='stat-value'>{$misiAktif} Tugas</p></div>
                                    </div>

                                    <div class='custom-card-stats'>
                                        <div class='icon-bg'>
                                            <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width: 1.75rem; height: 1.75rem;'><path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z' /></svg>
                                        </div>
                                        <div><p class='stat-label'>Total Selesai</p><p class='stat-value'>{$misiSelesai} Aplikasi</p></div>
                                    </div>
                                </div>
                            </div>
                        ");
                    }
                }
            )

            ->discoverResources(in: app_path('Filament/Tester/Resources'), for: 'App\\Filament\\Tester\\Resources')
            ->discoverPages(in: app_path('Filament/Tester/Pages'), for: 'App\\Filament\\Tester\\Pages')
            ->pages([Pages\Dashboard::class])
            ->discoverWidgets(in: app_path('Filament/Tester/Widgets'), for: 'App\\Filament\\Tester\\Widgets')
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
            ->authMiddleware([Authenticate::class]);
    }

    public function boot(): void
    {
        app()->bind(
            \Filament\Http\Responses\Auth\Contracts\LogoutResponse::class,
            fn () => new class implements \Filament\Http\Responses\Auth\Contracts\LogoutResponse {
                public function toResponse($request) {
                    return redirect('/');
                }
            }
        );
    }
}