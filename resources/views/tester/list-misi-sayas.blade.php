<x-filament-panels::page>
    <div class="space-y-8">

        @if($missions->isEmpty())
            <div class="rounded-3xl border shadow-sm"
                 style="background: #F3F7FC; border-color: #D9E3EF;">
                <div class="flex flex-col items-center justify-center px-8 py-16 text-center">
                    <div class="mb-5 rounded-2xl p-5" style="background: #DCE8F3;">
                        <x-heroicon-o-clipboard-document-list class="h-10 w-10" style="color: #587BAA;" />
                    </div>

                    <h2 class="text-xl font-bold" style="color: #111936;">
                        Belum Ada Misi Testing
                    </h2>

                    <p class="mt-2 max-w-md text-sm leading-relaxed" style="color: #6B7A90;">
                        Misi akan muncul setelah kamu mendaftar sebagai tester pada aplikasi yang tersedia.
                    </p>
                </div>
            </div>
        @endif

        @foreach($missions as $mission)
            @php
                $application = $mission->application;
                $dailyReportsCount = $mission->daily_reports_count_custom ?? 0;
                $progressPercentage = $mission->progress_percentage ?? 0;
                $dailyMissions = $mission->daily_missions_custom ?? [];

                $statusStyle = match ($mission->status) {
                    'active' => 'background:#DCE8F3;color:#2F4A76;',
                    'completed' => 'background:#DCFCE7;color:#15803D;',
                    'failed', 'dropped' => 'background:#FEE2E2;color:#B91C1C;',
                    default => 'background:#E9EEF5;color:#6B7A90;',
                };

                $statusLabel = match ($mission->status) {
                    'active' => 'Aktif',
                    'completed' => 'Selesai',
                    'failed' => 'Gagal',
                    'dropped' => 'Berhenti',
                    default => $mission->status,
                };
            @endphp

            <div class="overflow-hidden rounded-3xl border shadow-sm"
                 style="background: #F8FAFC; border-color: #D9E3EF;">

                {{-- HEADER --}}
                <div class="p-6 sm:p-7"
                     style="background: linear-gradient(135deg, #111936 0%, #334A78 55%, #587BAA 100%);">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-2xl font-bold tracking-tight text-white">
                                    {{ $application?->title ?? 'Aplikasi tidak ditemukan' }}
                                </h2>

                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold"
                                      style="{{ $statusStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>

                            <div class="mt-4 space-y-2 text-sm" style="color: #DCE8F3;">
                                <p class="flex items-center gap-2">
                                    <x-heroicon-o-user class="h-4 w-4 shrink-0" />
                                    <span>Developer: {{ $application?->developer?->name ?? '-' }}</span>
                                </p>

                                @if($application?->start_date)
                                    <p class="flex items-center gap-2">
                                        <x-heroicon-o-calendar-days class="h-4 w-4 shrink-0" />
                                        <span>
                                            Periode testing:
                                            {{ \Carbon\Carbon::parse($application->start_date)->translatedFormat('d F Y') }}
                                            -
                                            {{ $application?->end_date
                                                ? \Carbon\Carbon::parse($application->end_date)->translatedFormat('d F Y')
                                                : \Carbon\Carbon::parse($application->start_date)->addDays(14)->translatedFormat('d F Y')
                                            }}
                                        </span>
                                    </p>
                                @else
                                    <p class="flex items-center gap-2">
                                        <x-heroicon-o-clock class="h-4 w-4 shrink-0" />
                                        <span>Periode testing belum dimulai oleh developer.</span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="shrink-0">
                            @if($application?->app_link)
                                <a href="{{ $application->app_link }}"
                                   target="_blank"
                                   class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-bold shadow-sm transition"
                                   style="background:#FFFFFF;color:#2F4A76;">
                                    <x-heroicon-o-link class="h-4 w-4" />
                                    Buka Link Testing
                                </a>
                            @else
                                <span class="inline-flex items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-bold"
                                      style="background:rgba(255,255,255,.14);color:#FFFFFF;border:1px solid rgba(255,255,255,.22);">
                                    <x-heroicon-o-lock-closed class="h-4 w-4" />
                                    Link Belum Tersedia
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- CONTENT --}}
                <div class="grid grid-cols-1 gap-6 p-6 sm:p-7 xl:grid-cols-3">

                    {{-- LEFT COLUMN --}}
                    <div class="space-y-6 xl:col-span-1">

                        {{-- PROGRESS --}}
                        <div class="rounded-3xl border bg-white p-6 shadow-sm"
                             style="border-color:#D9E3EF;">
                            <div class="flex items-start justify-between gap-5">
                                <div>
                                    <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-2xl"
                                         style="background:#DCE8F3;color:#2F4A76;">
                                        <x-heroicon-o-chart-bar class="h-5 w-5" />
                                    </div>

                                    <h3 class="text-base font-bold" style="color:#111936;">
                                        Progress Laporan
                                    </h3>

                                    <p class="mt-2 text-sm leading-relaxed" style="color:#6B7A90;">
                                        Kumpulkan 14 laporan harian untuk menyelesaikan misi.
                                    </p>
                                </div>

                                <div class="rounded-2xl px-4 py-3 text-center"
                                     style="background:#F1F5F9;">
                                    <p class="text-2xl font-bold leading-none" style="color:#111936;">
                                        {{ $dailyReportsCount }}/14
                                    </p>
                                    <p class="mt-1 text-xs font-medium" style="color:#6B7A90;">
                                        laporan
                                    </p>
                                </div>
                            </div>

                            <div class="mt-6 h-3 overflow-hidden rounded-full" style="background:#E5EDF6;">
                                <div class="h-3 rounded-full transition-all"
                                     style="width: {{ $progressPercentage }}%; background:#587BAA;">
                                </div>
                            </div>
                        </div>

                        {{-- INSTRUKSI --}}
                        <div class="rounded-3xl border bg-white p-6 shadow-sm"
                             style="border-color:#D9E3EF;">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl"
                                     style="background:#E5EDF6;color:#587BAA;">
                                    <x-heroicon-o-document-text class="h-5 w-5" />
                                </div>

                                <h3 class="text-base font-bold" style="color:#111936;">
                                    Instruksi Testing
                                </h3>
                            </div>

                            <p class="whitespace-pre-line text-sm leading-7" style="color:#6B7A90;">
                                {{ $application?->description ?? 'Belum ada instruksi dari developer.' }}
                            </p>
                        </div>

                        {{-- LAPORAN AKHIR --}}
                        <div class="rounded-3xl border bg-white p-6 shadow-sm"
                             style="border-color:#D9E3EF;">
                            <div class="mb-4 flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-2xl"
                                     style="background:#E5EDF6;color:#587BAA;">
                                    <x-heroicon-s-paper-airplane class="h-5 w-5" />
                                </div>

                                <h3 class="text-base font-bold" style="color:#111936;">
                                    Laporan Akhir
                                </h3>
                            </div>

                            <p class="text-sm leading-7" style="color:#6B7A90;">
                                Laporan akhir hanya bisa dikirim setelah masa testing 14 hari selesai dan kamu sudah mengirim 14 laporan harian.
                            </p>

                            <div class="mt-5">
                                @if($this->canSubmitFinalReport($mission))
                                    {{ ($this->kirimLaporanAkhirAction)(['record' => $mission->id]) }}
                                @else
                                    <button type="button"
                                            disabled
                                            title="{{ $this->finalReportTooltip($mission) }}"
                                            class="inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-bold"
                                            style="background:#E9EEF5;color:#8A98AA;">
                                        <x-heroicon-s-paper-airplane class="h-4 w-4" />
                                        Kirim Laporan Akhir
                                    </button>

                                    <p class="mt-3 rounded-2xl px-4 py-3 text-xs leading-relaxed"
                                       style="background:#F1F5F9;color:#6B7A90;">
                                        {{ $this->finalReportTooltip($mission) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT COLUMN --}}
                    <div class="xl:col-span-2">
                        <div class="overflow-hidden rounded-3xl border bg-white shadow-sm"
                             style="border-color:#D9E3EF;">

                            <div class="border-b px-6 py-5"
                                 style="background:#F1F5F9;border-color:#D9E3EF;">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl"
                                         style="background:#DCE8F3;color:#2F4A76;">
                                        <x-heroicon-o-list-bullet class="h-5 w-5" />
                                    </div>

                                    <div>
                                        <h3 class="text-base font-bold" style="color:#111936;">
                                            Timeline Misi Harian
                                        </h3>

                                        <p class="mt-1 text-sm leading-relaxed" style="color:#6B7A90;">
                                            Kerjakan satu misi setiap hari selama 14 hari.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if(!$application?->start_date)
                                <div class="flex flex-col items-center justify-center px-8 py-16 text-center">
                                    <div class="mb-5 rounded-2xl p-5" style="background:#E5EDF6;">
                                        <x-heroicon-o-clock class="h-9 w-9" style="color:#587BAA;" />
                                    </div>

                                    <p class="text-base font-bold" style="color:#111936;">
                                        Sesi Testing Belum Dimulai
                                    </p>

                                    <p class="mt-2 max-w-md text-sm leading-relaxed" style="color:#6B7A90;">
                                        Timeline misi akan muncul setelah developer memulai sesi testing.
                                    </p>
                                </div>
                            @else
                                <div class="space-y-3 p-5" style="background:#F8FAFC;">
                                    @foreach($dailyMissions as $dailyMission)
                                        @php
                                            $missionStatus = $dailyMission['status'];

                                            $iconStyle = match ($missionStatus) {
                                                'done' => 'background:#DCFCE7;color:#15803D;',
                                                'today' => 'background:#DCE8F3;color:#2F4A76;',
                                                'missed' => 'background:#FEE2E2;color:#B91C1C;',
                                                default => 'background:#E9EEF5;color:#8A98AA;',
                                            };

                                            $badgeStyle = match ($missionStatus) {
                                                'done' => 'background:#DCFCE7;color:#15803D;',
                                                'today' => 'background:#2F4A76;color:#FFFFFF;',
                                                'missed' => 'background:#FEE2E2;color:#B91C1C;',
                                                default => 'background:#E9EEF5;color:#8A98AA;',
                                            };

                                            $statusText = match ($missionStatus) {
                                                'done' => 'Sudah Dikerjakan',
                                                'today' => 'Misi Hari Ini',
                                                'missed' => 'Terlewat',
                                                default => 'Belum Tersedia',
                                            };
                                        @endphp

                                        <div class="rounded-2xl border bg-white px-5 py-4 shadow-sm transition"
                                             style="border-color:#E5EDF6;">
                                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                                <div class="flex items-center gap-4">
                                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl"
                                                         style="{{ $iconStyle }}">
                                                        @if($missionStatus === 'done')
                                                            <x-heroicon-o-check class="h-5 w-5" />
                                                        @elseif($missionStatus === 'today')
                                                            <x-heroicon-o-play class="h-5 w-5" />
                                                        @elseif($missionStatus === 'missed')
                                                            <x-heroicon-o-x-mark class="h-5 w-5" />
                                                        @else
                                                            <x-heroicon-o-lock-closed class="h-5 w-5" />
                                                        @endif
                                                    </div>

                                                    <div>
                                                        <p class="text-sm font-bold" style="color:#111936;">
                                                            Hari {{ $dailyMission['day'] }}
                                                        </p>

                                                        <p class="mt-1 text-sm" style="color:#6B7A90;">
                                                            {{ $dailyMission['date'] }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-3 sm:justify-end">
                                                    @if($missionStatus === 'today' && $mission->status === 'active')
                                                        {{ ($this->laporHarianAction)(['record' => $mission->id]) }}
                                                    @else
                                                        <span class="inline-flex rounded-full px-4 py-1.5 text-xs font-bold"
                                                              style="{{ $badgeStyle }}">
                                                            {{ $statusText }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>