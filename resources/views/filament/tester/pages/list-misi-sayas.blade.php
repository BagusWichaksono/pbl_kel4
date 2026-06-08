<x-filament-panels::page>
<style>
    .misi-header {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        padding: 14px 20px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 20px;
        box-shadow: 0 14px 34px -28px rgba(15, 23, 42, 0.34);
    }
    .misi-header-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .misi-header-badge {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 999px;
        padding: 3px 14px;
        font-size: 12px;
        font-weight: 800;
        color: #047857;
    }
    .misi-card {
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        background: #fff;
        overflow: hidden;
        transition: transform .18s, box-shadow .18s, border-color .18s;
        display: block;
        text-decoration: none;
        box-shadow: 0 14px 34px -30px rgba(15, 23, 42, .34);
    }
    .misi-card:hover {
        transform: translateY(-3px);
        border-color: #cbd5e1;
        box-shadow: 0 22px 46px -34px rgba(15, 23, 42, .44);
    }
    .misi-card.is-locked {
        cursor: pointer;
        border-color: #fed7aa;
        background: #fffaf5;
        opacity: .86;
    }
    .misi-card.is-locked:hover {
        transform: none;
        border-color: #fed7aa;
        box-shadow: 0 14px 34px -30px rgba(15, 23, 42, .34);
    }
    .misi-card-top {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 18px;
    }
    .misi-card.is-locked .misi-card-top {
        background: #fff7ed;
        border-bottom-color: #fed7aa;
    }
    .misi-card-top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .misi-app-meta {
        min-width: 0;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .misi-app-icon {
        width: 70px;
        height: 70px;
        min-width: 70px;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 14px 28px -24px rgba(15, 23, 42, .45);
    }
    .misi-app-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 8px;
        background: #fff;
    }
    .misi-app-icon-fallback {
        color: #047857;
        font-size: 1.45rem;
        font-weight: 900;
        letter-spacing: .04em;
    }
    .misi-card-title {
        font-size: 16px;
        font-weight: 850;
        color: #0f172a;
        margin-bottom: 4px;
    }
    .misi-card-subtitle {
        font-size: 12px;
        color: #64748b;
    }
    .misi-badge {
        border-radius: 999px;
        padding: 3px 12px;
        font-size: 11px;
        font-weight: 600;
        flex-shrink: 0;
        margin-top: 2px;
        white-space: nowrap;
    }
    .misi-badge-running {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #047857;
    }
    .misi-badge-done {
        background: #DCFCE7;
        color: #15803D;
    }
    .misi-badge-locked {
        background: #FFF7ED;
        border: 1px solid #FED7AA;
        color: #9A3412;
    }
    .misi-card-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 14px;
    }
    .misi-progress-label {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }
    .misi-progress-label .lbl {
        font-size: 12px;
        color: #7c6f6f;
    }
    .misi-progress-label .pct {
        font-size: 12px;
        font-weight: 800;
        color: #047857;
    }
    .misi-track {
        height: 8px;
        background: #E2E8F0;
        border-radius: 999px;
        overflow: hidden;
    }
    .misi-fill {
        height: 100%;
        border-radius: 999px;
        background: #10b981;
    }
    .misi-fill.locked {
        background: #f97316;
    }
    .misi-date-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        color: #7c6f6f;
    }
    .misi-footer {
        border-top: 1px solid #e2e8f0;
        padding-top: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    .misi-day-chips {
        display: flex;
        gap: 3px;
        flex-wrap: wrap;
    }
    .misi-day-chip {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        background: #E2E8F0;
        position: relative;
    }
    /* Hari sudah dilewati & ada laporan = hijau */
    .misi-day-chip.submitted {
        background: #22C55E;
    }
    /* Hari sudah dilewati tapi tidak ada laporan = oranye lembut */
    .misi-day-chip.missed {
        background: #FED7AA;
    }
    /* Hari belum tiba = abu (default) */
    .misi-footer-link {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #047857;
        transition: gap .18s;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .misi-footer-link.is-locked {
        color: #9A3412;
    }
    .misi-card:hover .misi-footer-link {
        gap: 8px;
    }
    .misi-legend {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    .misi-legend-item {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        color: #7c6f6f;
    }
    .misi-legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 2px;
    }
    .misi-empty {
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        background: #fff;
        padding: 64px 24px;
        text-align: center;
    }
    .misi-empty-icon {
        margin: 0 auto 16px;
        width: 56px;
        height: 56px;
        border-radius: 16px;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .misi-report-count {
        font-size: 11px;
        color: #7c6f6f;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .misi-lock-notice {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        border-radius: 16px;
        border: 1px solid #fed7aa;
        background: #fff7ed;
        padding: 10px 12px;
        font-size: 12px;
        line-height: 1.45;
        color: #9a3412;
    }
</style>

<div class="space-y-5">

    {{-- HEADER --}}
    @if($missions->isNotEmpty())
        <div class="misi-header">
            <div class="misi-header-left">
                <x-heroicon-o-device-phone-mobile class="h-5 w-5" style="color:#047857;" />
                <h2 class="text-sm font-bold" style="color:#0f172a;">Aplikasi Terdaftar</h2>
                <span class="misi-header-badge">{{ $missions->count() }}</span>
            </div>
        </div>
    @endif

    {{-- EMPTY STATE --}}
    @if($missions->isEmpty())
        <div class="misi-empty">
            <div class="misi-empty-icon mx-auto">
                <x-heroicon-o-clipboard-document-list class="h-7 w-7" style="color:#047857;" />
            </div>
            <h2 class="text-lg font-bold" style="color:var(--tesyuk-ink);">Belum Ada Misi Testing</h2>
            <p class="mt-2 text-sm" style="color:#7c6f6f;">
                Misi akan muncul setelah kamu mendaftar sebagai tester.
            </p>
        </div>

    @else

        {{-- CARD GRID --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            @foreach($missions as $mission)

                @php
                $dailyTestingDays = \App\Models\ApplicationTester::DAILY_TESTING_DAYS;
                $application       = $mission->application;
                $dailyReportsCount = $mission->daily_reports_count_custom ?? 0;
                $submittedDates = collect($mission->daily_report_dates_custom ?? []);
                $missedDates = collect($mission->missed_daily_report_dates_custom ?? []);
                $missedReportsCount = (int) ($mission->missed_daily_reports_count_custom ?? 0);
                $isLockedDueMissedReport = (bool) ($mission->is_locked_due_missed_report ?? false);
                $appIconPath = $application?->app_icon;
                $appIconUrl = $appIconPath
                    ? (str_starts_with($appIconPath, 'http') ? $appIconPath : asset('storage/' . $appIconPath))
                    : null;
                $appInitials = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $application?->title ?? 'Aplikasi'), 0, 2));
                $appInitials = $appInitials !== '' ? $appInitials : 'AP';

                $detailUrl = \App\Filament\Tester\Resources\MisiSayaResource::getUrl(
                    'view',
                    ['record' => $mission->id]
                );

                $today = \Carbon\Carbon::today();

                $startDate = $application?->start_date
                    ? \Carbon\Carbon::parse($application->start_date)->startOfDay()
                    : null;

                $endDate = $application?->end_date
                    ? \Carbon\Carbon::parse($application->end_date)->startOfDay()
                    : ($startDate ? $startDate->copy()->addDays($dailyTestingDays - 1) : null);

                // Berapa hari kalender yang sudah dilewati
                $daysPassed = 0;

                if ($startDate) {
                    $diff = $startDate->diffInDays($today, false);
                    $daysPassed = (int) min(max($diff + 1, 0), $dailyTestingDays);
                }

                // Status misi
                $isFinished = $daysPassed >= $dailyTestingDays || $mission->status === \App\Models\ApplicationTester::STATUS_COMPLETED;

                if ($isLockedDueMissedReport) {
                    $statusText = 'Misi gugur';
                    $badgeClass = 'misi-badge-locked';
                    $badgeLabel = 'Gugur';
                } elseif ($isFinished) {
                    $statusText = 'Misi selesai';
                    $badgeClass = 'misi-badge-done';
                    $badgeLabel = "{$daysPassed}/{$dailyTestingDays} Hari";
                } elseif ($mission->status === \App\Models\ApplicationTester::STATUS_DROPPED) {
                    $statusText = 'Misi tidak aktif';
                    $badgeClass = 'misi-badge-locked';
                    $badgeLabel = 'Dikunci';
                } else {
                    $statusText = 'Masih berjalan';
                    $badgeClass = 'misi-badge-running';
                    $badgeLabel = "{$daysPassed}/{$dailyTestingDays} Hari";
                }

                // Progress laporan
                $progressPct = min(
                    round(($dailyReportsCount / $dailyTestingDays) * 100),
                    100
                );
            @endphp

                <a href="{{ $detailUrl }}" class="misi-card {{ $isLockedDueMissedReport ? 'is-locked' : '' }} group">

                    {{-- TOP --}}
                    <div class="misi-card-top">
                        <div class="misi-card-top-row">
                            <div class="misi-app-meta">
                                <div class="misi-app-icon">
                                    @if($appIconUrl)
                                        <img src="{{ $appIconUrl }}" alt="{{ $application?->title ?? 'Icon aplikasi' }}">
                                    @else
                                        <span class="misi-app-icon-fallback">{{ $appInitials }}</span>
                                    @endif
                                </div>

                                <div class="min-w-0">
                                    <p class="misi-card-title truncate">
                                        {{ $application?->title ?? 'Aplikasi Tidak Ditemukan' }}
                                    </p>
                                    <p class="misi-card-subtitle">{{ $statusText }}</p>
                                </div>
                            </div>

                            {{-- Badge menunjukkan hari kalender yang sudah lewat --}}
                            <span class="misi-badge {{ $badgeClass }}">
                                {{ $badgeLabel }}
                            </span>
                        </div>
                    </div>

                    {{-- BODY --}}
                    <div class="misi-card-body">

                        {{-- Progress bar berdasarkan laporan terkumpul --}}
                        <div>
                            <div class="misi-progress-label">
                                <span class="lbl">Bukti testing terkumpul</span>
                                <span class="pct">{{ $progressPct }}%</span>
                            </div>
                            <div class="misi-track">
                                <div class="misi-fill {{ $isLockedDueMissedReport ? 'locked' : '' }}" style="width:{{ $progressPct }}%;"></div>
                            </div>
                        </div>

                        @if($isLockedDueMissedReport)
                            <div class="misi-lock-notice">
                                <x-heroicon-o-lock-closed class="h-4 w-4 shrink-0" />
                                <span>
                                    Tester tidak bisa mengikuti sesi testing karena misi harian sudah terlewat tanpa bukti laporan. Misi ini gugur.
                                </span>
                            </div>
                        @endif

                        {{-- Keterangan laporan vs hari --}}
                        <div class="misi-report-count">
                            <x-heroicon-o-document-check class="h-4 w-4 shrink-0" />
                            {{ $dailyReportsCount }} laporan dikumpulkan
                            @if($missedReportsCount > 0)
                                &nbsp;·&nbsp;
                                <span style="color:#9A3412;">
                                    {{ $missedReportsCount }} hari terlewat tanpa laporan
                                </span>
                            @endif
                        </div>

                        {{-- Tanggal --}}
                        <div class="misi-date-row">
                            <x-heroicon-o-calendar-days class="h-4 w-4 shrink-0" />
                            @if($startDate)
                                {{ $startDate->translatedFormat('d M Y') }}
                                –
                                {{ $endDate->translatedFormat('d M Y') }}
                            @else
                                Periode belum dimulai
                            @endif
                        </div>

                        {{-- Footer --}}
                        <div class="misi-footer">
                            <div>
                                {{-- Day chips --}}
                                <div class="misi-day-chips" style="margin-bottom:6px;">
                                    @for($d = 0; $d < $dailyTestingDays; $d++)
                                        @php
                                        $chipDate = $startDate ? $startDate->copy()->addDays($d)->toDateString() : null;

                                        if ($chipDate && $submittedDates->contains($chipDate)) {
                                            $chipClass = 'submitted';
                                        } elseif ($chipDate && $missedDates->contains($chipDate)) {
                                            $chipClass = 'missed';
                                        } else {
                                            $chipClass = '';
                                        }
                                        @endphp
                                        <div class="misi-day-chip {{ $chipClass }}" title="Hari {{ $d + 1 }}"></div>
                                    @endfor
                                </div>
                                {{-- Legend --}}
                                <div class="misi-legend">
                                    <div class="misi-legend-item">
                                        <div class="misi-legend-dot" style="background:#22C55E;"></div>
                                        Laporan dikumpulkan
                                    </div>
                                    <div class="misi-legend-item">
                                        <div class="misi-legend-dot" style="background:#FED7AA;"></div>
                                        Terlewat
                                    </div>
                                </div>
                            </div>

                            @if($isLockedDueMissedReport)
                                <span class="misi-footer-link is-locked">
                                    Lihat preview
                                    <x-heroicon-o-arrow-right class="h-4 w-4" />
                                </span>
                            @else
                                <span class="misi-footer-link">
                                    Lihat detail
                                    <x-heroicon-o-arrow-right class="h-4 w-4" />
                                </span>
                            @endif
                        </div>

                    </div>
                </a>

            @endforeach
        </div>

    @endif
</div>
</x-filament-panels::page>
