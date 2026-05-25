<x-filament-panels::page>
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

        @forelse ($this->groupedReports as $appName => $reportsByDate)
            <div class="overflow-hidden border shadow-sm"; style="border-radius:29px; style="border-color:#D0DAEA; background:#FFFFFF;">

                {{-- HEADER APLIKASI --}}
                <div class="px-6 py-5" style="background: linear-gradient(135deg, #1B2A4A 0%, #2B4C7E 50%, #4A6FA5 100%);">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg"
                            style="background:rgba(255,255,255,0.15);">
                            <x-heroicon-o-clipboard-document-list class="h-5 w-5" style="color:#FFFFFF;" />
                        </div>
                        <div>
                            <p class="text-base font-bold tracking-tight" style="color:#FFFFFF;">{{ $appName }}</p>
                            <p class="text-xs" style="color:#B8CAE0;">
                                {{ $reportsByDate->flatten()->count() }} total laporan masuk
                            </p>
                        </div>
                    </div>
                </div>

                {{-- CARD PER TANGGAL --}}
                <div style="display:flex; flex-direction:column; gap:1rem; padding:1rem; background:#F5F8FC;">
                    @foreach ($reportsByDate as $date => $reports)
                        <div class="overflow-hidden rounded-xl border" style="border-color:#D0DAEA;">

                            {{-- LABEL TANGGAL --}}
                            <div class="flex items-center justify-between px-4 py-3"
                                style="background:#EFF4FB; border-bottom:1px solid #D0DAEA;">
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-calendar-days class="h-3.5 w-3.5" style="color:#4A6FA5;" />
                                    <span class="text-xs font-bold uppercase tracking-widest" style="color:#2B4C7E;">
                                        {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                                    </span>
                                </div>
                                <span class="rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                    style="background:#D0DAEA; color:#2B4C7E;">
                                    {{ $reports->count() }} laporan
                                </span>
                            </div>

                            {{-- LIST TESTER --}}
                            <div style="background:#FFFFFF;">
                                @foreach ($reports as $report)
                                    <div x-data="{ open: false }"
                                        class="flex items-center justify-between px-4 py-4"
                                        style="border-bottom:1px solid #EDF2F7;"
                                        x-on:mouseover="$el.style.background='#F7FAFF'"
                                        x-on:mouseleave="$el.style.background='#FFFFFF'">

                                        {{-- INFO TESTER --}}
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-xs font-bold"
                                                style="background:#D0DAEA; color:#2B4C7E;">
                                                {{ strtoupper(substr($report->tester?->name ?? 'T', 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <p class="text-sm font-semibold" style="color:#1B2A4A;">
                                                        {{ $report->tester?->name ?? 'Tester Tidak Diketahui' }}
                                                    </p>
                                                </div>
                                                <div class="flex items-center gap-1 mt-0.5">
                                                    <x-heroicon-o-clock class="h-3 w-3" style="color:#7B8FAB;" />
                                                    <span class="text-xs" style="color:#7B8FAB;">
                                                        {{ $report->created_at?->format('H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- TOMBOL LIHAT LAPORAN --}}
                                        <button type="button"
                                            x-on:click="open = true"
                                            class="inline-flex items-center gap-1.5 rounded-lg px-3.5 py-2 text-xs font-semibold"
                                            style="background:#EFF4FB; color:#2B4C7E; border:1px solid #D0DAEA;"
                                            x-on:mouseover="$el.style.background='#D0DAEA'"
                                            x-on:mouseleave="$el.style.background='#EFF4FB'">
                                            <x-heroicon-o-eye class="h-3.5 w-3.5" />
                                            Lihat Laporan
                                        </button>

                                        {{-- MODAL --}}
                                        <div x-show="open"
                                            x-on:click.self="open = false"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                            style="background:rgba(27,42,74,0.5); backdrop-filter:blur(4px);"
                                            x-cloak>
                                            <div x-on:click.stop
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95"
                                                class="w-full max-w-2xl overflow-hidden rounded-2xl shadow-2xl"
                                                style="background:#FFFFFF; border:1px solid #D0DAEA;">

                                                {{-- MODAL HEADER --}}
                                                <div class="flex items-start justify-between gap-4 px-6 py-5"
                                                    style="background: linear-gradient(135deg, #1B2A4A 0%, #2B4C7E 50%, #4A6FA5 100%);">
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full text-sm font-bold"
                                                            style="background:rgba(255,255,255,0.2); color:#FFFFFF;">
                                                            {{ strtoupper(substr($report->tester?->name ?? 'T', 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p class="text-base font-bold leading-tight" style="color:#FFFFFF;">
                                                                {{ $report->tester?->name ?? 'Tester Tidak Diketahui' }}
                                                            </p>
                                                            <p class="mt-1 text-xs" style="color:#B8CAE0;">
                                                                {{ $report->created_at?->format('d M Y · H:i') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <button type="button"
                                                        x-on:click="open = false"
                                                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg"
                                                        style="background:rgba(255,255,255,0.15); color:#FFFFFF;"
                                                        x-on:mouseover="$el.style.background='rgba(255,255,255,0.25)'"
                                                        x-on:mouseleave="$el.style.background='rgba(255,255,255,0.15)'">
                                                        <x-heroicon-o-x-mark class="h-5 w-5" />
                                                    </button>
                                                </div>

                                                {{-- MODAL BODY --}}
                                                <div style="max-height:75vh; overflow-y:auto; padding:1.5rem; display:flex; flex-direction:column; gap:1.5rem;">

                                                    {{-- CATATAN --}}
                                                    <div>
                                                        <div class="mb-3 flex items-center gap-2">
                                                            <x-heroicon-o-document-text class="h-4 w-4" style="color:#4A6FA5;" />
                                                            <span class="text-sm font-bold" style="color:#2B4C7E;">Catatan</span>
                                                        </div>
                                                        <div class="rounded-xl px-4 py-4"
                                                            style="background:#EFF4FB; border:1px solid #D0DAEA;">
                                                            <p class="whitespace-pre-line text-sm leading-7" style="color:#1B2A4A;">
                                                                {{ $report->notes ?? 'Tidak ada catatan.' }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    {{-- LAPORAN BUG --}}
                                                    @if(!empty($report->bug_report))
                                                        <div>
                                                            <div class="mb-3 flex items-center gap-2">
                                                                <x-heroicon-o-bug-ant class="h-4 w-4" style="color:#B91C1C;" />
                                                                <span class="text-sm font-bold" style="color:#B91C1C;">Laporan Bug</span>
                                                            </div>
                                                            <div class="rounded-xl px-4 py-4"
                                                                style="background:#FEF2F2; border:1px solid #FECACA;">
                                                                <p class="whitespace-pre-line text-sm leading-7" style="color:#7F1D1D;">
                                                                    {{ $report->bug_report }}
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div>
                                                            <div class="mb-3 flex items-center gap-2">
                                                                <x-heroicon-o-bug-ant class="h-4 w-4" style="color:#4A6FA5;" />
                                                                <span class="text-sm font-bold" style="color:#2B4C7E;">Laporan Bug</span>
                                                            </div>
                                                            <div class="rounded-xl px-4 py-4"
                                                                style="background:#EFF4FB; border:1px solid #D0DAEA;">
                                                                <p class="text-sm" style="color:#7B8FAB;">Tidak ada laporan bug pada hari ini.</p>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- SCREENSHOT --}}
                                                    <div>
                                                        <div class="mb-3 flex items-center gap-2">
                                                            <x-heroicon-o-photo class="h-4 w-4" style="color:#4A6FA5;" />
                                                            <span class="text-sm font-bold" style="color:#2B4C7E;">Screenshot</span>
                                                        </div>
                                                        @if($report->screenshot)
                                                            <div class="overflow-hidden rounded-xl border p-2"
                                                                style="border-color:#D0DAEA; background:#F8FAFC;">
                                                                <img src="{{ Storage::url($report->screenshot) }}"
                                                                    alt="Screenshot laporan"
                                                                    class="w-full rounded-lg object-contain"
                                                                    style="max-height:360px;" />
                                                            </div>
                                                            <a href="{{ Storage::url($report->screenshot) }}"
                                                                target="_blank"
                                                                class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold"
                                                                style="background:#EFF4FB; color:#2B4C7E; border:1px solid #D0DAEA;">
                                                                <x-heroicon-o-arrow-top-right-on-square class="h-4 w-4" />
                                                                Buka di Tab Baru
                                                            </a>
                                                        @else
                                                            <div class="flex flex-col items-center justify-center rounded-xl py-10"
                                                                style="background:#EFF4FB; border:1px dashed #D0DAEA;">
                                                                <x-heroicon-o-photo class="mb-2 h-8 w-8" style="color:#7B8FAB;" />
                                                                <p class="text-sm" style="color:#7B8FAB;">Tidak ada screenshot.</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                </div>
                                                {{-- akhir modal body --}}
                                            </div>
                                        </div>
                                        {{-- akhir modal --}}

                                    </div>
                                    {{-- akhir tester row --}}
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center rounded-2xl border py-16 text-center shadow-sm"
                style="background:#EFF4FB; border-color:#D0DAEA;">
                <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-xl" style="background:#D0DAEA;">
                    <x-heroicon-o-clipboard-document-list class="h-7 w-7" style="color:#4A6FA5;" />
                </div>
                <h2 class="text-base font-bold" style="color:#1B2A4A;">Belum Ada Laporan Harian</h2>
                <p class="mt-2 max-w-xs text-sm" style="color:#7B8FAB;">
                    Laporan akan muncul setelah tester mengirimkan laporan harian mereka.
                </p>
            </div>
        @endforelse

    </div>
</x-filament-panels::page>
