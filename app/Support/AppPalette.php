<?php

namespace App\Support;

use Filament\Support\Colors\Color;

final class AppPalette
{
    public const INK = '#1d1616';

    public const PRIMARY = '#8e1616';

    public const ACCENT = '#d84040';

    public const SECONDARY = '#eeeeee';

    public const OBSIDIAN = '#000000';

    public const MAROON = '#3d0000';

    public const CRIMSON = '#950101';

    public const SIGNAL_RED = '#ff0000';

    public const NAVY = '#2b2e4a';

    public const CORAL = '#e84545';

    public const BERRY = '#903749';

    public const PLUM = '#53354a';

    public const LOGO_ASSET = 'assets/logo-new-transparent.png';

    public const BRAND_COLORS = [
        'ink' => self::INK,
        'primary' => self::PRIMARY,
        'accent' => self::ACCENT,
        'secondary' => self::SECONDARY,
        'obsidian' => self::OBSIDIAN,
        'maroon' => self::MAROON,
        'crimson' => self::CRIMSON,
        'signal_red' => self::SIGNAL_RED,
        'navy' => self::NAVY,
        'coral' => self::CORAL,
        'berry' => self::BERRY,
        'plum' => self::PLUM,
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
            '--tesyuk-obsidian' => self::OBSIDIAN,
            '--tesyuk-maroon' => self::MAROON,
            '--tesyuk-crimson' => self::CRIMSON,
            '--tesyuk-signal-red' => self::SIGNAL_RED,
            '--tesyuk-navy' => self::NAVY,
            '--tesyuk-coral' => self::CORAL,
            '--tesyuk-berry' => self::BERRY,
            '--tesyuk-plum' => self::PLUM,
            '--tesyuk-ink-rgb' => self::rgb(self::INK),
            '--tesyuk-primary-rgb' => self::rgb(self::PRIMARY),
            '--tesyuk-accent-rgb' => self::rgb(self::ACCENT),
            '--tesyuk-secondary-rgb' => self::rgb(self::SECONDARY),
            '--tesyuk-obsidian-rgb' => self::rgb(self::OBSIDIAN),
            '--tesyuk-maroon-rgb' => self::rgb(self::MAROON),
            '--tesyuk-crimson-rgb' => self::rgb(self::CRIMSON),
            '--tesyuk-signal-red-rgb' => self::rgb(self::SIGNAL_RED),
            '--tesyuk-navy-rgb' => self::rgb(self::NAVY),
            '--tesyuk-coral-rgb' => self::rgb(self::CORAL),
            '--tesyuk-berry-rgb' => self::rgb(self::BERRY),
            '--tesyuk-plum-rgb' => self::rgb(self::PLUM),
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
                    z-index: 35 !important;
                    overflow: visible !important;
                    background: rgba(255, 255, 255, 0.62) !important;
                    border-bottom: 1px solid rgba(var(--tesyuk-primary-rgb), 0.10) !important;
                    box-shadow: 0 18px 44px -38px rgba(var(--tesyuk-ink-rgb), 0.82) !important;
                    backdrop-filter: blur(18px) saturate(155%) !important;
                    -webkit-backdrop-filter: blur(18px) saturate(155%) !important;
                }

                .fi-topbar nav {
                    background:
                        linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.48)),
                        radial-gradient(circle at right, rgba(var(--tesyuk-accent-rgb), 0.10), transparent 340px) !important;
                    border-bottom: 1px solid rgba(var(--tesyuk-primary-rgb), 0.08) !important;
                    box-shadow: none !important;
                    --tw-ring-shadow: 0 0 #0000 !important;
                }

                .fi-topbar .fi-icon-btn {
                    position: relative !important;
                    width: 42px !important;
                    height: 42px !important;
                    min-width: 42px !important;
                    border-radius: 999px !important;
                    border: 1px solid rgba(var(--tesyuk-primary-rgb), 0.15) !important;
                    background: rgba(255, 255, 255, 0.82) !important;
                    color: var(--tesyuk-ink) !important;
                    box-shadow: 0 14px 32px -26px rgba(var(--tesyuk-ink-rgb), 0.72) !important;
                }

                .fi-topbar .fi-icon-btn:hover,
                .fi-topbar .fi-icon-btn:focus-visible {
                    background: var(--tesyuk-ink) !important;
                    border-color: rgba(var(--tesyuk-ink-rgb), 0.24) !important;
                    color: #ffffff !important;
                    transform: translateY(-1px) !important;
                }

                .fi-topbar .fi-icon-btn-icon {
                    color: currentColor !important;
                    stroke-width: 2.1 !important;
                }

                .fi-topbar .fi-badge {
                    background: var(--tesyuk-accent) !important;
                    color: #ffffff !important;
                    border: 2px solid #ffffff !important;
                    box-shadow: 0 8px 18px -12px rgba(var(--tesyuk-accent-rgb), 0.9) !important;
                }

                .fi-topbar .fi-icon-btn[data-topbar-tooltip]::after {
                    content: attr(data-topbar-tooltip);
                    position: absolute;
                    right: 0;
                    top: calc(100% + 0.55rem);
                    z-index: 9998;
                    min-width: max-content;
                    transform: translateY(-4px) scale(0.96);
                    transform-origin: top right;
                    border-radius: 999px;
                    background: #050505;
                    color: #ffffff;
                    padding: 0.58rem 0.9rem;
                    font-size: 0.75rem;
                    font-weight: 760;
                    letter-spacing: 0;
                    line-height: 1;
                    opacity: 0;
                    pointer-events: none;
                    box-shadow: 0 16px 36px -20px rgba(0, 0, 0, 0.9);
                    transition: opacity 0.16s ease, transform 0.16s ease;
                }

                .fi-topbar .fi-icon-btn[data-topbar-tooltip]:hover::after,
                .fi-topbar .fi-icon-btn[data-topbar-tooltip]:focus-visible::after {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }

                .fi-topbar .fi-topbar-database-notifications-btn,
                .fi-topbar .fi-topbar-database-notifications-btn:hover,
                .fi-topbar .fi-topbar-database-notifications-btn:focus,
                .fi-topbar .fi-topbar-database-notifications-btn:focus-visible,
                .fi-topbar .fi-topbar-database-notifications-btn:active,
                .fi-topbar .fi-topbar-database-notifications-btn[aria-expanded="true"] {
                    background: rgba(255, 255, 255, 0.82) !important;
                    border-color: rgba(var(--tesyuk-primary-rgb), 0.15) !important;
                    color: var(--tesyuk-ink) !important;
                    transform: none !important;
                }

                .fi-modal[data-fi-modal-id="database-notifications"] .fi-modal-close-overlay {
                    background: transparent !important;
                    backdrop-filter: none !important;
                    -webkit-backdrop-filter: none !important;
                }

                .fi-modal[data-fi-modal-id="database-notifications"] .fi-modal-window {
                    height: min(52dvh, 540px) !important;
                    min-height: min(340px, 52dvh) !important;
                    max-height: min(52dvh, 540px) !important;
                    margin-block: auto !important;
                    border-radius: 24px 0 0 24px !important;
                    overflow: hidden !important;
                    background: rgba(255, 255, 255, 0.96) !important;
                    box-shadow: 0 26px 70px -34px rgba(var(--tesyuk-ink-rgb), 0.86) !important;
                }

                .fi-modal[data-fi-modal-id="database-notifications"] .fi-modal-header {
                    flex: 0 0 auto !important;
                    background:
                        linear-gradient(135deg, rgba(255, 255, 255, 0.94), rgba(255, 255, 255, 0.78)),
                        radial-gradient(circle at top right, rgba(var(--tesyuk-accent-rgb), 0.10), transparent 230px) !important;
                    border-bottom: 1px solid rgba(var(--tesyuk-primary-rgb), 0.10) !important;
                }

                .fi-modal[data-fi-modal-id="database-notifications"] .fi-modal-content {
                    flex: 1 1 auto !important;
                    min-height: 0 !important;
                    overflow-y: auto !important;
                    padding-top: 0 !important;
                    padding-bottom: 0 !important;
                }

                .fi-modal[data-fi-modal-id="database-notifications"] .fi-modal-content > div {
                    max-height: none !important;
                    overflow: visible !important;
                }

                @media (max-width: 640px) {
                    .fi-modal[data-fi-modal-id="database-notifications"] .fi-modal-window {
                        width: calc(100vw - 1.5rem) !important;
                        height: min(56dvh, 520px) !important;
                        min-height: min(320px, 56dvh) !important;
                        max-height: min(56dvh, 520px) !important;
                        margin: auto 0.75rem auto auto !important;
                        border-radius: 22px !important;
                    }
                }

                @keyframes tesyuk-page-enter {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .fi-main-ctn,
                .fi-main,
                .fi-page,
                .fi-topbar,
                .fi-sidebar,
                .fi-section,
                .fi-ta-ctn,
                .fi-modal-window,
                .fi-dropdown-panel,
                .fi-wi,
                .fi-btn,
                .fi-input-wrp {
                    transition:
                        background-color 0.22s ease,
                        border-color 0.22s ease,
                        box-shadow 0.22s ease,
                        color 0.18s ease,
                        opacity 0.22s ease,
                        transform 0.22s ease !important;
                }

                .fi-main {
                    padding-top: 0.95rem !important;
                }

                .fi-page > section {
                    padding-top: 2.75rem !important;
                    animation: tesyuk-page-enter 0.28s ease both;
                }

                .fi-header {
                    margin-bottom: 0.25rem !important;
                }

                .fi-header-heading {
                    line-height: 1.2 !important;
                }

                .fi-section,
                .fi-ta-ctn,
                .fi-modal-window,
                .fi-dropdown-panel {
                    border-radius: 22px !important;
                    overflow: hidden !important;
                }

                .fi-input-wrp,
                .fi-select-input,
                .fi-textarea,
                .fi-btn,
                .fi-tabs,
                .fi-pagination,
                .fi-fo-file-upload .filepond--panel-root {
                    border-radius: 14px !important;
                }

                .fi-sidebar {
                    z-index: 90 !important;
                    background:
                        radial-gradient(circle at top left, rgba(var(--tesyuk-accent-rgb), 0.12), transparent 190px),
                        linear-gradient(180deg, var(--tesyuk-ink) 0%, #211717 76%, #2b1717 100%) !important;
                    border-right: 1px solid rgba(255, 255, 255, 0.08) !important;
                    box-shadow: 16px 0 42px -34px rgba(var(--tesyuk-ink-rgb), 0.9) !important;
                    transition:
                        width 0.34s cubic-bezier(0.22, 1, 0.36, 1),
                        max-width 0.34s cubic-bezier(0.22, 1, 0.36, 1),
                        transform 0.34s cubic-bezier(0.22, 1, 0.36, 1),
                        box-shadow 0.24s ease,
                        background-color 0.24s ease !important;
                }

                .fi-main-sidebar .overflow-x-clip {
                    overflow: visible !important;
                }

                .fi-main-sidebar {
                    z-index: 90 !important;
                    overflow: visible !important;
                }

                .fi-sidebar-header {
                    position: relative !important;
                    z-index: 100 !important;
                    overflow: visible !important;
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
                    transition: padding 0.3s cubic-bezier(0.22, 1, 0.36, 1), gap 0.3s ease !important;
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
                    transition: opacity 0.2s ease, transform 0.24s cubic-bezier(0.22, 1, 0.36, 1), max-width 0.28s ease !important;
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

                .fi-sidebar-header .fi-icon-btn {
                    position: relative !important;
                    z-index: 110 !important;
                    width: 42px !important;
                    height: 42px !important;
                    min-width: 42px !important;
                    border-radius: 999px !important;
                    color: rgba(255, 255, 255, 0.88) !important;
                    background: rgba(255, 255, 255, 0.055) !important;
                    border: 1px solid rgba(255, 255, 255, 0.10) !important;
                    box-shadow: none !important;
                    transition:
                        background-color 0.18s ease,
                        border-color 0.18s ease,
                        color 0.18s ease,
                        transform 0.18s ease !important;
                }

                .fi-sidebar-header .fi-icon-btn:hover,
                .fi-sidebar-header .fi-icon-btn:focus-visible {
                    background: var(--tesyuk-secondary) !important;
                    border-color: rgba(255, 255, 255, 0.34) !important;
                    color: var(--tesyuk-ink) !important;
                    transform: translateY(-1px) !important;
                }

                .fi-sidebar-header .fi-icon-btn-icon {
                    width: 1.3rem !important;
                    height: 1.3rem !important;
                }

                .fi-sidebar-header .fi-icon-btn-icon svg {
                    width: 100% !important;
                    height: 100% !important;
                    display: block !important;
                }

                .fi-sidebar-header .fi-icon-btn[data-sidebar-tooltip]::after {
                    content: attr(data-sidebar-tooltip);
                    position: absolute;
                    left: calc(100% + 0.72rem);
                    top: 50%;
                    z-index: 9999;
                    min-width: max-content;
                    transform: translate(0, -50%) scale(0.96);
                    transform-origin: left center;
                    border-radius: 999px;
                    background: #050505;
                    color: #ffffff;
                    padding: 0.62rem 1rem;
                    font-size: 0.78rem;
                    font-weight: 700;
                    letter-spacing: 0;
                    line-height: 1;
                    opacity: 0;
                    pointer-events: none;
                    box-shadow: 0 16px 36px -20px rgba(0, 0, 0, 0.9);
                    transition: opacity 0.16s ease, transform 0.16s ease;
                }

                .fi-sidebar-header .fi-icon-btn[data-sidebar-tooltip]::before {
                    content: "";
                    position: absolute;
                    left: calc(100% + 0.42rem);
                    top: 50%;
                    z-index: 9999;
                    width: 0.7rem;
                    height: 0.7rem;
                    background: #050505;
                    transform: translateY(-50%) rotate(45deg) scale(0.88);
                    opacity: 0;
                    pointer-events: none;
                    transition: opacity 0.16s ease, transform 0.16s ease;
                }

                .fi-sidebar-header .fi-icon-btn[data-sidebar-tooltip]:hover::after,
                .fi-sidebar-header .fi-icon-btn[data-sidebar-tooltip]:focus-visible::after {
                    opacity: 1;
                    transform: translate(0, -50%) scale(1);
                }

                .fi-sidebar-header .fi-icon-btn[data-sidebar-tooltip]:hover::before,
                .fi-sidebar-header .fi-icon-btn[data-sidebar-tooltip]:focus-visible::before {
                    opacity: 1;
                    transform: translateY(-50%) rotate(45deg) scale(1);
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
                    transition: gap 0.28s ease, transform 0.28s ease, opacity 0.2s ease;
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
                    transition: opacity 0.18s ease, transform 0.22s ease;
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

                .tesyuk-sidebar-profile-summary,
                .tesyuk-sidebar-profile-link {
                    display: grid !important;
                    grid-template-columns: 42px minmax(0, 1fr) !important;
                    gap: 0.68rem !important;
                    align-items: center !important;
                    color: inherit !important;
                    text-decoration: none !important;
                    cursor: default !important;
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

                .tesyuk-sidebar-profile:hover .tesyuk-sidebar-avatar,
                .tesyuk-sidebar-profile:hover .tesyuk-sidebar-avatar-fallback {
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

                .tesyuk-sidebar-profile-copy {
                    transition: opacity 0.2s ease, transform 0.24s cubic-bezier(0.22, 1, 0.36, 1), max-width 0.28s ease !important;
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
                    grid-template-columns: minmax(0, 1fr) 46px !important;
                    gap: 0.5rem !important;
                    margin-top: 0.68rem !important;
                }

                .tesyuk-sidebar-logout-form {
                    position: relative !important;
                    width: 100% !important;
                }

                .tesyuk-sidebar-profile-button,
                .tesyuk-sidebar-logout {
                    border-radius: 999px !important;
                    border: 1px solid rgba(255, 255, 255, 0.11) !important;
                    color: #ffffff !important;
                    min-height: 40px !important;
                    box-shadow: none !important;
                    transition: background-color 0.18s ease, border-color 0.18s ease, color 0.18s ease, transform 0.18s ease !important;
                }

                .tesyuk-sidebar-profile-button {
                    background: rgba(255, 255, 255, 0.075) !important;
                }

                .tesyuk-sidebar-profile-button:hover {
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
                    min-width: 46px !important;
                    height: 40px !important;
                    padding: 0 !important;
                    cursor: pointer !important;
                    background: rgba(var(--tesyuk-accent-rgb), 0.20) !important;
                    border-color: rgba(var(--tesyuk-accent-rgb), 0.38) !important;
                    color: #ffffff !important;
                }

                .tesyuk-sidebar-logout:hover {
                    background: var(--tesyuk-accent) !important;
                    border-color: rgba(255, 255, 255, 0.38) !important;
                    color: #ffffff !important;
                    transform: translateY(-1px) !important;
                }

                .tesyuk-sidebar-logout-tooltip {
                    position: absolute !important;
                    right: 0 !important;
                    bottom: calc(100% + 0.48rem) !important;
                    z-index: 20 !important;
                    border-radius: 999px !important;
                    background: var(--tesyuk-secondary) !important;
                    color: var(--tesyuk-ink) !important;
                    padding: 0.32rem 0.58rem !important;
                    font-size: 0.66rem !important;
                    font-weight: 850 !important;
                    line-height: 1 !important;
                    white-space: nowrap !important;
                    box-shadow: 0 12px 28px -18px rgba(0, 0, 0, 0.82) !important;
                    opacity: 0 !important;
                    transform: translateY(4px) !important;
                    pointer-events: none !important;
                    transition: opacity 0.16s ease, transform 0.16s ease !important;
                }

                .tesyuk-sidebar-logout-form:hover .tesyuk-sidebar-logout-tooltip,
                .tesyuk-sidebar-logout:focus-visible + .tesyuk-sidebar-logout-tooltip {
                    opacity: 1 !important;
                    transform: translateY(0) !important;
                }

                .fi-page.fi-resource-edit-profile,
                .fi-page.fi-page-auth-edit-profile {
                    --tesyuk-profile-card-border: rgba(var(--tesyuk-accent-rgb), 0.12);
                }

                .tesyuk-profile-section {
                    border-radius: 26px !important;
                    border: 1px solid var(--tesyuk-profile-card-border, rgba(var(--tesyuk-accent-rgb), 0.12)) !important;
                    background:
                        radial-gradient(circle at top right, rgba(var(--tesyuk-accent-rgb), 0.06), transparent 280px),
                        #ffffff !important;
                    box-shadow: 0 18px 48px -42px rgba(var(--tesyuk-ink-rgb), 0.45) !important;
                    overflow: hidden !important;
                }

                .tesyuk-profile-section .fi-section-header {
                    padding-top: 1.35rem !important;
                    padding-bottom: 1.1rem !important;
                }

                .tesyuk-profile-section .fi-section-header-heading {
                    color: var(--tesyuk-ink) !important;
                    font-weight: 900 !important;
                }

                .tesyuk-profile-section .fi-section-header-description {
                    color: rgba(var(--tesyuk-ink-rgb), 0.58) !important;
                    font-weight: 500 !important;
                }

                .tesyuk-profile-avatar-section .fi-section-content {
                    padding-top: 1.8rem !important;
                }

                .tesyuk-profile-avatar-upload {
                    max-width: 16rem !important;
                    margin-inline: auto !important;
                    text-align: center !important;
                }

                .tesyuk-profile-avatar-upload .fi-fo-field-wrp-label {
                    justify-content: center !important;
                    text-align: center !important;
                }

                .tesyuk-profile-avatar-upload .fi-fo-field-wrp-error-message,
                .tesyuk-profile-avatar-upload .fi-fo-field-wrp-helper-text {
                    text-align: center !important;
                }

                .tesyuk-profile-avatar-upload .filepond--root {
                    width: 10.75rem !important;
                    max-width: 10.75rem !important;
                    margin-inline: auto !important;
                }

                .tesyuk-profile-avatar-upload .filepond--panel-root {
                    border: 1px solid rgba(var(--tesyuk-primary-rgb), 0.16) !important;
                    background:
                        radial-gradient(circle at top, rgba(var(--tesyuk-accent-rgb), 0.10), transparent 70%),
                        #ffffff !important;
                    box-shadow: 0 22px 42px -34px rgba(var(--tesyuk-ink-rgb), 0.58) !important;
                }

                .tesyuk-profile-avatar-upload .filepond--drop-label {
                    color: rgba(var(--tesyuk-ink-rgb), 0.68) !important;
                    font-weight: 650 !important;
                }

                .tesyuk-profile-avatar-upload .filepond--label-action {
                    color: var(--tesyuk-primary) !important;
                    font-weight: 850 !important;
                    text-decoration: none !important;
                }

                .fi-page .fi-form-actions {
                    margin-top: 1.25rem !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-label,
                .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-item-label,
                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-brand-logo-text,
                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-profile-copy {
                    max-width: 0 !important;
                    opacity: 0 !important;
                    overflow: hidden !important;
                    pointer-events: none !important;
                    transform: translateX(-10px) !important;
                    white-space: nowrap !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .fi-sidebar-group-label {
                    height: 0 !important;
                    padding: 0 !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-brand-logo {
                    gap: 0 !important;
                    transform: translateX(1px) !important;
                }

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

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-profile-summary,
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
                    width: 46px !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-logout-tooltip {
                    right: auto !important;
                    left: 50% !important;
                    bottom: calc(100% + 0.55rem) !important;
                    transform: translate(-50%, 4px) !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-logout-form:hover .tesyuk-sidebar-logout-tooltip,
                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-logout:focus-visible + .tesyuk-sidebar-logout-tooltip {
                    transform: translate(-50%, 0) !important;
                }

                .fi-sidebar:not(.fi-sidebar-open) .tesyuk-sidebar-logout {
                    width: 46px !important;
                    height: 46px !important;
                    min-height: 46px !important;
                    background: rgba(var(--tesyuk-accent-rgb), 0.22) !important;
                    border-color: rgba(var(--tesyuk-accent-rgb), 0.38) !important;
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

                .tesyuk-chat-widget {
                    position: fixed !important;
                    right: 1.65rem !important;
                    bottom: 1.55rem !important;
                    z-index: 9997 !important;
                    pointer-events: none !important;
                }

                [x-cloak] {
                    display: none !important;
                }

                .tesyuk-chat-button {
                    position: relative !important;
                    width: 64px !important;
                    height: 64px !important;
                    border-radius: 999px !important;
                    border: 1px solid rgba(var(--tesyuk-primary-rgb), 0.18) !important;
                    background:
                        linear-gradient(135deg, rgba(255, 255, 255, 0.94), rgba(255, 255, 255, 0.72)),
                        radial-gradient(circle at top, rgba(var(--tesyuk-accent-rgb), 0.10), transparent 72%) !important;
                    box-shadow: 0 18px 38px -20px rgba(var(--tesyuk-ink-rgb), 0.78) !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    cursor: pointer !important;
                    pointer-events: auto !important;
                    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease !important;
                }

                .tesyuk-chat-button:hover,
                .tesyuk-chat-button:focus-visible {
                    transform: translateY(-3px) scale(1.04) !important;
                    border-color: rgba(var(--tesyuk-accent-rgb), 0.36) !important;
                    box-shadow: 0 24px 46px -22px rgba(var(--tesyuk-ink-rgb), 0.82) !important;
                }

                .tesyuk-chat-button-logo {
                    width: 42px !important;
                    height: 42px !important;
                    object-fit: contain !important;
                    display: block !important;
                }

                .tesyuk-chat-tooltip {
                    position: absolute !important;
                    right: calc(100% + 0.75rem) !important;
                    top: 50% !important;
                    transform: translate(8px, -50%) scale(0.96) !important;
                    transform-origin: right center !important;
                    min-width: max-content !important;
                    border-radius: 999px !important;
                    background: var(--tesyuk-ink) !important;
                    color: #ffffff !important;
                    padding: 0.72rem 1rem !important;
                    font-size: 0.78rem !important;
                    font-weight: 800 !important;
                    line-height: 1 !important;
                    opacity: 0 !important;
                    pointer-events: none !important;
                    box-shadow: 0 16px 36px -20px rgba(0, 0, 0, 0.9) !important;
                    transition: opacity 0.16s ease, transform 0.16s ease !important;
                }

                .tesyuk-chat-button:hover .tesyuk-chat-tooltip,
                .tesyuk-chat-button:focus-visible .tesyuk-chat-tooltip {
                    opacity: 1 !important;
                    transform: translate(0, -50%) scale(1) !important;
                }

                .tesyuk-chat-badge {
                    position: absolute !important;
                    top: -0.15rem !important;
                    right: -0.15rem !important;
                    min-width: 1.2rem !important;
                    height: 1.2rem !important;
                    border-radius: 999px !important;
                    background: var(--tesyuk-accent) !important;
                    color: #ffffff !important;
                    border: 2px solid #ffffff !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    font-size: 0.62rem !important;
                    font-weight: 900 !important;
                    line-height: 1 !important;
                }

                .tesyuk-chat-panel {
                    position: fixed !important;
                    right: 1.65rem !important;
                    bottom: 6.15rem !important;
                    width: min(420px, calc(100vw - 2rem)) !important;
                    height: min(650px, calc(100vh - 7.5rem)) !important;
                    border-radius: 28px !important;
                    overflow: hidden !important;
                    border: 1px solid rgba(var(--tesyuk-primary-rgb), 0.13) !important;
                    background: rgba(255, 255, 255, 0.94) !important;
                    box-shadow: 0 28px 80px -32px rgba(var(--tesyuk-ink-rgb), 0.76) !important;
                    backdrop-filter: blur(18px) saturate(145%) !important;
                    -webkit-backdrop-filter: blur(18px) saturate(145%) !important;
                    display: flex;
                    flex-direction: column !important;
                    pointer-events: auto !important;
                }

                .tesyuk-chat-enter {
                    transition: opacity 0.2s ease, transform 0.2s ease !important;
                }

                .tesyuk-chat-enter-start,
                .tesyuk-chat-leave-end {
                    opacity: 0 !important;
                    transform: translateY(16px) scale(0.96) !important;
                }

                .tesyuk-chat-enter-end,
                .tesyuk-chat-leave-start {
                    opacity: 1 !important;
                    transform: translateY(0) scale(1) !important;
                }

                .tesyuk-chat-leave {
                    transition: opacity 0.16s ease, transform 0.16s ease !important;
                }

                .tesyuk-chat-header {
                    padding: 1rem !important;
                    display: flex !important;
                    align-items: center !important;
                    gap: 0.75rem !important;
                    background: linear-gradient(135deg, var(--tesyuk-ink), #2b1717 78%, var(--tesyuk-primary)) !important;
                    color: #ffffff !important;
                }

                .tesyuk-chat-header-logo {
                    width: 46px !important;
                    height: 46px !important;
                    border-radius: 16px !important;
                    background: rgba(255, 255, 255, 0.92) !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    flex: 0 0 auto !important;
                }

                .tesyuk-chat-header-logo img {
                    width: 34px !important;
                    height: 34px !important;
                    object-fit: contain !important;
                }

                .tesyuk-chat-header h2 {
                    margin: 0 !important;
                    font-size: 1rem !important;
                    font-weight: 900 !important;
                    line-height: 1.2 !important;
                }

                .tesyuk-chat-header p {
                    margin: 0.15rem 0 0 !important;
                    color: rgba(238, 238, 238, 0.68) !important;
                    font-size: 0.75rem !important;
                    font-weight: 650 !important;
                }

                .tesyuk-chat-close {
                    margin-left: auto !important;
                    width: 36px !important;
                    height: 36px !important;
                    border-radius: 999px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    background: rgba(255, 255, 255, 0.08) !important;
                    color: #ffffff !important;
                    border: 1px solid rgba(255, 255, 255, 0.12) !important;
                    transition: background-color 0.16s ease, transform 0.16s ease !important;
                }

                .tesyuk-chat-close:hover,
                .tesyuk-chat-close:focus-visible {
                    background: rgba(255, 255, 255, 0.16) !important;
                    transform: rotate(4deg) !important;
                }

                .tesyuk-chat-close svg {
                    width: 1.1rem !important;
                    height: 1.1rem !important;
                }

                .tesyuk-chat-note {
                    margin: 0 !important;
                    padding: 0.78rem 1rem !important;
                    background: rgba(var(--tesyuk-coral-rgb), 0.10) !important;
                    border-bottom: 1px solid rgba(var(--tesyuk-coral-rgb), 0.22) !important;
                    color: var(--tesyuk-primary) !important;
                    font-size: 0.72rem !important;
                    font-weight: 760 !important;
                    line-height: 1.55 !important;
                }

                .tesyuk-chat-messages {
                    flex: 1 1 auto !important;
                    overflow-y: auto !important;
                    padding: 1rem !important;
                    background:
                        radial-gradient(circle at top right, rgba(var(--tesyuk-accent-rgb), 0.08), transparent 260px),
                        #fffafa !important;
                }

                .tesyuk-chat-message {
                    display: flex !important;
                    margin-bottom: 0.75rem !important;
                }

                .tesyuk-chat-message.is-mine {
                    justify-content: flex-end !important;
                }

                .tesyuk-chat-message.is-admin {
                    justify-content: flex-start !important;
                }

                .tesyuk-chat-bubble {
                    max-width: min(78%, 290px) !important;
                    border-radius: 18px !important;
                    padding: 0.72rem 0.82rem !important;
                    box-shadow: 0 14px 30px -26px rgba(var(--tesyuk-ink-rgb), 0.72) !important;
                }

                .tesyuk-chat-message.is-mine .tesyuk-chat-bubble {
                    background: var(--tesyuk-ink) !important;
                    color: #ffffff !important;
                    border-bottom-right-radius: 7px !important;
                }

                .tesyuk-chat-message.is-admin .tesyuk-chat-bubble {
                    background: #ffffff !important;
                    color: var(--tesyuk-ink) !important;
                    border: 1px solid rgba(var(--tesyuk-primary-rgb), 0.10) !important;
                    border-bottom-left-radius: 7px !important;
                }

                .tesyuk-chat-sender {
                    margin-bottom: 0.28rem !important;
                    font-size: 0.66rem !important;
                    font-weight: 900 !important;
                    opacity: 0.72 !important;
                }

                .tesyuk-chat-bubble p {
                    margin: 0 !important;
                    font-size: 0.82rem !important;
                    line-height: 1.55 !important;
                    white-space: pre-wrap !important;
                    overflow-wrap: anywhere !important;
                }

                .tesyuk-chat-bubble time {
                    display: block !important;
                    margin-top: 0.42rem !important;
                    font-size: 0.62rem !important;
                    font-weight: 700 !important;
                    opacity: 0.58 !important;
                    text-align: right !important;
                }

                .tesyuk-chat-attachment {
                    display: block !important;
                    margin-top: 0.55rem !important;
                    border-radius: 14px !important;
                    overflow: hidden !important;
                    border: 1px solid rgba(255, 255, 255, 0.24) !important;
                }

                .tesyuk-chat-attachment img {
                    display: block !important;
                    width: 100% !important;
                    max-height: 180px !important;
                    object-fit: cover !important;
                }

                .tesyuk-chat-empty {
                    min-height: 100% !important;
                    display: flex !important;
                    flex-direction: column !important;
                    align-items: center !important;
                    justify-content: center !important;
                    text-align: center !important;
                    color: rgba(var(--tesyuk-ink-rgb), 0.62) !important;
                    padding: 2rem !important;
                }

                .tesyuk-chat-empty img {
                    width: 70px !important;
                    height: 70px !important;
                    object-fit: contain !important;
                    margin-bottom: 0.8rem !important;
                }

                .tesyuk-chat-empty h3 {
                    margin: 0 !important;
                    color: var(--tesyuk-ink) !important;
                    font-size: 1rem !important;
                    font-weight: 900 !important;
                }

                .tesyuk-chat-empty p {
                    margin: 0.35rem 0 0 !important;
                    font-size: 0.82rem !important;
                    line-height: 1.55 !important;
                }

                .tesyuk-chat-form {
                    border-top: 1px solid rgba(var(--tesyuk-primary-rgb), 0.10) !important;
                    padding: 0.78rem !important;
                    background: rgba(255, 255, 255, 0.92) !important;
                }

                .tesyuk-chat-input-row {
                    display: grid !important;
                    grid-template-columns: 38px minmax(0, 1fr) auto !important;
                    gap: 0.48rem !important;
                    align-items: end !important;
                }

                .tesyuk-chat-file-button {
                    width: 38px !important;
                    height: 38px !important;
                    border-radius: 14px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                    background: var(--tesyuk-secondary) !important;
                    color: var(--tesyuk-primary) !important;
                    cursor: pointer !important;
                    transition: background-color 0.16s ease, color 0.16s ease, transform 0.16s ease !important;
                }

                .tesyuk-chat-file-button:hover {
                    background: var(--tesyuk-ink) !important;
                    color: #ffffff !important;
                    transform: translateY(-1px) !important;
                }

                .tesyuk-chat-file-button input {
                    display: none !important;
                }

                .tesyuk-chat-file-button svg {
                    width: 1.05rem !important;
                    height: 1.05rem !important;
                }

                .tesyuk-chat-form textarea {
                    min-height: 38px !important;
                    max-height: 96px !important;
                    resize: vertical !important;
                    border-radius: 14px !important;
                    border: 1px solid rgba(var(--tesyuk-primary-rgb), 0.12) !important;
                    background: #ffffff !important;
                    color: var(--tesyuk-ink) !important;
                    padding: 0.55rem 0.7rem !important;
                    font-size: 0.82rem !important;
                    line-height: 1.45 !important;
                    outline: none !important;
                }

                .tesyuk-chat-form textarea:focus {
                    border-color: rgba(var(--tesyuk-accent-rgb), 0.42) !important;
                    box-shadow: 0 0 0 3px rgba(var(--tesyuk-accent-rgb), 0.10) !important;
                }

                .tesyuk-chat-send {
                    min-height: 38px !important;
                    border-radius: 14px !important;
                    background: var(--tesyuk-ink) !important;
                    color: #ffffff !important;
                    padding: 0 0.9rem !important;
                    font-size: 0.75rem !important;
                    font-weight: 900 !important;
                    transition: background-color 0.16s ease, transform 0.16s ease !important;
                }

                .tesyuk-chat-send:hover,
                .tesyuk-chat-send:focus-visible {
                    background: var(--tesyuk-primary) !important;
                    transform: translateY(-1px) !important;
                }

                .tesyuk-chat-file-preview,
                .tesyuk-chat-error {
                    margin-bottom: 0.55rem !important;
                    border-radius: 14px !important;
                    padding: 0.48rem 0.62rem !important;
                    font-size: 0.72rem !important;
                    font-weight: 760 !important;
                }

                .tesyuk-chat-file-preview {
                    display: flex !important;
                    align-items: center !important;
                    justify-content: space-between !important;
                    gap: 0.6rem !important;
                    background: rgba(var(--tesyuk-accent-rgb), 0.08) !important;
                    color: var(--tesyuk-primary) !important;
                }

                .tesyuk-chat-file-preview button {
                    color: var(--tesyuk-ink) !important;
                    font-weight: 900 !important;
                }

                .tesyuk-chat-error {
                    background: rgba(var(--tesyuk-accent-rgb), 0.10) !important;
                    color: var(--tesyuk-primary) !important;
                }

                @media (max-width: 640px) {
                    .tesyuk-chat-widget {
                        right: 0.85rem !important;
                        bottom: 0.85rem !important;
                    }

                    .tesyuk-chat-panel {
                        left: 0.85rem !important;
                        right: 0.85rem !important;
                        bottom: 5.65rem !important;
                        width: auto !important;
                        height: min(620px, calc(100vh - 6.6rem)) !important;
                        border-radius: 24px !important;
                    }

                    .tesyuk-chat-tooltip {
                        display: none !important;
                    }
                }
            </style>
            <script>
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
                localStorage.setItem('tesyuk_theme', 'light');

                (() => {
                    const applyTesYukSidebarTooltips = () => {
                        document
                            .querySelectorAll('.fi-sidebar-header .fi-icon-btn')
                            .forEach((button) => {
                                const isOpenButton = button.classList.contains('mx-auto');
                                const label = isOpenButton ? 'Open sidebar' : 'Close sidebar';

                                button.setAttribute('data-sidebar-tooltip', label);
                                button.setAttribute('title', label);
                                button.setAttribute('aria-label', label);
                            });
                    };

                    const bootTesYukSidebarTooltips = () => {
                        applyTesYukSidebarTooltips();

                        if (! window.__tesyukSidebarTooltipObserver && document.body) {
                            window.__tesyukSidebarTooltipObserver = new MutationObserver(applyTesYukSidebarTooltips);
                            window.__tesyukSidebarTooltipObserver.observe(document.body, { childList: true, subtree: true });
                        }
                    };

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', bootTesYukSidebarTooltips);
                    } else {
                        bootTesYukSidebarTooltips();
                    }

                    document.addEventListener('livewire:navigated', bootTesYukSidebarTooltips);
                })();

                (() => {
                    const applyTesYukTopbarTooltips = () => {
                        document
                            .querySelectorAll('.fi-topbar .fi-icon-btn')
                            .forEach((button) => {
                                const currentLabel = button.getAttribute('aria-label')
                                    || button.getAttribute('title')
                                    || button.textContent?.trim();
                                const isNotification = button.classList.contains('fi-topbar-database-notifications-btn')
                                    || /notification|notifikasi/i.test(currentLabel || '');
                                const label = isNotification ? 'Notifikasi' : currentLabel;

                                if (! label) {
                                    return;
                                }

                                button.setAttribute('data-topbar-tooltip', label);
                                button.setAttribute('title', label);
                                button.setAttribute('aria-label', label);
                            });
                    };

                    const bootTesYukTopbarTooltips = () => {
                        applyTesYukTopbarTooltips();

                        if (! window.__tesyukTopbarTooltipObserver && document.body) {
                            window.__tesyukTopbarTooltipObserver = new MutationObserver(applyTesYukTopbarTooltips);
                            window.__tesyukTopbarTooltipObserver.observe(document.body, { childList: true, subtree: true });
                        }
                    };

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', bootTesYukTopbarTooltips);
                    } else {
                        bootTesYukTopbarTooltips();
                    }

                    document.addEventListener('livewire:navigated', bootTesYukTopbarTooltips);
                })();
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
                <div class="tesyuk-sidebar-profile-summary" aria-label="Ringkasan profil">
                    {$avatar}
                    <div class="tesyuk-sidebar-profile-copy" style="min-width:0;">
                        <div class="tesyuk-sidebar-profile-name">{$escapedName}</div>
                        <div class="tesyuk-sidebar-profile-meta">{$escapedEmail}</div>
                        {$metaHtml}
                    </div>
                </div>

                <div class="tesyuk-sidebar-profile-actions">
                    <a href="{$escapedProfileUrl}" class="tesyuk-sidebar-profile-button">Edit Profil</a>
                    <form method="post" action="{$escapedLogoutUrl}" class="tesyuk-sidebar-logout-form">
                        <input type="hidden" name="_token" value="{$escapedCsrfToken}">
                        <button type="submit" class="tesyuk-sidebar-logout" aria-label="Log out" title="Log out">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.1" stroke="currentColor" style="width:1.12rem;height:1.12rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
                            </svg>
                        </button>
                        <span class="tesyuk-sidebar-logout-tooltip">Log out</span>
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
