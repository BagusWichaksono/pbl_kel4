<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    {{-- ══════════════════════════════════════
         x-data: state modal Alpine.js
    ══════════════════════════════════════ --}}
    <div
        class="space-y-6"
        x-data="{
            open: false,
            app: {
                title: '', desc: '', initial: '', color: '',
                developer: '', testers: 0, maxTesters: 0,
                slotLeft: 0, isFull: false, isEnded: false,
                endDate: '', remainingDays: null, reward: 0
            },
            openModal(data) {
                this.app  = data;
                this.open = true;
            },
            closeModal() {
                this.open = false;
            }
        }"
        @keydown.escape.window="closeModal()"
    >
        {{-- ── Header & Search ── --}}
        <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Misi Tersedia</h2>
                <p class="text-sm text-slate-500">Pilih aplikasi yang ingin kamu uji dan kumpulkan poinnya.</p>
            </div>
            <div class="relative w-full md:w-72">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                {{-- wire:model → search di Livewire component --}}
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama aplikasi..."
                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900"
                >
            </div>
        </div>

        {{-- ── Grid Misi ── --}}
        @if($this->applications->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($this->applications as $app)
                    @php
                        $colors     = ['#0f172a','#7c3aed','#2563eb','#059669','#d97706','#dc2626','#0891b2'];
                        $bgColor    = $colors[$app->id % count($colors)];
                        $initial    = strtoupper(substr($app->title, 0, 1));
                        $isRegistered  = $this->isRegistered($app->id);
                        $isFull     = $app->testers_count >= $app->max_testers;
                        $isEnded    = $app->end_date && $app->end_date->isPast();
                        $remainingDays = $app->end_date
                            ? (int) max(0, now()->diffInDays($app->end_date, false))
                            : null;
                        $slotLeft      = $app->max_testers - $app->testers_count;
                        $developerName = $app->developer ? $app->developer->name : 'Unknown Developer';
                        $endDateLabel  = $app->end_date
                            ? $app->end_date->format('d M Y')
                            : 'Tidak ada batas';
                        $reward = $app->reward_points ?? 0;
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-lg transition group">
                        <div>
                            {{-- Icon + Badge status --}}
                            <div class="flex justify-between items-start mb-4">
                                <div
                                    class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-xl"
                                    style="background-color: {{ $bgColor }};"
                                >
                                    {{ $initial }}
                                </div>

                                {{-- Badge status --}}
                                @if($isEnded)
                                    <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full">Berakhir</span>
                                @elseif($isFull)
                                    <span class="bg-slate-100 text-slate-500 text-xs font-bold px-3 py-1 rounded-full">Slot Penuh</span>
                                @elseif($isRegistered)
                                    <span class="bg-blue-100 text-blue-600 text-xs font-bold px-3 py-1 rounded-full">Terdaftar</span>
                                @elseif($remainingDays !== null && $remainingDays <= 3)
                                    <span class="bg-orange-100 text-orange-600 text-xs font-bold px-3 py-1 rounded-full">{{ $remainingDays }}h lagi</span>
                                @else
                                    <span class="bg-emerald-100 text-emerald-600 text-xs font-bold px-3 py-1 rounded-full">Baru</span>
                                @endif
                            </div>

                            {{-- ↓ Judul + deskripsi → klik buka modal --}}
                            <div
                                class="cursor-pointer"
                                @click="openModal({
                                    title:         {{ json_encode($app->title) }},
                                    desc:          {{ json_encode($app->description ?? '') }},
                                    initial:       {{ json_encode($initial) }},
                                    color:         {{ json_encode($bgColor) }},
                                    developer:     {{ json_encode($developerName) }},
                                    testers:       {{ $app->testers_count }},
                                    maxTesters:    {{ $app->max_testers }},
                                    slotLeft:      {{ $slotLeft }},
                                    isFull:        {{ $isFull ? 'true' : 'false' }},
                                    isEnded:       {{ $isEnded ? 'true' : 'false' }},
                                    endDate:       {{ json_encode($endDateLabel) }},
                                    remainingDays: {{ $remainingDays ?? 'null' }},
                                    reward:        {{ $reward }}
                                })"
                            >
                                <h3 class="font-bold text-lg text-slate-900 mb-1 group-hover:text-blue-600 transition">
                                    {{ $app->title }}
                                </h3>
                                <p class="text-sm text-slate-500 mb-1 line-clamp-2">
                                    {{ $app->description ?? 'Tidak ada deskripsi.' }}
                                </p>
                                <span class="inline-flex items-center gap-1 text-xs text-blue-500 font-semibold mb-3 hover:text-blue-700 transition">
                                    <i class="ph ph-info"></i> Lihat detail
                                </span>
                            </div>

                            {{-- Developer --}}
                            <p class="text-xs text-slate-400 flex items-center gap-1 mb-4">
                                <i class="ph ph-user"></i> {{ $developerName }}
                            </p>
                        </div>

                        {{-- Footer card --}}
                        <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                            {{-- Reward poin --}}
                            <div class="flex items-center gap-1 font-bold text-amber-500">
                                <i class="ph-fill ph-coin"></i>
                                {{ number_format($reward, 0, ',', '.') }}
                            </div>

                            {{-- Tombol aksi --}}
                            @if($isRegistered)
                                <span class="flex items-center gap-1 bg-emerald-50 text-emerald-600 border border-emerald-200 text-sm font-bold px-4 py-2 rounded-lg">
                                    <i class="ph ph-check-circle"></i> Terdaftar
                                </span>
                            @elseif($isEnded)
                                <span class="flex items-center gap-1 bg-slate-100 text-slate-400 text-sm font-bold px-4 py-2 rounded-lg">
                                    <i class="ph ph-x-circle"></i> Berakhir
                                </span>
                            @elseif($isFull)
                                <span class="flex items-center gap-1 bg-slate-100 text-slate-400 text-sm font-bold px-4 py-2 rounded-lg">
                                    <i class="ph ph-prohibit"></i> Slot Penuh
                                </span>
                            @else
                                <button
                                    wire:click="daftarMisi({{ $app->id }})"
                                    wire:confirm="Yakin ingin mendaftar sebagai tester di '{{ $app->title }}'?"
                                    class="flex items-center gap-1 bg-slate-900 text-white text-sm font-bold px-4 py-2 rounded-lg hover:bg-slate-800 transition"
                                >
                                    <i class="ph ph-plus"></i> Ambil Misi
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            {{-- Empty state --}}
            <div class="flex flex-col items-center justify-center bg-white rounded-2xl border border-slate-200 py-20 text-center">
                <i class="ph ph-magnifying-glass text-slate-300 text-6xl mb-4"></i>
                <h4 class="font-bold text-slate-600 mb-1">Belum Ada Misi Tersedia</h4>
                <p class="text-sm text-slate-400">Belum ada aplikasi yang siap untuk diuji. Cek kembali nanti ya!</p>
            </div>
        @endif

        {{-- ══════════════════════════════════════
             MODAL DETAIL — Alpine.js
        ══════════════════════════════════════ --}}
        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
            style="background: rgba(15,23,42,0.5); backdrop-filter: blur(4px);"
            @click.self="closeModal()"
            role="dialog"
            aria-modal="true"
        >
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-250"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white rounded-2xl w-full max-w-lg max-h-[85vh] flex flex-col shadow-2xl overflow-hidden"
            >
                {{-- Modal Header --}}
                <div class="flex items-center gap-4 p-6 pb-5 border-b border-slate-100">
                    <div
                        class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-xl flex-shrink-0"
                        x-text="app.initial"
                        :style="'background-color: ' + app.color"
                    ></div>
                    <div class="flex-1 min-w-0">
                        <h2 class="font-bold text-lg text-slate-900 leading-snug" x-text="app.title"></h2>
                        <p class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                            <i class="ph ph-user"></i>
                            <span x-text="app.developer"></span>
                        </p>
                    </div>
                    <button
                        @click="closeModal()"
                        class="w-8 h-8 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-red-50 hover:border-red-200 hover:text-red-500 transition flex-shrink-0"
                        aria-label="Tutup"
                    >
                        <i class="ph ph-x text-sm"></i>
                    </button>
                </div>

                {{-- Modal Badges --}}
                <div class="flex gap-2 flex-wrap px-6 py-3 border-b border-slate-100 bg-slate-50">
                    {{-- Slot badge --}}
                    <span
                        class="text-xs font-bold px-3 py-1 rounded-full"
                        :class="app.isFull
                            ? 'bg-red-100 text-red-600'
                            : 'bg-blue-100 text-blue-600'"
                        x-text="app.isFull ? 'Slot Penuh' : app.slotLeft + ' slot tersisa'"
                    ></span>

                    {{-- Berakhir badge --}}
                    <span
                        x-show="app.isEnded"
                        class="text-xs font-bold px-3 py-1 rounded-full bg-red-100 text-red-600"
                    >Berakhir</span>

                    {{-- Hari lagi badge --}}
                    <span
                        x-show="!app.isEnded && app.remainingDays !== null"
                        x-text="app.remainingDays + ' hari lagi'"
                        class="text-xs font-bold px-3 py-1 rounded-full bg-emerald-100 text-emerald-600"
                    ></span>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-5">

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 text-center">
                            <div class="font-extrabold text-slate-900 text-base leading-none mb-1" x-text="app.testers + '/' + app.maxTesters"></div>
                            <div class="text-xs text-slate-400 font-semibold">Tester</div>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 text-center">
                            <div class="font-extrabold text-slate-900 text-base leading-none mb-1" x-text="app.isFull ? '0' : app.slotLeft"></div>
                            <div class="text-xs text-slate-400 font-semibold">Slot Tersisa</div>
                        </div>
                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-3 text-center">
                            <div class="font-extrabold text-amber-500 text-base leading-none mb-1 flex items-center justify-center gap-1">
                                <i class="ph-fill ph-coin text-sm"></i>
                                <span x-text="app.reward.toLocaleString('id-ID')"></span>
                            </div>
                            <div class="text-xs text-amber-400 font-semibold">Reward Poin</div>
                        </div>
                    </div>

                    {{-- Batas waktu --}}
                    <div class="flex items-center gap-2 text-sm text-slate-600 bg-slate-50 rounded-xl px-4 py-3 border border-slate-100">
                        <i class="ph ph-calendar text-slate-400"></i>
                        <span class="font-semibold text-slate-400">Batas Waktu:</span>
                        <span class="font-bold" x-text="app.endDate"></span>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Deskripsi Aplikasi</p>
                        <p
                            class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap"
                            x-show="app.desc && app.desc.trim() !== ''"
                            x-text="app.desc"
                        ></p>
                        <p
                            class="text-sm text-slate-400 italic"
                            x-show="!app.desc || app.desc.trim() === ''"
                        >Tidak ada deskripsi tersedia.</p>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="p-4 border-t border-slate-100 flex justify-end">
                    <button
                        type="button"
                        @click="closeModal()"
                        class="bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm px-5 py-2.5 rounded-xl transition"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        {{-- END MODAL --}}

    </div>{{-- END x-data --}}
</x-filament-panels::page>