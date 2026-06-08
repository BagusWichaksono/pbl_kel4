<x-filament-panels::page>
    @php
        /** @var \App\Models\DailyReport $report */
        $report = $this->record;
        $report->loadMissing(['application', 'tester', 'reviewer']);

        $application = $report->application;
        $tester = $report->tester;
        $appIconPath = $application?->app_icon;
        $appIconUrl = $appIconPath
            ? (str_starts_with($appIconPath, 'http') ? $appIconPath : asset('storage/' . $appIconPath))
            : null;
        $appInitials = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $application?->title ?? 'Aplikasi'), 0, 2));
        $appInitials = $appInitials !== '' ? $appInitials : 'AP';

        $status = $report->status ?? \App\Models\DailyReport::STATUS_PENDING;
        $statusLabel = match ($status) {
            \App\Models\DailyReport::STATUS_APPROVED => 'Disetujui',
            \App\Models\DailyReport::STATUS_REJECTED => 'Ditolak',
            default => 'Menunggu Review',
        };
        $statusStyle = match ($status) {
            \App\Models\DailyReport::STATUS_APPROVED => 'background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;',
            \App\Models\DailyReport::STATUS_REJECTED => 'background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;',
            default => 'background:#fffbeb;color:#92400e;border:1px solid #fde68a;',
        };

        $backUrl = \App\Filament\Developer\Resources\DailyReportResource::getUrl('index', [
            'tableFilters' => [
                'app_id' => [
                    'value' => $report->app_id,
                ],
            ],
        ]);
    @endphp

    <style>
        .daily-detail-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--tesyuk-primary);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
        }

        .daily-detail-shell {
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 28px;
            background: #ffffff;
            box-shadow: 0 24px 60px -42px rgba(15, 23, 42, .42);
        }

        .daily-detail-hero {
            padding: 24px 28px;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
        }

        .daily-detail-app-icon {
            width: 56px !important;
            height: 56px !important;
            max-width: 56px !important;
            max-height: 56px !important;
            min-width: 56px !important;
            min-height: 56px !important;
            flex: 0 0 56px !important;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            background: #ffffff;
            box-shadow: 0 16px 32px -26px rgba(15, 23, 42, .48);
        }

        .daily-detail-app-icon img {
            display: block;
            width: 100% !important;
            height: 100% !important;
            max-width: 56px !important;
            max-height: 56px !important;
            object-fit: contain;
            box-sizing: border-box;
            padding: 7px;
            background: #ffffff;
        }

        .daily-detail-app-icon-fallback {
            color: var(--tesyuk-primary);
            font-size: 1.55rem;
            font-weight: 900;
            letter-spacing: .04em;
        }

        .daily-detail-back svg,
        .daily-detail-chip svg,
        .daily-detail-panel-header svg,
        .daily-detail-panel-body a svg {
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .daily-detail-chip svg {
            width: .875rem;
            height: .875rem;
        }

        .daily-detail-empty svg {
            width: 2rem;
            height: 2rem;
            flex-shrink: 0;
        }

        .daily-detail-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
        }

        .daily-detail-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            background: #f8fafc;
            color: #64748b;
            padding: 6px 11px;
            font-size: 12px;
            font-weight: 700;
        }

        .daily-detail-grid {
            display: grid;
            gap: 22px;
            padding: 24px;
            background: #f8fafc;
        }

        @media (min-width: 1180px) {
            .daily-detail-grid {
                grid-template-columns: minmax(0, 1.1fr) minmax(360px, .9fr);
            }
        }

        .daily-detail-panel {
            border: 1px solid #e2e8f0;
            border-radius: 22px;
            background: #ffffff;
            overflow: hidden;
            box-shadow: 0 16px 34px -30px rgba(15, 23, 42, .36);
        }

        .daily-detail-panel-header {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 16px 18px;
            border-bottom: 1px solid #e2e8f0;
            background: #ffffff;
            color: var(--tesyuk-ink);
            font-size: 14px;
            font-weight: 800;
        }

        .daily-detail-panel-body {
            padding: 18px;
        }

        .daily-detail-text-box {
            border: 1px solid rgba(var(--tesyuk-accent-rgb), .18);
            border-radius: 18px;
            background: var(--tesyuk-secondary);
            color: var(--tesyuk-ink);
            padding: 16px;
            font-size: 14px;
            line-height: 1.7;
            white-space: pre-line;
        }

        .daily-detail-screenshot {
            width: 100%;
            max-height: 72vh;
            object-fit: contain;
            border-radius: 16px;
            background: #f8fafc;
        }

        .daily-detail-empty {
            display: flex;
            min-height: 220px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 1px dashed #cbd5e1;
            border-radius: 18px;
            background: #f8fafc;
            color: #64748b;
            text-align: center;
        }

        .daily-detail-review {
            border-color: #fecaca;
            background: #fef2f2;
            color: #7f1d1d;
        }

        @media (max-width: 640px) {
            .daily-detail-hero,
            .daily-detail-grid {
                padding: 18px;
            }

            .daily-detail-app-icon {
                width: 52px !important;
                height: 52px !important;
                max-width: 52px !important;
                max-height: 52px !important;
                min-width: 52px !important;
                min-height: 52px !important;
                flex-basis: 52px !important;
                border-radius: 16px;
            }
        }
    </style>

    <div class="space-y-4">
        <a href="{{ $backUrl }}" class="daily-detail-back">
            <x-heroicon-o-arrow-left class="h-4 w-4" />
            Kembali ke daftar laporan
        </a>

        <div class="daily-detail-shell">
            <div class="daily-detail-hero">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex min-w-0 items-start gap-4">
                        <div class="daily-detail-app-icon" style="width:56px !important;height:56px !important;min-width:56px !important;max-width:56px !important;min-height:56px !important;max-height:56px !important;flex:0 0 56px !important;">
                            @if($appIconUrl)
                                <img src="{{ $appIconUrl }}" alt="{{ $application?->title ?? 'Logo aplikasi' }}" style="display:block;width:100% !important;height:100% !important;max-width:56px !important;max-height:56px !important;object-fit:contain;box-sizing:border-box;padding:7px;background:#ffffff;">
                            @else
                                <span class="daily-detail-app-icon-fallback">{{ $appInitials }}</span>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-xl font-bold tracking-tight" style="color:#0f172a;">
                                    {{ $application?->title ?? 'Aplikasi tidak ditemukan' }}
                                </h2>
                                <span class="rounded-full px-3 py-1 text-xs font-bold" style="{{ $statusStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="daily-detail-meta">
                                <span class="daily-detail-chip">
                                    <x-heroicon-o-user class="h-3.5 w-3.5" />
                                    {{ $tester?->name ?? 'Tester tidak diketahui' }}
                                </span>
                                <span class="daily-detail-chip">
                                    <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                                    {{ $report->report_date?->translatedFormat('d M Y') ?? '-' }}
                                </span>
                                <span class="daily-detail-chip">
                                    <x-heroicon-o-clock class="h-3.5 w-3.5" />
                                    Dikirim {{ $report->created_at?->format('d M Y H:i') ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($report->reviewed_at)
                        <div class="text-sm lg:text-right" style="color:#64748b;">
                            <div class="font-semibold" style="color:#0f172a;">Direview oleh {{ $report->reviewer?->name ?? 'Developer' }}</div>
                            <div>{{ $report->reviewed_at?->format('d M Y H:i') }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="daily-detail-grid">
                <div class="daily-detail-panel">
                    <div class="daily-detail-panel-header">
                        <x-heroicon-o-photo class="h-4 w-4" style="color:var(--tesyuk-primary);" />
                        Screenshot Laporan
                    </div>
                    <div class="daily-detail-panel-body">
                        @if($report->screenshot)
                            <img
                                src="{{ Storage::url($report->screenshot) }}"
                                alt="Screenshot laporan harian"
                                class="daily-detail-screenshot"
                            >
                            <a href="{{ Storage::url($report->screenshot) }}" target="_blank"
                                class="mt-4 inline-flex items-center justify-center gap-2 rounded-full px-4 py-2 text-sm font-bold"
                                style="background:var(--tesyuk-secondary);color:var(--tesyuk-primary);border:1px solid rgba(var(--tesyuk-accent-rgb),.18);">
                                <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                                Buka screenshot di tab baru
                            </a>
                        @else
                            <div class="daily-detail-empty">
                                <x-heroicon-o-photo class="mb-2 h-8 w-8" />
                                <p class="text-sm font-semibold">Tidak ada screenshot.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="daily-detail-panel">
                        <div class="daily-detail-panel-header">
                            <x-heroicon-o-document-text class="h-4 w-4" style="color:var(--tesyuk-primary);" />
                            Catatan Tester
                        </div>
                        <div class="daily-detail-panel-body">
                            <div class="daily-detail-text-box">
                                {{ $report->notes ?: 'Tidak ada catatan.' }}
                            </div>
                        </div>
                    </div>

                    <div class="daily-detail-panel">
                        <div class="daily-detail-panel-header">
                            <x-heroicon-o-bug-ant class="h-4 w-4" style="color:{{ $report->bug_report ? '#b91c1c' : 'var(--tesyuk-primary)' }};" />
                            Laporan Bug
                        </div>
                        <div class="daily-detail-panel-body">
                            <div class="daily-detail-text-box" style="{{ $report->bug_report ? 'background:#fef2f2;border-color:#fecaca;color:#7f1d1d;' : '' }}">
                                {{ $report->bug_report ?: 'Tidak ada laporan bug pada hari ini.' }}
                            </div>
                        </div>
                    </div>

                    @if($report->rejection_reason)
                        <div class="daily-detail-panel">
                            <div class="daily-detail-panel-header">
                                <x-heroicon-o-x-circle class="h-4 w-4" style="color:#b91c1c;" />
                                Alasan Reject
                            </div>
                            <div class="daily-detail-panel-body">
                                <div class="daily-detail-text-box daily-detail-review">
                                    {{ $report->rejection_reason }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
