<?php

namespace App\Support;

use Filament\Support\Colors\Color;

final class AppPalette
{
    public const INK = '#1d1616';

    public const PRIMARY = '#8e1616';

    public const ACCENT = '#d84040';

    public const SECONDARY = '#eeeeee';

    public const LOGO_ASSET = 'assets/logo-new-transparent.png';

    public const BRAND_COLORS = [
        'ink' => self::INK,
        'primary' => self::PRIMARY,
        'accent' => self::ACCENT,
        'secondary' => self::SECONDARY,
    ];

    public static function filamentColors(): array
    {
        return [
            'primary' => Color::hex(self::PRIMARY),
            'secondary' => Color::hex(self::SECONDARY),
            'danger' => Color::Rose,
            'success' => Color::Emerald,
            'gray' => Color::Neutral,
        ];
    }

    public static function tailwindColors(): array
    {
        return [
            50 => self::SECONDARY,
            100 => self::SECONDARY,
            200 => self::SECONDARY,
            300 => self::ACCENT,
            400 => self::ACCENT,
            500 => self::ACCENT,
            600 => self::PRIMARY,
            700 => self::PRIMARY,
            800 => self::INK,
            900 => self::INK,
        ];
    }

    public static function brandNameHtml(): string
    {
        return '<span style="background: linear-gradient(135deg, var(--tesyuk-accent), var(--tesyuk-primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; letter-spacing: -0.02em;">TesYuk!</span>';
    }

    public static function brandLogoHtml(string $logoUrl): string
    {
        return <<<HTML
            <div class="tesyuk-brand-logo">
                <img src="{$logoUrl}" alt="TesYuk!" class="tesyuk-brand-logo-image">
                <span class="tesyuk-brand-logo-text">TesYuk!</span>
            </div>
        HTML;
    }

    public static function cssVariables(): string
    {
        $variables = [
            '--tesyuk-ink' => self::INK,
            '--tesyuk-primary' => self::PRIMARY,
            '--tesyuk-accent' => self::ACCENT,
            '--tesyuk-secondary' => self::SECONDARY,
            '--tesyuk-ink-rgb' => self::rgb(self::INK),
            '--tesyuk-primary-rgb' => self::rgb(self::PRIMARY),
            '--tesyuk-accent-rgb' => self::rgb(self::ACCENT),
            '--tesyuk-secondary-rgb' => self::rgb(self::SECONDARY),
        ];

        return implode("\n", array_map(
            fn (string $name, string $value): string => "{$name}: {$value};",
            array_keys($variables),
            $variables,
        ));
    }

    public static function cssVariablesStyle(): string
    {
        return '<style>:root {' . self::cssVariables() . '}</style>';
    }

    public static function heroGradient(): string
    {
        return 'linear-gradient(135deg,var(--tesyuk-ink) 0%,var(--tesyuk-ink) 68%,var(--tesyuk-primary) 88%,var(--tesyuk-accent) 100%)';
    }

    public static function progressGradient(): string
    {
        return 'linear-gradient(90deg,var(--tesyuk-accent) 0%,var(--tesyuk-primary) 100%)';
    }

    public static function sharedSidebarCss(): string
    {
        return <<<'HTML'
            <style>
                html {
                    color-scheme: light;
                }

                body,
                .fi-layout {
                    background:
                        radial-gradient(circle at top left, rgba(var(--tesyuk-accent-rgb), 0.08), transparent 340px),
                        linear-gradient(180deg, #fffafa 0%, #f8fafc 52%, #ffffff 100%) !important;
                }

                .fi-topbar {
                    background: transparent !important;
                    border-bottom: 0 !important;
                    box-shadow: none !important;
                    backdrop-filter: none !important;
                }

                .fi-sidebar {
                    background:
                        radial-gradient(circle at top left, rgba(var(--tesyuk-accent-rgb), 0.12), transparent 190px),
                        linear-gradient(180deg, var(--tesyuk-ink) 0%, #211717 76%, #2b1717 100%) !important;
                    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
                    box-shadow: 16px 0 42px -34px rgba(var(--tesyuk-ink-rgb), 0.9) !important;
                }

                .fi-sidebar-header {
                    background: transparent !important;
                    border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important;
                    box-shadow: none !important;
                    padding-left: 1rem !important;
                    padding-right: 1rem !important;
                }

                .fi-sidebar [data-slot="icon"],
                .fi-sidebar svg {
                    transition: none !important;
                }

                .fi-sidebar-nav {
                    gap: 0.9rem !important;
                    padding: 1rem 0.9rem 0.85rem !important;
                }

                .fi-sidebar-nav-groups {
                    gap: 0.95rem !important;
                }

                .fi-sidebar-group-label {
                    padding: 0 0.65rem !important;
                    color: rgba(238, 238, 238, 0.50) !important;
                    font-size: 0.68rem !important;
                    font-weight: 800 !important;
                    letter-spacing: 0.07em !important;
                    text-transform: uppercase !important;
                }

                .fi-sidebar-group-items {
                    gap: 0.22rem !important;
                }

                .fi-sidebar-item-button {
                    border-radius: 12px !important;
                    margin: 0 !important;
                    padding: 0.66rem 0.75rem !important;
                    border: 1px solid transparent !important;
                    background: transparent !important;
                    box-shadow: none !important;
                    transform: none !important;
                    transition: background-color 0.16s ease, border-color 0.16s ease !important;
                }

                .fi-sidebar-item-button:hover {
                    background: rgba(255, 255, 255, 0.07) !important;
                    border-color: rgba(255, 255, 255, 0.10) !important;
                    transform: none !important;
                }

                .fi-sidebar-item-button .fi-sidebar-item-label {
                    color: rgba(238, 238, 238, 0.78) !important;
                    font-weight: 650 !important;
                    line-height: 1.28 !important;
                    white-space: normal !important;
                }

                .fi-sidebar-item-button .fi-sidebar-item-icon {
                    color: rgba(238, 238, 238, 0.58) !important;
                }

                .fi-sidebar-item-active .fi-sidebar-item-button {
                    background: rgba(255, 255, 255, 0.10) !important;
                    border-color: rgba(var(--tesyuk-accent-rgb), 0.22) !important;
                    box-shadow: inset 3px 0 0 var(--tesyuk-primary) !important;
                }

                .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label {
                    color: #ffffff !important;
                    font-weight: 800 !important;
                }

                .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
                    color: #ffffff !important;
                }

                html:not(.dark) .fi-sidebar .fi-sidebar-item-button .fi-sidebar-item-label {
                    color: rgba(238, 238, 238, 0.78) !important;
                    font-weight: 650 !important;
                }

                html:not(.dark) .fi-sidebar .fi-sidebar-item-button .fi-sidebar-item-icon {
                    color: rgba(238, 238, 238, 0.58) !important;
                }

                html:not(.dark) .fi-sidebar .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button .fi-sidebar-item-label {
                    color: rgba(238, 238, 238, 0.78) !important;
                    font-weight: 650 !important;
                }

                html:not(.dark) .fi-sidebar .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button .fi-sidebar-item-icon {
                    color: rgba(238, 238, 238, 0.58) !important;
                }

                html:not(.dark) .fi-sidebar .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover {
                    background: rgba(255, 255, 255, 0.07) !important;
                    border-color: rgba(255, 255, 255, 0.10) !important;
                    box-shadow: none !important;
                    transform: none !important;
                }

                html:not(.dark) .fi-sidebar .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover .fi-sidebar-item-label,
                html:not(.dark) .fi-sidebar .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button:hover .fi-sidebar-item-icon {
                    color: #ffffff !important;
                }

                html:not(.dark) .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button {
                    background: rgba(255, 255, 255, 0.10) !important;
                    border-color: rgba(var(--tesyuk-accent-rgb), 0.22) !important;
                    box-shadow: inset 3px 0 0 var(--tesyuk-primary) !important;
                }

                html:not(.dark) .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label,
                html:not(.dark) .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
                    color: #ffffff !important;
                }

                html:not(.dark) .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label {
                    font-weight: 800 !important;
                }

                .dark .fi-sidebar .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button .fi-sidebar-item-label {
                    color: rgba(238, 238, 238, 0.78) !important;
                    font-weight: 650 !important;
                }

                .dark .fi-sidebar .fi-sidebar-item:not(.fi-sidebar-item-active) .fi-sidebar-item-button .fi-sidebar-item-icon {
                    color: rgba(238, 238, 238, 0.58) !important;
                }

                .dark .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-label,
                .dark .fi-sidebar .fi-sidebar-item-active .fi-sidebar-item-button .fi-sidebar-item-icon {
                    color: #ffffff !important;
                }

                .fi-sidebar-group-collapse-button {
                    display: none !important;
                }

                .fi-sidebar .fi-icon-btn {
                    color: rgba(238, 238, 238, 0.76) !important;
                }

                .fi-sidebar .fi-icon-btn:hover {
                    background: rgba(255, 255, 255, 0.08) !important;
                }

                .fi-topbar .fi-user-menu {
                    display: none !important;
                }

                .fi-logo,
                .tesyuk-brand-logo {
                    width: 100% !important;
                }

                .tesyuk-brand-logo {
                    display: flex;
                    align-items: center;
                    gap: 0.7rem;
                    color: #ffffff;
                }

                .tesyuk-brand-logo-image {
                    width: 46px !important;
                    height: 46px !important;
                    border: 0 !important;
                    border-radius: 0 !important;
                    object-fit: contain !important;
                    background: transparent !important;
                    box-shadow: none !important;
                    padding: 0 !important;
                }

                .tesyuk-brand-logo-text {
                    color: #ffffff;
                    font-size: 1.08rem;
                    font-weight: 850;
                    letter-spacing: -0.02em;
                }

                .tesyuk-sidebar-profile {
                    margin: 0 0.9rem 1rem !important;
                    border-radius: 16px !important;
                    border: 1px solid rgba(255, 255, 255, 0.10) !important;
                    background: rgba(255, 255, 255, 0.055) !important;
                    padding: 0.72rem !important;
                    color: #ffffff !important;
                    box-shadow: none !important;
                    transition: background-color 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease !important;
                }

                .tesyuk-sidebar-profile:hover {
                    background: rgba(255, 255, 255, 0.095) !important;
                    border-color: rgba(var(--tesyuk-accent-rgb), 0.28) !important;
                    box-shadow: 0 18px 34px -28px rgba(0, 0, 0, 0.9) !important;
                    transform: translateY(-1px) !important;
                }

                .tesyuk-sidebar-profile-link {
                    display: grid !important;
                    grid-template-columns: 42px minmax(0, 1fr) !important;
                    gap: 0.68rem !important;
                    align-items: center !important;
                    color: inherit !important;
                    text-decoration: none !important;
                }

                .tesyuk-sidebar-avatar,
                .tesyuk-sidebar-avatar-fallback {
                    width: 42px !important;
                    height: 42px !important;
                    border-radius: 999px !important;
                    border: 1px solid rgba(255, 255, 255, 0.62) !important;
                    background: var(--tesyuk-secondary) !important;
                    color: var(--tesyuk-ink) !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    font-size: 0.82rem !important;
                    font-weight: 900 !important;
                    object-fit: cover !important;
                    transition: border-color 0.18s ease, transform 0.18s ease !important;
                }

                .tesyuk-sidebar-profile-link:hover .tesyuk-sidebar-avatar,
                .tesyuk-sidebar-profile-link:hover .tesyuk-sidebar-avatar-fallback {
                    border-color: rgba(var(--tesyuk-accent-rgb), 0.85) !important;
                    transform: scale(1.04) !important;
                }

                .tesyuk-sidebar-profile-name {
                    color: #ffffff !important;
                    font-size: 0.9rem !important;
                    font-weight: 820 !important;
                    line-height: 1.25 !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                }

                .tesyuk-sidebar-profile-meta {
                    margin-top: 0.1rem !important;
                    color: rgba(238, 238, 238, 0.58) !important;
                    font-size: 0.7rem !important;
                    font-weight: 620 !important;
                    overflow: hidden !important;
                    text-overflow: ellipsis !important;
                    white-space: nowrap !important;
                }

                .tesyuk-sidebar-profile-actions {
                    display: grid !important;
                    grid-template-columns: 1fr 38px !important;
                    gap: 0.4rem !important;
                    margin-top: 0.68rem !important;
                }

                .tesyuk-sidebar-profile-button,
                .tesyuk-sidebar-logout {
                    border-radius: 999px !important;
                    border: 1px solid rgba(255, 255, 255, 0.11) !important;
                    background: rgba(255, 255, 255, 0.075) !important;
                    color: #ffffff !important;
                    min-height: 36px !important;
                    box-shadow: none !important;
                    transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.18s ease !important;
                }

                .tesyuk-sidebar-profile-button:hover,
                .tesyuk-sidebar-logout:hover {
                    background: var(--tesyuk-secondary) !important;
                    border-color: rgba(255, 255, 255, 0.42) !important;
                    color: var(--tesyuk-ink) !important;
                    transform: translateY(-1px) !important;
                }

                .tesyuk-sidebar-profile-button {
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    padding: 0.48rem 0.7rem !important;
                    font-size: 0.72rem !important;
                    font-weight: 800 !important;
                    text-align: center !important;
                    text-decoration: none !important;
                }

                .tesyuk-sidebar-logout {
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    width: 100% !important;
                    padding: 0 !important;
                    cursor: pointer !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-label,
                .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-label,
                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-brand-logo-text,
                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-profile-copy,
                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-profile-button {
                    display: none !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-profile {
                    width: 42px !important;
                    margin: 0 auto 0.85rem !important;
                    border: 0 !important;
                    background: transparent !important;
                    padding: 0 !important;
                    box-shadow: none !important;
                    transform: none !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-profile-link {
                    display: flex !important;
                    justify-content: center !important;
                    margin-bottom: 0.5rem !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-profile-actions {
                    display: flex !important;
                    justify-content: center !important;
                    margin-top: 0 !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-profile-actions form {
                    width: 42px !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-logout {
                    width: 42px !important;
                    height: 42px !important;
                    min-height: 42px !important;
                    background: rgba(255, 255, 255, 0.075) !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-nav {
                    padding-left: 0.65rem !important;
                    padding-right: 0.65rem !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-button {
                    justify-content: center !important;
                    padding-left: 0.55rem !important;
                    padding-right: 0.55rem !important;
                }
            </style>
            <script>
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                localStorage.setItem('tesyuk_theme', 'light');
            </script>
        HTML;
    }

    public static function sidebarProfileHtml(
        string $name,
        string $email,
        array $metaLines,
        string $profileUrl,
        string $logoutUrl,
        string $csrfToken,
        ?string $avatarUrl = null,
        string $fallbackInitials = 'TY',
    ): string {
        $name = trim($name) !== '' ? $name : 'Pengguna';
        $email = trim($email) !== '' ? $email : 'pengguna@tesyuk.local';
        $profileUrl = trim($profileUrl) !== '' ? $profileUrl : '#';

        $escapedName = e($name);
        $escapedEmail = e($email);
        $escapedProfileUrl = e($profileUrl);
        $escapedLogoutUrl = e($logoutUrl);
        $escapedCsrfToken = e($csrfToken);

        $metaHtml = implode('', array_map(
            fn (string $meta): string => '<div class="tesyuk-sidebar-profile-meta">' . e($meta) . '</div>',
            array_values(array_filter(
                array_map(fn (mixed $meta): string => trim((string) $meta), $metaLines),
                fn (string $meta): bool => $meta !== '',
            )),
        ));

        $initialSource = preg_replace('/[^A-Za-z0-9]/', '', $fallbackInitials) ?: preg_replace('/[^A-Za-z0-9]/', '', $name);
        $initials = strtoupper(substr($initialSource ?: 'TY', 0, 2));

        $avatar = $avatarUrl
            ? '<img src="' . e($avatarUrl) . '" alt="' . $escapedName . '" class="tesyuk-sidebar-avatar">'
            : '<div class="tesyuk-sidebar-avatar-fallback">' . e($initials) . '</div>';

        return <<<HTML
            <div class="tesyuk-sidebar-profile">
                <a href="{$escapedProfileUrl}" class="tesyuk-sidebar-profile-link">
                    {$avatar}
                    <div class="tesyuk-sidebar-profile-copy" style="min-width:0;">
                        <div class="tesyuk-sidebar-profile-name">{$escapedName}</div>
                        <div class="tesyuk-sidebar-profile-meta">{$escapedEmail}</div>
                        {$metaHtml}
                    </div>
                </a>

                <div class="tesyuk-sidebar-profile-actions">
                    <a href="{$escapedProfileUrl}" class="tesyuk-sidebar-profile-button">Profil</a>
                    <form method="post" action="{$escapedLogoutUrl}">
                        <input type="hidden" name="_token" value="{$escapedCsrfToken}">
                        <button type="submit" class="tesyuk-sidebar-logout" aria-label="Keluar">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor" style="width:1rem;height:1rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        HTML;
    }

    public static function rgb(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        return implode(', ', [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ]);
    }
}
