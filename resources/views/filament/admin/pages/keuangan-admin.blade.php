<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Antrean Pencairan -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                <h3 class="font-bold text-lg text-slate-900 mb-6">Antrean Pencairan e-Wallet</h3>
                
                <div class="space-y-4">
                    <!-- Item Antrean 1 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-slate-300 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 text-xl shadow-sm">
                                <i class="ph-fill ph-wallet"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-lg">kantin sipil</p>
                                <div class="flex items-center gap-2 text-sm text-slate-500">
                                    <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded text-xs font-bold">DANA</span>
                                    <span>0812-3456-7890</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col md:items-end w-full md:w-auto">
                            <p class="font-extrabold text-slate-900 text-xl mb-2">Rp 50.000</p>
                            <button class="w-full md:w-auto bg-slate-900 text-white font-bold px-5 py-2.5 rounded-lg text-sm hover:bg-slate-800 transition shadow-md">
                                <i class="ph-bold ph-check pr-1"></i> Tandai Selesai
                            </button>
                        </div>
                    </div>

                    <!-- Item Antrean 2 -->
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-slate-300 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600 text-xl shadow-sm">
                                <i class="ph-fill ph-wallet"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-lg">alsa</p>
                                <div class="flex items-center gap-2 text-sm text-slate-500">
                                    <span class="bg-slate-200 text-slate-700 px-2 py-0.5 rounded text-xs font-bold">GoPay</span>
                                    <span>0857-9876-5432</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col md:items-end w-full md:w-auto">
                            <p class="font-extrabold text-slate-900 text-xl mb-2">Rp 100.000</p>
                            <button class="w-full md:w-auto bg-slate-900 text-white font-bold px-5 py-2.5 rounded-lg text-sm hover:bg-slate-800 transition shadow-md">
                                <i class="ph-bold ph-check pr-1"></i> Tandai Selesai
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Keuangan -->
        <div class="lg:col-span-1">
            <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 text-white/10 text-[120px] pointer-events-none">
                    <i class="ph-fill ph-coins"></i>
                </div>
                <h3 class="font-bold text-lg mb-6 relative z-10">Ringkasan Sistem</h3>
                
                <div class="space-y-6 relative z-10">
                    <div>
                        <p class="text-sm text-slate-400 mb-1">Total Saldo Tertahan (Tester)</p>
                        <p class="text-2xl font-extrabold text-emerald-400">Rp 1.450.000</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-400 mb-1">Total Pencairan Sukses</p>
                        <p class="text-2xl font-extrabold text-white">Rp 5.200.000</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-filament-panels::page>