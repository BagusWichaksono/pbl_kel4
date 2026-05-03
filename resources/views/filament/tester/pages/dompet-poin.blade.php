<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Saldo Utama -->
        <div class="md:col-span-1">
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -top-10 text-white/10 text-[150px]">
                    <i class="ph-fill ph-wallet"></i>
                </div>
                <p class="text-sm font-medium text-slate-300 mb-2 relative z-10">Total Saldo Poin</p>

                <!-- Menampilkan Saldo Dinamis dari Database tester_profiles -->
                @php
                    $totalPoin = \App\Models\TesterProfile::where('user_id', auth()->id())->value('points') ?? 0;
                @endphp

                <h2 class="text-4xl font-extrabold mb-8 relative z-10">
                    {{ number_format($totalPoin, 0, ',', '.') }}
                    <span class="text-lg text-slate-400 font-medium ml-1">Pts</span>
                </h2>

                <button class="w-full bg-white text-slate-900 font-bold py-3 rounded-xl hover:bg-slate-100 transition relative z-10">
                    Cairkan ke e-Wallet
                </button>
            </div>
        </div>

        <!-- Riwayat Transaksi -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm h-full">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Riwayat Poin</h3>

                <div class="space-y-4">
                    <!-- Mengambil data riwayat dari database khusus untuk user yang sedang login -->
                    @php
                        $riwayatPoin = \App\Models\PointHistory::where('tester_id', auth()->id())
                                        ->orderBy('created_at', 'desc')
                                        ->get();
                    @endphp

                    <!-- Looping data riwayat -->
                    @forelse($riwayatPoin as $riwayat)
                        <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center text-emerald-600">
                                    <i class="ph-bold ph-plus"></i>
                                </div>
                                <div>
                                    <!-- Menampilkan Deskripsi Dinamis -->
                                    <h4 class="font-bold text-sm text-slate-900">{{ $riwayat->description }}</h4>
                                    <!-- Menampilkan Tanggal Dinamis -->
                                    <p class="text-xs text-slate-500">{{ $riwayat->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                            <!-- Menampilkan Nominal Dinamis -->
                            <span class="font-bold text-emerald-600">+{{ number_format($riwayat->amount, 0, ',', '.') }} Pts</span>
                        </div>
                    @empty
                        <!-- Pesan jika belum ada riwayat poin -->
                        <div class="text-center py-6">
                            <p class="text-slate-500 text-sm">Belum ada riwayat poin saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
