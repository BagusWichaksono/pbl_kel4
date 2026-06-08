<x-filament-panels::page>
    <style>
        .misi-detail-shell {
            overflow: hidden;
            border-radius: 30px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 24px 60px -42px rgba(15, 23, 42, .42);
        }

        .misi-detail-hero {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 24px 28px;
        }

        .misi-detail-app-icon {
            width: 76px;
            height: 76px;
            min-width: 76px;
            border-radius: 22px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            box-shadow: 0 16px 32px -26px rgba(15, 23, 42, .48);
        }

        .misi-detail-app-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 9px;
            background: #ffffff;
        }

        .misi-detail-app-icon-fallback {
            color: #047857;
            font-size: 1.55rem;
            font-weight: 900;
            letter-spacing: .04em;
        }

        .misi-progress-strip {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 16px 28px;
        }

        .misi-lock-banner {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            border-bottom: 1px solid #fed7aa;
            background: #fff7ed;
            padding: 16px 28px;
            color: #9a3412;
        }

        .misi-lock-banner-icon {
            display: flex;
            height: 36px;
            width: 36px;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            background: #ffedd5;
            color: #9a3412;
        }

        .misi-detail-grid {
            display: grid;
            gap: 24px;
            padding: 24px;
            background: #f8fafc;
        }

        @media (min-width: 1280px) {
            .misi-detail-grid {
                grid-template-columns: minmax(280px, .9fr) minmax(0, 2fr);
            }
        }

        .misi-detail-sidebar {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .misi-info-card,
        .misi-timeline-panel {
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 16px 34px -30px rgba(15, 23, 42, .36);
        }

        .misi-info-card {
            padding: 20px;
        }

        .misi-timeline-panel {
            overflow: hidden;
        }

        .misi-timeline-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding: 18px 22px;
            background: #ffffff;
        }

        .misi-timeline-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 18px;
            background: #f8fafc;
        }

        .misi-day-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background: #ffffff;
            padding: 14px 16px;
            box-shadow: 0 12px 28px -26px rgba(15, 23, 42, .32);
        }

        .misi-day-row.is-today {
            border-color: #86efac;
            background: #f0fdf4;
            box-shadow: 0 16px 34px -28px rgba(16, 185, 129, .42);
        }

        .misi-day-action {
            flex-shrink: 0;
        }

        @media (max-width: 640px) {
            .misi-detail-hero,
            .misi-progress-strip,
            .misi-lock-banner {
                padding-left: 18px;
                padding-right: 18px;
            }

            .misi-detail-grid {
                padding: 18px;
            }

            .misi-detail-app-icon {
                width: 64px;
                height: 64px;
                min-width: 64px;
                border-radius: 18px;
            }

            .misi-day-row {
                align-items: flex-start;
                flex-direction: column;
            }

            .misi-day-action {
                width: 100%;
            }
        }
    </style>

    @if(!$mission)
        <div class="rounded-2xl border p-8 text-center" style="background:#FEF2F2; border-color:#FECACA;">
            <p class="font-semibold" style="color:#B91C1C;">Misi tidak ditemukan.</p>
        </div>
    @else
        @php
            $dailyTestingDays = \App\Models\ApplicationTester::DAILY_TESTING_DAYS;
            $application = $mission->application;
            $appIconPath = $application?->app_icon;
            $appIconUrl = $appIconPath
                ? (str_starts_with($appIconPath, 'http') ? $appIconPath : asset('storage/' . $appIconPath))
                : null;
            $appInitials = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $application?->title ?? 'Aplikasi'), 0, 2));
            $appInitials = $appInitials !== '' ? $appInitials : 'AP';
            $dailyReportsCount = $mission->daily_reports_count_custom ?? 0;
            $progressPercentage = $mission->progress_percentage ?? 0;
            $dailyMissions = $mission->daily_missions_custom ?? [];
            $missedReportsCount = (int) ($mission->missed_daily_reports_count_custom ?? 0);
            $isLockedDueMissedReport = (bool) ($mission->is_locked_due_missed_report ?? false);
            $isRefunded = ($application?->payment_status ?? null) === 'refunded';

            $statusStyle = $isRefunded
                ? 'background:#FEE2E2;color:#B91C1C;border:1px solid #FECACA;'
                : ($isLockedDueMissedReport
                ? 'background:#FFF7ED;color:#9A3412;border:1px solid #FED7AA;'
                : match ($mission->status) {
                    'active' => 'background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;',
                    'completed' => 'background:#DCFCE7;color:#15803D;',
                    'failed', 'dropped' => 'background:#FFF7ED;color:#9A3412;',
                    default => 'background:#E2E8F0;color:#7c6f6f;',
                });

            $statusLabel = $isRefunded
                ? 'Tidak tersedia'
                : ($isLockedDueMissedReport
                ? 'Gugur'
                : match ($mission->status) {
                    'active' => 'Aktif',
                    'completed' => 'Selesai',
                    'failed' => 'Gagal',
                    'dropped' => 'Gugur',
                    default => $mission->status,
                });
        @endphp

        {{-- BACK LINK --}}
        <div class="mb-2">
            <a href="{{ \App\Filament\Tester\Resources\MisiSayaResource::getUrl('index') }}"
                class="inline-flex items-center gap-1.5 text-sm font-medium transition-colors hover:opacity-80"
                style="color:#047857;">
                <x-heroicon-o-arrow-left class="h-4 w-4" />
                Kembali ke Misi Saya
            </a>
        </div>

        <div class="misi-detail-shell">

            {{-- HEADER --}}
            <div class="misi-detail-hero">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex min-w-0 items-start gap-4">
                        <div class="misi-detail-app-icon">
                            @if($appIconUrl)
                                <img src="{{ $appIconUrl }}" alt="{{ $application?->title ?? 'Icon aplikasi' }}">
                            @else
                                <span class="misi-detail-app-icon-fallback">{{ $appInitials }}</span>
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

                            <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-1.5 text-sm" style="color:#64748b;">
                                <span class="flex items-center gap-1.5">
                                    <x-heroicon-o-user class="h-3.5 w-3.5" />
                                    {{ $application?->developer?->name ?? '-' }}
                                </span>

                                @if($isRefunded)
                                    <span class="flex items-center gap-1.5">
                                        <x-heroicon-o-lock-closed class="h-3.5 w-3.5" />
                                        Mohon maaf aplikasi telah ditarik dari peredaran dan tidak tersedia saat ini
                                    </span>
                                @elseif($application?->start_date)
                                    <span class="flex items-center gap-1.5">
                                        <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                                        {{ \Carbon\Carbon::parse($application->start_date)->translatedFormat('d M Y') }}
                                        &mdash;
                                        {{ $application?->end_date
                                            ? \Carbon\Carbon::parse($application->end_date)->translatedFormat('d M Y')
                                            : \Carbon\Carbon::parse($application->start_date)->addDays($dailyTestingDays - 1)->translatedFormat('d M Y')
                                        }}
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5">
                                        <x-heroicon-o-clock class="h-3.5 w-3.5" />
                                        Periode belum dimulai
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="shrink-0 ml-auto flex justify-end">
                        @if($isRefunded)
                            <span class="inline-flex items-center gap-2 rounded-full px-4 py-2.5 text-sm font-bold"
                                style="border:1px solid #fecaca;color:#b91c1c;background:#fef2f2;">
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                Tidak tersedia
                            </span>
                        @elseif($isLockedDueMissedReport)
                            <span class="inline-flex items-center gap-2 rounded-full px-4 py-2.5 text-sm font-bold"
                                style="border:1px solid #fed7aa;color:#9a3412;background:#fff7ed;">
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                Misi Dikunci
                            </span>
                        @elseif($application?->app_link)
                            <a href="{{ $application->app_link }}" target="_blank"
                                class="inline-flex items-center gap-2 rounded-full px-4 py-2.5 text-sm font-bold transition hover:opacity-90"
                                style="background:#f8fafc;border:1px solid #e2e8f0;color:#047857;">
                                <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                                Buka Aplikasi
                            </a>
                        @else
                            <span class="inline-flex items-center gap-2 rounded-full px-4 py-2.5 text-sm font-medium"
                                style="border:1px solid #e2e8f0;color:#64748b;background:#f8fafc;">
                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                Link Belum Tersedia
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- PROGRESS BAR STRIP --}}
            <div class="misi-progress-strip">
                <span class="text-xs font-semibold uppercase tracking-wide" style="color:{{ ($isLockedDueMissedReport || $isRefunded) ? '#9a3412' : '#047857' }};">Progress</span>
                <div class="h-2 flex-1 overflow-hidden rounded-full" style="background:#e2e8f0;">
                    <div class="h-2 rounded-full transition-all" style="width:{{ $progressPercentage }}%; background:{{ ($isLockedDueMissedReport || $isRefunded) ? '#f97316' : '#10b981' }};"></div>
                </div>
                <span class="text-sm font-bold" style="color:var(--tesyuk-ink);">{{ $dailyReportsCount }}/{{ $dailyTestingDays }}</span>
            </div>

            @if($isRefunded)
                <div class="misi-lock-banner">
                    <div class="misi-lock-banner-icon">
                        <x-heroicon-o-lock-closed class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="text-sm font-bold">Mohon maaf aplikasi telah ditarik dari peredaran dan tidak tersedia saat ini</p>
                    </div>
                </div>
            @elseif($isLockedDueMissedReport)
                <div class="misi-lock-banner">
                    <div class="misi-lock-banner-icon">
                        <x-heroicon-o-lock-closed class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="text-sm font-bold">Tidak bisa melanjutkan sesi testing</p>
                        <p class="mt-1 text-xs leading-relaxed">
                            Misi ini gugur karena ada {{ $missedReportsCount }} laporan harian yang terlewat tanpa bukti. Tester tidak bisa mengirim laporan harian atau laporan akhir untuk misi ini.
                        </p>
                    </div>
                </div>
            @endif

            {{-- CONTENT --}}
            <div class="misi-detail-grid">

                {{-- LEFT SIDEBAR --}}
                <div class="misi-detail-sidebar">

                    {{-- INSTRUKSI --}}
                    <div class="misi-info-card">
                        <div class="mb-2.5 flex items-center gap-2">
                            <x-heroicon-o-document-text class="h-4 w-4" style="color:#047857;" />
                            <h3 class="text-sm font-semibold" style="color:var(--tesyuk-ink);">Instruksi Testing</h3>
                        </div>
                        <p class="whitespace-pre-line text-sm leading-relaxed" style="color:#7c6f6f;">{{ $application?->description ?? 'Belum ada instruksi dari developer.' }}</p>
                    </div>

                    {{-- LAPORAN AKHIR --}}
                    <div class="misi-info-card">
                        <div class="mb-2.5 flex items-center gap-2">
                            <x-heroicon-o-paper-airplane class="h-4 w-4" style="color:#047857;" />
                            <h3 class="text-sm font-semibold" style="color:var(--tesyuk-ink);">Laporan Akhir</h3>
                        </div>
                        
                        @if($isLockedDueMissedReport)
                            <div class="rounded-2xl p-4" style="background:#FFF7ED; border: 1px solid #FED7AA;">
                                <p class="text-sm font-medium" style="color:#9A3412;">
                                    <x-heroicon-o-lock-closed class="inline h-4 w-4 mr-1"/> Misi Gugur
                                </p>
                                <p class="mt-1 text-xs leading-relaxed" style="color:#9A3412;">
                                    Kamu tidak bisa mengirim laporan akhir karena ada laporan harian yang terlewat.
                                </p>
                            </div>
                        @elseif(isset($testingReport) && $testingReport->status === 'pending')
                            <div class="rounded-2xl p-4" style="background:#FFFBEB; border: 1px solid #FDE68A;">
                                <p class="text-sm font-medium" style="color:#92400E;">
                                    <x-heroicon-o-clock class="inline h-4 w-4 mr-1"/> Menunggu Validasi Developer
                                </p>
                                <p class="mt-1 text-xs" style="color:#B45309;">
                                    Laporan akhirmu sedang ditinjau. Poin akan diberikan setelah disetujui.
                                </p>
                            </div>
                        @else
                            @if(isset($testingReport) && $testingReport->status === 'ditolak')
                                <div class="rounded-2xl p-4 mb-4" style="background:#FFF7ED; border: 1px solid #FED7AA;">
                                    <p class="text-sm font-medium" style="color:#9A3412;">
                                        <x-heroicon-o-x-circle class="inline h-4 w-4 mr-1"/> Laporan Ditolak
                                    </p>
                                    <p class="mt-1 text-xs" style="color:#9A3412;">
                                        <strong>Alasan:</strong> {{ $testingReport->alasan_penolakan }}
                                    </p>
                                    <p class="mt-2 text-xs" style="color:#9A3412;">
                                        Silakan perbaiki dan kirim ulang laporan akhir di bawah ini.
                                    </p>
                                </div>
                            @else
                                <p class="mb-2 text-xs leading-relaxed" style="color:#7c6f6f;">
                                    Kirim setelah {{ $dailyTestingDays }} hari testing & {{ $dailyTestingDays }} laporan harian selesai.
                                </p>
                            @endif

                            @if($this->canSubmitFinalReport($mission))
                               {{ ($this->kirimLaporanAkhirAction)([]) }}
                            @else
                                <button type="button" disabled
                                    title="{{ $this->finalReportTooltip($mission) }}"
                                    class="w-full cursor-not-allowed rounded-full px-4 py-2.5 text-xs font-semibold"
                                    style="background:#f1f5f9;border:1px solid #e2e8f0;color:#7c6f6f;">
                                    <x-heroicon-o-lock-closed class="mr-1 inline h-3.5 w-3.5" />
                                    Kirim Laporan Akhir
                                </button>
                                <p class="mt-2 text-xs leading-relaxed" style="color:#7c6f6f;">
                                    {{ $this->finalReportTooltip($mission) }}
                                </p>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- RIGHT: TIMELINE --}}
                <div class="misi-timeline-panel">
                    <div class="misi-timeline-header">
                        <div class="flex items-center gap-2">
                            <x-heroicon-o-list-bullet class="h-4 w-4" style="color:#047857;" />
                            <h3 class="text-sm font-semibold" style="color:var(--tesyuk-ink);">Timeline Misi Harian</h3>
                        </div>
                        <span class="text-xs" style="color:#7c6f6f;">{{ $dailyTestingDays }} hari</span>
                    </div>

                    @if(!$application?->start_date)
                        <div class="flex flex-col items-center justify-center text-center" style="padding:80px 32px;">
                            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl" style="background:#f1f5f9;border:1px solid #e2e8f0;">
                                <x-heroicon-o-clock class="h-6 w-6" style="color:#047857;" />
                            </div>
                            <p class="text-sm font-semibold" style="color:var(--tesyuk-ink);">Sesi Testing Belum Dimulai</p>
                            <p class="mt-1 max-w-xs text-xs" style="color:#7c6f6f;">
                                Timeline muncul setelah developer memulai sesi testing.
                            </p>
                        </div>
                    @else
                        <div class="misi-timeline-list">
                            @foreach($dailyMissions as $dailyMission)
                                @php
                                    $missionStatus = $dailyMission['status'];

                                    $iconStyle = match ($missionStatus) {
                                        'done' => 'background:#DCFCE7; color:#15803D;',
                                        'pending_review' => 'background:#FEF3C7; color:#92400E;',
                                        'today' => 'background:#047857; color:#FFFFFF;',
                                        'rejected' => 'background:#FEE2E2; color:#B91C1C;',
                                        'missed', 'failed' => 'background:#FFF7ED; color:#9A3412;',
                                        default => 'background:#E2E8F0; color:#7c6f6f;',
                                    };

                                    $badgeStyle = match ($missionStatus) {
                                        'done' => 'background:#DCFCE7; color:#15803D;',
                                        'pending_review' => 'background:#FEF3C7; color:#92400E;',
                                        'today' => 'background:#047857; color:#FFFFFF;',
                                        'rejected' => 'background:#FEE2E2; color:#B91C1C;',
                                        'missed', 'failed' => 'background:#FFF7ED; color:#9A3412;',
                                        default => 'background:#E2E8F0; color:#7c6f6f;',
                                    };

                                    $statusText = match ($missionStatus) {
                                        'done' => 'Selesai',
                                        'pending_review' => 'Menunggu Review',
                                        'today' => 'Hari Ini',
                                        'rejected' => 'Ditolak',
                                        'missed' => 'Terlewat',
                                        'failed' => 'Gugur',
                                        default => 'Terkunci',
                                    };
                                @endphp

                                <div class="misi-day-row {{ $missionStatus === 'today' ? 'is-today' : '' }}">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl"
                                            style="{{ $iconStyle }}">
                                            @if($missionStatus === 'done')
                                                <x-heroicon-o-check-circle class="h-4 w-4" />
                                            @elseif($missionStatus === 'pending_review')
                                                <x-heroicon-o-clock class="h-4 w-4" />
                                            @elseif($missionStatus === 'today')
                                                <x-heroicon-o-play-circle class="h-4 w-4" />
                                            @elseif($missionStatus === 'rejected')
                                                <x-heroicon-o-x-circle class="h-4 w-4" />
                                            @elseif($missionStatus === 'missed')
                                                <x-heroicon-o-x-circle class="h-4 w-4" />
                                            @elseif($missionStatus === 'failed')
                                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                            @else
                                                <x-heroicon-o-lock-closed class="h-4 w-4" />
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium" style="color:var(--tesyuk-ink);">Hari {{ $dailyMission['day'] }}</p>
                                            <p class="text-xs" style="color:#7c6f6f;">{{ $dailyMission['date'] }}</p>
                                            @if($missionStatus === 'rejected' && !empty($dailyMission['rejection_reason']))
                                                <p class="mt-1 max-w-md text-xs leading-relaxed" style="color:#B91C1C;">
                                                    {{ $dailyMission['rejection_reason'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="misi-day-action">
                                        @if($missionStatus === 'today' && $mission->status === 'active')
                                            {{ ($this->laporHarianAction)(['record' => $mission->id, 'report_date' => $dailyMission['date_raw']]) }}
                                        @elseif($missionStatus === 'rejected' && $mission->status === 'active')
                                            {{ ($this->laporHarianAction)(['record' => $mission->id, 'report_date' => $dailyMission['date_raw'], 'retry' => true]) }}
                                        @else
                                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium"
                                                style="{{ $badgeStyle }}">
                                                {{ $statusText }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    @endif
</x-filament-panels::page>
