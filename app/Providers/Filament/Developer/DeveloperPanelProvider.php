<?php

namespace App\Providers\Filament\Developer;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DeveloperPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->spa()
            ->id('developer')
            ->path('developer')
            ->brandName(new HtmlString('<span style="background: linear-gradient(135deg, #5374ac, #2f456f); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; letter-spacing: -0.02em;">TesYuk!</span>'))
            ->profile(\App\Filament\Developer\Pages\CustomEditProfile::class)            
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
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('Aplikasi')->collapsible(false),
                NavigationGroup::make('Riwayat')->collapsible(false),
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
            ->discoverWidgets(in: app_path('Filament/Developer/Widgets'), for: 'App\\Filament\\Developer\\Widgets')
            ->widgets([])
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
                PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (! request()->is('developer') && ! request()->is('developer/')) {
                        return null;
                    }

                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    $userName = e($user?->name ?? 'Developer');
                    $userId = $user?->id;

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
                        'app' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M3.75 6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6Z' /><path stroke-linecap='round' stroke-linejoin='round' d='M8.25 8.25h7.5M8.25 12h7.5M8.25 15.75h4.5' />"),
                        'clock' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M12 6v6h4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />"),
                        'check' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />"),
                        'users' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z' />"),
                    ];

                    $urlAddApp = \App\Filament\Developer\Resources\AppResource::getUrl('create');
                    $urlApps = \App\Filament\Developer\Resources\AppResource::getUrl('index');

                    $reportResource = \App\Filament\Developer\Resources\TestingReportResource::class;
                    $urlReports = class_exists($reportResource) ? $reportResource::getUrl('index') : '#';

                    $appsQuery = null;

                    if (Schema::hasTable('applications')) {
                        $appsQuery = DB::table('applications');

                        if (Schema::hasColumn('applications', 'developer_id')) {
                            $appsQuery->where('developer_id', $userId);
                        }
                    }

                    $totalApps = $appsQuery ? (clone $appsQuery)->count() : 0;

                    $pendingApps = $appsQuery && Schema::hasColumn('applications', 'payment_status')
                        ? (clone $appsQuery)->where('payment_status', 'pending')->count()
                        : 0;

                    $validApps = $appsQuery && Schema::hasColumn('applications', 'payment_status')
                        ? (clone $appsQuery)->where('payment_status', 'valid')->count()
                        : 0;

                    $testerJoined = 0;

                    if (Schema::hasTable('application_testers') && Schema::hasTable('applications')) {
                        $testerJoinedQuery = DB::table('application_testers')
                            ->join('applications', 'application_testers.application_id', '=', 'applications.id');

                        if (Schema::hasColumn('applications', 'developer_id')) {
                            $testerJoinedQuery->where('applications.developer_id', $userId);
                        }

                        $testerJoined = $testerJoinedQuery->count();
                    }

                    $latestApps = $appsQuery
                        ? (clone $appsQuery)->latest('created_at')->limit(5)->get()
                        : collect();

                    $appRows = '';

                    foreach ($latestApps as $app) {
                        $title = e($app->title ?? $app->name ?? 'Aplikasi');
                        $status = e($app->payment_status ?? $app->testing_status ?? 'pending');
                        $target = $app->max_testers ?? 20;

                        $joined = 0;

                        if (Schema::hasTable('application_testers')) {
                            $joined = DB::table('application_testers')
                                ->where('application_id', $app->id)
                                ->count();
                        }

                        $percent = $target > 0 ? min(100, round(($joined / $target) * 100)) : 0;

                        $appRows .= "
                            <div style='padding:1rem 0;border-bottom:1px solid #e2e8f0;'>
                                <div style='display:flex;align-items:center;justify-content:space-between;gap:1rem;'>
                                    <div>
                                        <div style='font-weight:800;color:#0f172a;'>{$title}</div>
                                        <div style='font-size:0.8rem;color:#64748b;margin-top:0.2rem;'>Status: {$status}</div>
                                    </div>
                                    <div style='font-size:0.8rem;font-weight:800;color:#2f456f;'>{$joined}/{$target} tester</div>
                                </div>
                                <div style='height:8px;background:#e2e8f0;border-radius:999px;margin-top:0.8rem;overflow:hidden;'>
                                    <div style='height:100%;width:{$percent}%;background:#5374ac;border-radius:999px;'></div>
                                </div>
                            </div>
                        ";
                    }

                    if ($appRows === '') {
                        $appRows = "
                            <div style='padding:1.5rem;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:18px;'>
                                Belum ada aplikasi terdaftar. Daftarkan aplikasi pertamamu untuk mulai testing.
                            </div>
                        ";
                    }

                    return new HtmlString(<<<HTML
                        <div style="margin-bottom:2rem;display:flex;flex-direction:column;gap:1.5rem;">
                            <div style="background:linear-gradient(135deg,#141c33 0%,#2f456f 50%,#5374ac 100%);border-radius:24px;padding:3rem;color:white;position:relative;overflow:hidden;box-shadow:0 20px 40px -15px rgba(20,28,51,0.4);">
                                <div style="position:relative;z-index:10;">
                                    <h2 style="font-size:2.25rem;font-weight:800;margin:0;letter-spacing:-0.02em;">{$greeting}, {$userName}!</h2>
                                    <p style="margin-top:0.75rem;color:#cbdcf0;max-width:620px;font-size:1.125rem;line-height:1.6;">
                                        Kelola aplikasi, pantau jumlah tester, dan lihat progress testing aplikasi kamu.
                                    </p>
                                </div>
                                <div style="position:absolute;right:-20px;top:-20px;width:200px;height:200px;background:rgba(255,255,255,0.06);border-radius:50%;filter:blur(40px);"></div>
                            </div>

                            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:1rem;">
                                <a href="{$urlApps}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
                                    <div class="icon-bg">{$icons['app']}</div>
                                    <div>
                                        <p class="stat-label">Aplikasi Saya</p>
                                        <p class="stat-value">{$totalApps}</p>
                                    </div>
                                </a>

                                <a href="{$urlApps}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
                                    <div class="icon-bg">{$icons['clock']}</div>
                                    <div>
                                        <p class="stat-label">Menunggu Verifikasi</p>
                                        <p class="stat-value">{$pendingApps}</p>
                                    </div>
                                </a>

                                <a href="{$urlApps}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
                                    <div class="icon-bg">{$icons['check']}</div>
                                    <div>
                                        <p class="stat-label">Aplikasi Valid</p>
                                        <p class="stat-value">{$validApps}</p>
                                    </div>
                                </a>

                                <a href="{$urlReports}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
                                    <div class="icon-bg">{$icons['users']}</div>
                                    <div>
                                        <p class="stat-label">Tester Bergabung</p>
                                        <p class="stat-value">{$testerJoined}</p>
                                    </div>
                                </a>
                            </div>

                            <div style="display:grid;grid-template-columns:1.3fr 0.7fr;gap:1.5rem;">
                                <div class="fi-section" style="padding:1.5rem;">
                                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                        <div>
                                            <h3 style="font-size:1.15rem;font-weight:800;margin:0;color:#0f172a;">Progress Aplikasi</h3>
                                            <p style="font-size:0.9rem;color:#64748b;margin:0.25rem 0 0;">Pantau kuota tester dan status aplikasi terbaru.</p>
                                        </div>
                                        <a href="{$urlApps}" style="font-size:0.85rem;font-weight:800;color:#2f456f;text-decoration:none;">Lihat Semua</a>
                                    </div>
                                    {$appRows}
                                </div>

                                <div class="fi-section" style="padding:1.5rem;">
                                    <h3 style="font-size:1.15rem;font-weight:800;margin:0;color:#0f172a;">Mulai Testing</h3>
                                    <p style="font-size:0.9rem;color:#64748b;line-height:1.6;margin-top:0.5rem;">
                                        Daftarkan aplikasi yang sudah lulus review awal Google Play Console, lalu tunggu validasi admin.
                                    </p>
                                    <a href="{$urlAddApp}" style="display:inline-flex;margin-top:1rem;padding:0.8rem 1rem;border-radius:999px;background:#5374ac;color:white;font-weight:800;text-decoration:none;">
                                        Daftarkan Aplikasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    HTML);
                }
            );
    }
}