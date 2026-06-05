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
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
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
                PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (! request()->is('admin') && ! request()->is('admin/')) {
                        return null;
                    }

                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    $userName = e($user?->name ?? 'Admin');

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
                        'check' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />"),
                        'money' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v9.75m19.5-9.75v9.75m0-9.75h-.75a.75.75 0 0 1-.75-.75V4.5m1.5 1.5v-.375c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V6m19.5 9.75v.375c0 .621-.504 1.125-1.125 1.125H3.375a1.125 1.125 0 0 1-1.125-1.125v-.375m19.5 0h-.75a.75.75 0 0 0-.75.75v.75m-16.5-1.5h.75a.75.75 0 0 1 .75.75v.75m6-10.5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Z' />"),
                        'wallet' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M21 12.75V9.75A2.25 2.25 0 0 0 18.75 7.5H5.25A2.25 2.25 0 0 1 3 5.25m18 7.5h-4.5a2.25 2.25 0 0 0 0 4.5H21m0-4.5v6A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V5.25m0 0A2.25 2.25 0 0 1 5.25 3h12A2.25 2.25 0 0 1 19.5 5.25V7.5' />"),
                    ];

                    $countTable = function (string $table, ?string $column = null, ?string $value = null): int {
                        if (! Schema::hasTable($table)) {
                            return 0;
                        }

                        $query = DB::table($table);

                        if ($column && Schema::hasColumn($table, $column)) {
                            $query->where($column, $value);
                        }

                        return $query->count();
                    };

                    $pendingApps = $countTable('applications', 'payment_status', 'pending');
                    $validApps = $countTable('applications', 'payment_status', 'valid');
                    $pendingPayments = $countTable('transactions', 'status', 'pending');
                    $pendingWithdrawals = $countTable('withdrawals', 'status', 'pending');

                    $urlApps = \App\Filament\Admin\Resources\AppResource::getUrl('index');
                    $urlPayment = \App\Filament\Admin\Resources\TransactionResource::getUrl('index');

                    $withdrawalResource = \App\Filament\Admin\Resources\PenukaranPoinResource::class;
                    $urlWithdrawals = class_exists($withdrawalResource) ? $withdrawalResource::getUrl('index') : '#';

                    $latestApps = Schema::hasTable('applications')
                        ? DB::table('applications')->latest('created_at')->limit(5)->get()
                        : collect();

                    $appRows = '';

                    foreach ($latestApps as $app) {
                        $title = e($app->title ?? $app->name ?? 'Aplikasi');
                        $status = e($app->payment_status ?? $app->testing_status ?? 'pending');
                        $date = isset($app->created_at) ? \Carbon\Carbon::parse($app->created_at)->format('d M Y') : '-';

                        $appRows .= "
                            <div style='display:flex;align-items:center;justify-content:space-between;padding:0.9rem 0;border-bottom:1px solid #e2e8f0;gap:1rem;'>
                                <div>
                                    <div style='font-weight:800;color:#0f172a;'>{$title}</div>
                                    <div style='font-size:0.8rem;color:#64748b;margin-top:0.2rem;'>Diajukan {$date}</div>
                                </div>
                                <span style='font-size:0.75rem;font-weight:800;padding:0.35rem 0.7rem;border-radius:999px;background:#eff5fa;color:#2f456f;'>{$status}</span>
                            </div>
                        ";
                    }

                    if ($appRows === '') {
                        $appRows = "
                            <div style='padding:1.5rem;text-align:center;color:#64748b;border:1px dashed #cbd5e1;border-radius:18px;'>
                                Belum ada aplikasi terbaru yang perlu diverifikasi.
                            </div>
                        ";
                    }

                    return new HtmlString(<<<HTML
                        <div style="margin-bottom:2rem;display:flex;flex-direction:column;gap:1.5rem;">
                            <div style="background:linear-gradient(135deg,#141c33 0%,#2f456f 50%,#5374ac 100%);border-radius:24px;padding:3rem;color:white;position:relative;overflow:hidden;box-shadow:0 20px 40px -15px rgba(20,28,51,0.4);">
                                <div style="position:relative;z-index:10;">
                                    <h2 style="font-size:2.25rem;font-weight:800;margin:0;letter-spacing:-0.02em;">{$greeting}, {$userName}!</h2>
                                    <p style="margin-top:0.75rem;color:#cbdcf0;max-width:620px;font-size:1.125rem;line-height:1.6;">
                                        Pantau verifikasi aplikasi, pembayaran developer, pencairan reward tester, dan pesan bantuan dari satu tempat.
                                    </p>
                                </div>
                                <div style="position:absolute;right:-20px;top:-20px;width:200px;height:200px;background:rgba(255,255,255,0.06);border-radius:50%;filter:blur(40px);"></div>
                            </div>

                            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:1rem;">
                                <a href="{$urlApps}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
                                    <div class="icon-bg">{$icons['app']}</div>
                                    <div>
                                        <p class="stat-label">Aplikasi Pending</p>
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

                                <a href="{$urlPayment}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
                                    <div class="icon-bg">{$icons['money']}</div>
                                    <div>
                                        <p class="stat-label">Pembayaran Pending</p>
                                        <p class="stat-value">{$pendingPayments}</p>
                                    </div>
                                </a>

                                <a href="{$urlWithdrawals}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;">
                                    <div class="icon-bg">{$icons['wallet']}</div>
                                    <div>
                                        <p class="stat-label">Pencairan Pending</p>
                                        <p class="stat-value">{$pendingWithdrawals}</p>
                                    </div>
                                </a>
                            </div>

                            <div class="fi-section" style="padding:1.5rem;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                    <div>
                                        <h3 style="font-size:1.15rem;font-weight:800;margin:0;color:#0f172a;">Antrian Aplikasi Terbaru</h3>
                                        <p style="font-size:0.9rem;color:#64748b;margin:0.25rem 0 0;">Cek aplikasi terbaru yang masuk ke TesYuk.</p>
                                    </div>
                                    <a href="{$urlApps}" style="font-size:0.85rem;font-weight:800;color:#2f456f;text-decoration:none;">Lihat Semua</a>
                                </div>
                                {$appRows}
                            </div>
                        </div>
                    HTML);
                }
            );
    }
}