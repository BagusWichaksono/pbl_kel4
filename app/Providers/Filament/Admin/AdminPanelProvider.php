<?php

namespace App\Providers\Filament\Admin;

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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $chartWidgets = array_values(array_filter([
            \App\Filament\Admin\Widgets\AdminRevenueChart::class,
            \App\Filament\Admin\Widgets\AdminAppsChart::class,
            \App\Filament\Admin\Widgets\AdminUsersChart::class,
        ], fn (string $widget): bool => class_exists($widget)));

        return $panel
            ->spa()
            ->default()
            ->id('admin')
            ->path('admin')
            ->brandName('TesYuk!')
            ->brandLogo(new HtmlString(AppPalette::brandLogoHtml(asset(AppPalette::LOGO_ASSET))))
            ->brandLogoHeight('3rem')
            ->profile(\App\Filament\Admin\Pages\CustomEditProfile::class)
            ->darkMode(false)
            ->colors(AppPalette::filamentColors())
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                //NavigationGroup::make('Verifikasi')->collapsible(false),
                NavigationGroup::make('Manajemen Testing')->collapsible(false),
                NavigationGroup::make('Keuangan')->collapsible(false),
                NavigationGroup::make('Bantuan')->collapsible(false),
            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets($chartWidgets)
            ->databaseNotifications()
            ->authGuard('web')
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
                        border-color: rgba(var(--tesyuk-primary-rgb), 0.4) !important;
                    }

                    .custom-card-display {
                        background: white !important;
                        border: 1px dashed rgba(var(--tesyuk-accent-rgb), 0.15) !important;
                        border-radius: 20px !important;
                        padding: 1.5rem !important;
                        display: flex !important;
                        align-items: center !important;
                        gap: 1rem !important;
                        box-shadow: none !important;
                        opacity: 0.9 !important;
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
                        name: $user?->name ?? 'Admin',
                        email: $user?->email ?? 'admin@tesyuk.local',
                        metaLines: ['Admin Panel'],
                        profileUrl: filament()->getProfileUrl() ?? url('/admin'),
                        logoutUrl: filament()->getLogoutUrl(),
                        csrfToken: csrf_token(),
                        avatarUrl: $user?->getFilamentAvatarUrl(),
                        fallbackInitials: 'AD',
                    ));
                }
            )
            ->renderHook(
                PanelsRenderHook::PAGE_HEADER_WIDGETS_BEFORE,
                function () {
                    if (! request()->is('admin') && ! request()->is('admin/')) {
                        return null;
                    }

                    $user = Auth::user();
                    $userName = e($user?->name ?? 'Admin');

                    $hour = now()->timezone('Asia/Jakarta')->format('H');
                    $greeting = match (true) {
                        $hour >= 5 && $hour < 11 => 'Selamat Pagi',
                        $hour >= 11 && $hour < 15 => 'Selamat Siang',
                        $hour >= 15 && $hour < 18 => 'Selamat Sore',
                        default => 'Selamat Malam',
                    };

                    $logoAdminUrl = asset('assets/logo-admin.png');

                    $svg = fn (string $path): string => "
                        <svg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke-width='2' stroke='currentColor' style='width:1.75rem;height:1.75rem;'>
                            {$path}
                        </svg>
                    ";

                    $icons = [
                        'payment' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v9.75m19.5-9.75v9.75m0-9.75h-.75a.75.75 0 0 1-.75-.75V4.5m1.5 1.5v-.375c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V6m19.5 9.75v.375c0 .621-.504 1.125-1.125 1.125H3.375a1.125 1.125 0 0 1-1.125-1.125v-.375m19.5 0h-.75a.75.75 0 0 0-.75.75v.75m-16.5-1.5h.75a.75.75 0 0 1 .75.75v.75m6-10.5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Z' />"),
                        'app' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3' />"),
                        'revenue' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />"),
                        'check' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z' />"),
                        'users' => $svg("<path stroke-linecap='round' stroke-linejoin='round' d='M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z' />"),
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

                    $totalValidApps = $countTable('applications', 'payment_status', 'valid');
                    $pendingApps = $countTable('applications', 'payment_status', 'pending');
                    $pendingPayments = $countTable('transactions', 'status', 'pending');
                    $pendingWithdrawals = $countTable('withdrawals', 'status', 'pending');
                    $activeTesters = $countTable('users', 'role', 'tester');

                    $totalRevenue = $totalValidApps * 300000;
                    $totalRevenueFormatted = number_format($totalRevenue, 0, ',', '.');

                    $appResource = \App\Filament\Admin\Resources\AppResource::class;
                    $transactionResource = \App\Filament\Admin\Resources\TransactionResource::class;
                    $withdrawalResource = \App\Filament\Admin\Resources\PenukaranPoinResource::class;

                    $urlApps = class_exists($appResource) ? $appResource::getUrl('index') : '#';
                    $urlPayment = class_exists($transactionResource) ? $transactionResource::getUrl('index') : '#';
                    $urlWithdrawals = class_exists($withdrawalResource) ? $withdrawalResource::getUrl('index') : '#';

                    $latestApps = Schema::hasTable('applications')
                        ? DB::table('applications')->latest('created_at')->limit(5)->get()
                        : collect();

                    $appRows = '';

                    foreach ($latestApps as $app) {
                        $title = e($app->title ?? 'Aplikasi');
                        $status = strtolower($app->payment_status ?? $app->testing_status ?? 'pending');
                        $date = isset($app->created_at) ? \Carbon\Carbon::parse($app->created_at)->format('d M Y') : '-';

                        $badgeBg = '#f1f5f9';
                        $badgeColor = '#64748b';
                        
                        if (in_array($status, ['valid', 'approved', 'completed', 'diterima'])) {
                            $badgeBg = '#dcfce7';
                            $badgeColor = '#166534';
                        } elseif (in_array($status, ['invalid', 'rejected', 'ditolak'])) {
                            $badgeBg = '#fee2e2';
                            $badgeColor = '#991b1b';
                        } elseif (in_array($status, ['pending', 'menunggu'])) {
                            $badgeBg = '#fef3c7';
                            $badgeColor = '#92400e';
                        }

                        $appRows .= "
                            <div style='display:flex;align-items:center;justify-content:space-between;padding:0.9rem 0;border-bottom:1px solid #e2e8f0;gap:1rem;'>
                                <div>
                                    <div style='font-weight:800;color:#0f172a;'>{$title}</div>
                                    <div style='font-size:0.8rem;color:#64748b;margin-top:0.2rem;'>Diajukan {$date}</div>
                                </div>
                                <span style='font-size:0.75rem;font-weight:800;padding:0.35rem 0.7rem;border-radius:999px;background:{$badgeBg};color:{$badgeColor};'>{$status}</span>
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
                            <div style="background:linear-gradient(135deg, var(--tesyuk-ink) 0%, var(--tesyuk-ink) 68%, var(--tesyuk-primary) 88%, var(--tesyuk-accent) 100%);border-radius:24px;padding:3rem;color:white;position:relative;overflow:hidden;box-shadow:0 20px 40px -15px rgba(var(--tesyuk-ink-rgb),0.4);min-height:260px;">
                                <div style="position:relative;z-index:10;max-width:640px;">
                                    <h2 style="font-size:2.25rem;font-weight:800;margin:0;letter-spacing:-0.02em;">{$greeting}, {$userName}!</h2>
                                    <p style="margin-top:0.75rem;color:rgba(var(--tesyuk-secondary-rgb), 0.78);max-width:560px;font-size:1.125rem;line-height:1.6;">
                                        Pusat kendali TesYuk untuk memantau verifikasi aplikasi, validasi pembayaran, pencairan reward tester, dan pertumbuhan platform.
                                    </p>
                                </div>

                                <img src="{$logoAdminUrl}" alt="Admin Logo" style="position:absolute;right:2rem;bottom:-1.5rem;height:110%;z-index:5;object-fit:contain;pointer-events:none;">
                                    <div style="position:absolute;right:-20px;top:-20px;width:200px;height:200px;background:rgba(255,255,255,0.06);border-radius:50%;filter:blur(40px);z-index:1;"></div>
                                    <div style="position:absolute;right:120px;bottom:-50px;width:150px;height:150px;background:rgba(var(--tesyuk-accent-rgb),0.18);border-radius:50%;filter:blur(40px);z-index:1;"></div>
                                </div>

                                <h3 style="font-size:1.15rem;font-weight:800;margin:1.5rem 0 1rem;color:#0f172a;">Ringkasan Sistem</h3>
                                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1rem;margin-bottom:1.5rem;">
                                    <div class="custom-card-display" style="color:inherit;">
                                        <div class="icon-bg" style="background:#f1f5f9;color:#64748b;">{$icons['revenue']}</div>
                                        <div>
                                            <p class="stat-label">Total Pendapatan</p>
                                            <p class="stat-value">Rp {$totalRevenueFormatted}</p>
                                        </div>
                                    </div>

                                    <div class="custom-card-display" style="color:inherit;">
                                        <div class="icon-bg" style="background:#f1f5f9;color:#64748b;">{$icons['users']}</div>
                                        <div>
                                            <p class="stat-label">Tester Aktif</p>
                                            <p class="stat-value">{$activeTesters}</p>
                                        </div>
                                    </div>
                                </div>

                                <h3 style="font-size:1.15rem;font-weight:800;margin:0 0 1rem;color:#0f172a;">Aksi Cepat</h3>
                                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1rem;">
                                    <a href="{$urlPayment}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;position:relative;">
                                        <div class="icon-bg">{$icons['payment']}</div>
                                        <div>
                                            <p class="stat-label">Pembayaran Pending</p>
                                            <p class="stat-value">{$pendingPayments}</p>
                                        </div>
                                        <div style="position:absolute;right:1rem;opacity:0.4;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                        </div>
                                    </a>

                                    <a href="{$urlApps}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;position:relative;">
                                        <div class="icon-bg">{$icons['app']}</div>
                                        <div>
                                            <p class="stat-label">Aplikasi Pending</p>
                                            <p class="stat-value">{$pendingApps}</p>
                                        </div>
                                        <div style="position:absolute;right:1rem;opacity:0.4;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                        </div>
                                    </a>

                                    <a href="{$urlApps}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;position:relative;">
                                        <div class="icon-bg">{$icons['check']}</div>
                                        <div>
                                            <p class="stat-label">Aplikasi Valid</p>
                                            <p class="stat-value">{$totalValidApps}</p>
                                        </div>
                                        <div style="position:absolute;right:1rem;opacity:0.4;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                        </div>
                                    </a>

                                    <a href="{$urlWithdrawals}" class="custom-card-stats" style="text-decoration:none;color:inherit;cursor:pointer;position:relative;">
                                        <div class="icon-bg">{$icons['wallet']}</div>
                                        <div>
                                            <p class="stat-label">Pencairan Pending</p>
                                            <p class="stat-value">{$pendingWithdrawals}</p>
                                        </div>
                                        <div style="position:absolute;right:1rem;opacity:0.4;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1rem;height:1rem;"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                                        </div>
                                    </a>
                                </div>

                            <div class="fi-section" style="padding:1.5rem;">
                                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                                    <div>
                                        <h3 style="font-size:1.15rem;font-weight:800;margin:0;color:#0f172a;">Antrian Aplikasi Terbaru</h3>
                                        <p style="font-size:0.9rem;color:#64748b;margin:0.25rem 0 0;">Cek aplikasi terbaru yang masuk ke TesYuk.</p>
                                    </div>
                                    <a href="{$urlApps}" style="font-size:0.85rem;font-weight:800;color:var(--tesyuk-primary);text-decoration:none;">Lihat Semua</a>
                                </div>

                                {$appRows}
                            </div>

                            <div style="background:linear-gradient(to right, #eff6ff, #ffffff);border:1px solid #bfdbfe;border-left:4px solid #3b82f6;border-radius:16px;padding:1.25rem 1.5rem;display:flex;gap:1rem;align-items:flex-start;">
                                <div style="color:#3b82f6;flex-shrink:0;margin-top:2px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" style="width:1.5rem;height:1.5rem;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                    </svg>
                                </div>
                                <div>
                                    <div style="font-size:1rem;font-weight:800;color:#1e3a8a;">Informasi Grafik</div>
                                    <div style="font-size:.875rem;color:#1d4ed8;line-height:1.6;margin-top:.25rem;">
                                        Grafik di bawah menampilkan data 6 bulan terakhir. Anda dapat mengarahkan kursor ke titik atau batang grafik untuk melihat detail angka per bulan dengan lebih jelas.
                                    </div>
                                </div>
                            </div>
                        </div>
                        HTML);
                }
            )
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
