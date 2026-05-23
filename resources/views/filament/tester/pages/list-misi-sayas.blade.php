<x-filament-panels::page>
    <div class="space-y-6">

        {{-- EMPTY STATE --}}
        @if($missions->isEmpty())
            <div class="rounded-lg border" style="background:#EFF4FB; border-color:#D0DAEA;">
                <div class="flex flex-col items-center justify-center text-center py-2">
                    <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl" style="background:#D0DAEA;">
                        <x-heroicon-o-clipboard-document-list class="h-7 w-7"/>
                    </div>
                    <h2 class="text-lg font-semibold" style="color:#1B2A4A;">
                        Belum Ada Misi Testing
                    </h2>
                    <p class="mt-2 max-w-sm text-sm" style="color:#7B8FAB;">
                        Misi akan muncul setelah kamu mendaftar sebagai tester pada aplikasi yang tersedia.
                    </p>
                </div>
            </div>
        @endif

        {{-- MISSION CARDS --}}
        @foreach($missions as $mission)
            @php
                $application = $mission->application;
                $dailyReportsCount = $mission->daily_reports_count_custom ?? 0;
                $progressPercentage = $mission->progress_percentage ?? 0;
                $dailyMissions = $mission->daily_missions_custom ?? [];

                $statusStyle = match ($mission->status) {
                    'active' => 'background:#D0DAEA;color:#2B4C7E;',
                    'completed' => 'background:#DCFCE7;color:#15803D;',
                    'failed', 'dropped' => 'background:#FEE2E2;color:#B91C1C;',
                    default => 'background:#E2E8F0;color:#7B8FAB;',
                };

                $statusLabel = match ($mission->status) {
                    'active' => 'Aktif',
                    'completed' => 'Selesai',
                    'failed' => 'Gagal',
                    'dropped' => 'Berhenti',
                    default => $mission->status,
                };
            @endphp

            <div class="overflow-hidden rounded-2xl border shadow-sm" style="border-color:#D0DAEA; background:#FFFFFF;">

                {{-- HEADER --}}
                <div class="px-6 py-5" style="background: linear-gradient(135deg, #1B2A4A 0%, #2B4C7E 50%, #4A6FA5 100%);">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-4">
                                <h2 class="text-xl font-bold tracking-tight" style="color:#FFFFFF;">
                                    {{ $application?->title ?? 'Aplikasi tidak ditemukan' }}
                                </h2>
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold" style="{{ $statusStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="mt-3 flex flex-wrap items-center gap-x-5 gap-y-1.5 text-sm" style="color:#B8CAE0;">
                                <span class="flex items-center gap-1.5">
                                    <x-heroicon-o-user class="h-3.5 w-3.5" />
                                    {{ $application?->developer?->name ?? '-' }}
                                </span>

                                @if($application?->start_date)
                                    <span class="flex items-center gap-1.5">
                                        <x-heroicon-o-calendar-days class="h-3.5 w-3.5" />
                                        {{ \Carbon\Carbon::parse($application->start_date)->translatedFormat('d M Y') }}
                                        {{ $application?->end_date
                                            ? \Carbon\Carbon::parse($application->end_date)->translatedFormat('d M Y')
                                            : \Carbon\Carbon::parse($application->start_date)->addDays(14)->translatedFormat('d M Y')
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

                        <div class="shrink-0 ml-auto flex justify-end">
                            @if($application?->app_link)
                                <a href="{{ $application->app_link }}" target="_blank"
                                    class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold shadow-sm transition hover:opacity-90"
                                    style="background:#FFFFFF; color:#2B4C7E;">
                                    <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                                    Buka Aplikasi
                                </a>
                            @else
                                <span class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium"
                                    style="border:1px solid rgba(255,255,255,0.25); color:#B8CAE0;">
                                    <x-heroicon-o-lock-closed class="h-4 w-4" />
                                    Link Belum Tersedia
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- PROGRESS BAR STRIP --}}
                <div class="flex items-center gap-4 border-b px-6 py-3" style="background:#EFF4FB; border-color:#D0DAEA;">
                    <span class="text-xs font-semibold uppercase tracking-wide" style="color:#2B4C7E;">Progress</span>
                    <div class="h-2 flex-1 overflow-hidden rounded-full" style="background:#D0DAEA;">
                        <div class="h-2 rounded-full transition-all" style="width:{{ $progressPercentage }}%; background:#4A6FA5;"></div>
                    </div>
                    <span class="text-sm font-bold" style="color:#1B2A4A;">{{ $dailyReportsCount }}/14</span>
                </div>

                {{-- CONTENT --}}
                <div class="grid grid-cols-1 xl:grid-cols-3">

                    {{-- LEFT SIDEBAR --}}
                    <div class="border-b xl:col-span-1 xl:border-b-0 xl:border-r" style="border-color:#D0DAEA; padding:24px;">

                        {{-- INSTRUKSI --}}
                        <div class="rounded-xl" style="background:#EFF4FB; padding:20px;">
                            <div class="mb-2.5 flex items-center gap-2">
                                <x-heroicon-o-document-text class="h-4 w-4" style="color:#4A6FA5;" />
                                <h3 class="text-sm font-semibold" style="color:#1B2A4A;">Instruksi Testing</h3>
                            </div>
                            <p class="whitespace-pre-line text-sm leading-relaxed" style="color:#7B8FAB;">{{ $application?->description ?? 'Belum ada instruksi dari developer.' }}</p>
                        </div>

                        {{-- LAPORAN AKHIR --}}
                        <div class="rounded-xl" style="background:#EFF4FB; padding:20px; margin-top:20px;">
                            <div class="mb-2.5 flex items-center gap-2">
                                <x-heroicon-o-paper-airplane class="h-4 w-4" style="color:#4A6FA5;" />
                                <h3 class="text-sm font-semibold" style="color:#1B2A4A;">Laporan Akhir</h3>
                            </div>
                            <p class="mb-2 text-xs leading-relaxed" style="color:#7B8FAB; ">
                                Kirim setelah 14 hari testing & 14 laporan harian selesai.
                            </p>
                            @if($this->canSubmitFinalReport($mission))
                                {{ ($this->kirimLaporanAkhirAction)(['record' => $mission->id]) }}
                            @else
                                <button type="button" disabled
                                    title="{{ $this->finalReportTooltip($mission) }}"
                                    class="w-full cursor-not-allowed rounded-lg px-4 py-2.5 text-xs font-semibold"
                                    style="background:#D0DAEA; color:#7B8FAB;">
                                    <x-heroicon-o-lock-closed class="mr-1 inline h-3.5 w-3.5" />
                                    Kirim Laporan Akhir
                                </button>
                                <p class="mt-2 text-xs leading-relaxed" style="color:#7B8FAB;">
                                    {{ $this->finalReportTooltip($mission) }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- RIGHT: TIMELINE --}}
                    <div class="xl:col-span-2">
                        <div class="flex items-center justify-between border-b" style="border-color:#D0DAEA; padding:16px 24px;">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-list-bullet class="h-4 w-4" style="color:#4A6FA5;" />
                                <h3 class="text-sm font-semibold" style="color:#1B2A4A;">Timeline Misi Harian</h3>
                            </div>
                            <span class="text-xs" style="color:#7B8FAB;">14 hari</span>
                        </div>

                        @if(!$application?->start_date)
                            <div class="flex flex-col items-center justify-center text-center" style="padding:80px 32px;">
                                <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl" style="background:#D0DAEA;">
                                    <x-heroicon-o-clock class="h-6 w-6" style="color:#4A6FA5;" />
                                </div>
                                <p class="text-sm font-semibold" style="color:#1B2A4A;">Sesi Testing Belum Dimulai</p>
                                <p class="mt-1 max-w-xs text-xs" style="color:#7B8FAB;">
                                    Timeline muncul setelah developer memulai sesi testing.
                                </p>
                            </div>
                        @else
                            <div>
                                @foreach($dailyMissions as $dailyMission)
                                    @php
                                        $missionStatus = $dailyMission['status'];

                                        $iconStyle = match ($missionStatus) {
                                            'done' => 'background:#DCFCE7; color:#15803D;',
                                            'today' => 'background:#2B4C7E; color:#FFFFFF;',
                                            'missed' => 'background:#FEE2E2; color:#B91C1C;',
                                            default => 'background:#E2E8F0; color:#7B8FAB;',
                                        };

                                        $badgeStyle = match ($missionStatus) {
                                            'done' => 'background:#DCFCE7; color:#15803D;',
                                            'today' => 'background:#2B4C7E; color:#FFFFFF;',
                                            'missed' => 'background:#FEE2E2; color:#B91C1C;',
                                            default => 'background:#E2E8F0; color:#7B8FAB;',
                                        };

                                        $rowBg = $missionStatus === 'today' ? 'background:#EFF4FB;' : '';

                                        $statusText = match ($missionStatus) {
                                            'done' => 'Selesai',
                                            'today' => 'Hari Ini',
                                            'missed' => 'Terlewat',
                                            default => 'Terkunci',
                                        };
                                    @endphp

                                    <div class="flex items-center justify-between border-b"
                                        style="border-color:#EDF2F7; padding:14px 24px; {{ $rowBg }}">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                                style="{{ $iconStyle }}">
                                                @if($missionStatus === 'done')
                                                    <x-heroicon-o-check-circle class="h-4 w-4" />
                                                @elseif($missionStatus === 'today')
                                                    <x-heroicon-o-play-circle class="h-4 w-4" />
                                                @elseif($missionStatus === 'missed')
                                                    <x-heroicon-o-x-circle class="h-4 w-4" />
                                                @else
                                                    <x-heroicon-o-lock-closed class="h-4 w-4" />
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium" style="color:#1B2A4A;">Hari {{ $dailyMission['day'] }}</p>
                                                <p class="text-xs" style="color:#7B8FAB;">{{ $dailyMission['date'] }}</p>
                                            </div>
                                        </div>

                                        <div>
                                            @if($missionStatus === 'today' && $mission->status === 'active')
                                                {{ ($this->laporHarianAction)(['record' => $mission->id]) }}
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
        @endforeach
    </div>
</x-filament-panels::page>