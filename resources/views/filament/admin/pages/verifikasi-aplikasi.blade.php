<x-filament-panels::page>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    {{-- 
         x-data: state modal Alpine.js
     --}}
    <div
        class="space-y-4"
        x-data="{
            open: false,
            app: {
                title: '', developer: '', uploadedAt: '',
                paymentStatus: '', paymentProof: '',
                description: '', platform: '',
                startDate: '', endDate: ''
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

        {{-- ── Header ── --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Verifikasi Aplikasi</h3>
                    <p class="text-sm text-slate-500">Kelola verifikasi pembayaran aplikasi dari Developer.</p>
                </div>
                @if($this->pendingCount > 0)
                    <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold whitespace-nowrap">
                        {{ $this->pendingCount }} Antrean
                    </span>
                @endif
            </div>

            {{-- ── Filter Tabs ── --}}
            <div class="flex gap-2 px-6 py-3 border-b border-slate-200 bg-white flex-wrap">
                @foreach(['semua' => 'Semua', 'pending' => 'Pending', 'valid' => 'Valid', 'invalid' => 'Invalid'] as $val => $label)
                    <button
                        wire:click="setFilter('{{ $val }}')"
                        class="px-4 py-1.5 rounded-lg text-xs font-bold border transition
                            {{ $filter === $val
                                ? 'bg-slate-900 text-white border-slate-900'
                                : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50 hover:text-slate-900' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- ── Table ── --}}
            @if($this->applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 border-b border-slate-200 text-slate-900 uppercase font-bold text-xs">
                            <tr>
                                <th class="px-6 py-4">Nama Aplikasi</th>
                                <th class="px-6 py-4">Developer</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($this->applications as $app)
                                @php
                                    $developerName = $app->developer ? $app->developer->name : '-';
                                    $proofUrl = $app->payment_proof
                                        ? asset('storage/' . $app->payment_proof)
                                        : '';
                                    $startDateLabel = $app->start_date
                                        ? $app->start_date->format('d M Y')
                                        : '-';
                                    $endDateLabel = $app->end_date
                                        ? $app->end_date->format('d M Y')
                                        : '-';
                                @endphp
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition">

                                    {{-- Nama --}}
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $app->title }}</div>
                                        <div class="text-xs text-slate-400">{{ $app->created_at->diffForHumans() }}</div>
                                    </td>

                                    {{-- Developer --}}
                                    <td class="px-6 py-4">{{ $developerName }}</td>

                                    {{-- Status --}}
                                    <td class="px-6 py-4">
                                        @if($app->payment_status === 'pending')
                                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                                        @elseif($app->payment_status === 'valid')
                                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Valid</span>
                                        @elseif($app->payment_status === 'invalid')
                                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs font-bold">Invalid</span>
                                        @endif
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center items-center gap-2 flex-wrap">

                                            {{-- Tombol Detail → buka modal Alpine --}}
                                            <button
                                                @click="openModal({
                                                    title:         {{ json_encode($app->title) }},
                                                    developer:     {{ json_encode($developerName) }},
                                                    uploadedAt:    {{ json_encode($app->created_at->format('d M Y, H:i')) }},
                                                    paymentStatus: {{ json_encode($app->payment_status) }},
                                                    paymentProof:  {{ json_encode($proofUrl) }},
                                                    description:   {{ json_encode($app->description ?? '') }},
                                                    startDate:     {{ json_encode($startDateLabel) }},
                                                    endDate:       {{ json_encode($endDateLabel) }}
                                                })"
                                                class="flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg font-bold text-xs transition shadow-sm"
                                            >
                                                <i class="ph-bold ph-eye"></i> Detail
                                            </button>

                                            @if($app->payment_status === 'pending')
                                                <button
                                                    wire:click="setujui({{ $app->id }})"
                                                    wire:confirm="Yakin ingin menyetujui pembayaran '{{ $app->title }}'?"
                                                    class="flex items-center gap-1 bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-2 rounded-lg font-bold text-xs transition shadow-sm"
                                                >
                                                    <i class="ph-bold ph-check"></i> Setujui
                                                </button>
                                                <button
                                                    wire:click="tolak({{ $app->id }})"
                                                    wire:confirm="Yakin ingin menolak pembayaran '{{ $app->title }}'?"
                                                    class="flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg font-bold text-xs transition shadow-sm"
                                                >
                                                    <i class="ph-bold ph-x"></i> Tolak
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            @else
                {{-- Empty state --}}
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <i class="ph ph-files text-slate-300 text-6xl mb-4"></i>
                    <h4 class="font-bold text-slate-600 mb-1">Tidak Ada Data</h4>
                    <p class="text-sm text-slate-400">Belum ada aplikasi dengan status "{{ $filter }}".</p>
                </div>
            @endif
        </div>

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
                class="bg-white rounded-2xl w-full max-w-xl max-h-[90vh] flex flex-col shadow-2xl overflow-hidden"
            >
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-6 pb-5 border-b border-slate-100">
                    <div>
                        <h2 class="font-bold text-lg text-slate-900" x-text="app.title"></h2>
                        <p class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                            <i class="ph ph-user"></i>
                            <span x-text="app.developer"></span>
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Badge status --}}
                        <span
                            class="px-3 py-1 rounded-full text-xs font-bold"
                            :class="{
                                'bg-amber-100 text-amber-700':  app.paymentStatus === 'pending',
                                'bg-emerald-100 text-emerald-700': app.paymentStatus === 'valid',
                                'bg-red-100 text-red-600':      app.paymentStatus === 'invalid'
                            }"
                            x-text="app.paymentStatus === 'pending' ? 'Pending'
                                   : app.paymentStatus === 'valid'   ? 'Valid'
                                   : 'Invalid'"
                        ></span>
                        <button
                            @click="closeModal()"
                            class="w-8 h-8 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-500 hover:bg-red-50 hover:border-red-200 hover:text-red-500 transition"
                            aria-label="Tutup"
                        >
                            <i class="ph ph-x text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 overflow-y-auto p-6 space-y-5">

                    {{-- Info grid --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                            <p class="text-xs text-slate-400 font-semibold mb-1">Tanggal Upload</p>
                            <p class="text-sm font-bold text-slate-800" x-text="app.uploadedAt"></p>
                        </div>
                        <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                            <p class="text-xs text-slate-400 font-semibold mb-1">Periode Testing</p>
                            <p class="text-sm font-bold text-slate-800">
                                <span x-text="app.startDate"></span>
                                <span x-show="app.startDate !== '-'"> — <span x-text="app.endDate"></span></span>
                            </p>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Deskripsi / Skenario Pengujian</p>
                        <div class="bg-slate-50 rounded-xl border border-slate-100 p-4">
                            <p
                                class="text-sm text-slate-600 leading-relaxed whitespace-pre-wrap"
                                x-show="app.description && app.description.trim() !== ''"
                                x-text="app.description"
                            ></p>
                            <p
                                class="text-sm text-slate-400 italic"
                                x-show="!app.description || app.description.trim() === ''"
                            >Tidak ada deskripsi.</p>
                        </div>
                    </div>

                    {{-- Bukti Pembayaran --}}
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Bukti Pembayaran</p>
                        <template x-if="app.paymentProof">
                            <img
                                :src="app.paymentProof"
                                alt="Bukti Pembayaran"
                                class="w-full rounded-xl border border-slate-200 shadow-sm object-contain max-h-72"
                            >
                        </template>
                        <template x-if="!app.paymentProof">
                            <div class="flex flex-col items-center justify-center bg-slate-50 rounded-xl border border-slate-200 py-10 text-slate-400">
                                <i class="ph ph-image text-4xl mb-2"></i>
                                <p class="text-sm">Belum ada bukti pembayaran.</p>
                            </div>
                        </template>
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