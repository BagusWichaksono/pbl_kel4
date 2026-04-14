<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Daftar Pengguna</h2>
            <p class="text-sm text-slate-500">Kelola ranking kepercayaan dan status aktif pengguna.</p>
        </div>
        <div class="relative">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" placeholder="Cari nama/email..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Card Pengguna 1 -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xl">
                        A
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900">Fattah Tester</h4>
                        <p class="text-xs text-slate-500">fattah@tester.com</p>
                    </div>
                </div>
                <span class="bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded">Tester</span>
            </div>
            
            <div class="bg-slate-50 p-3 rounded-lg mb-4 border border-slate-100">
                <p class="text-xs text-slate-500 mb-1">Ranking Kepercayaan</p>
                <div class="flex items-center gap-2">
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 98%"></div>
                    </div>
                    <span class="text-xs font-bold text-emerald-600">98%</span>
                </div>
            </div>

            <button class="w-full text-red-600 text-sm font-bold border border-red-200 bg-red-50 hover:bg-red-100 py-2.5 rounded-xl transition">
                <i class="ph-bold ph-prohibit pr-1"></i> Blokir Pengguna
            </button>
        </div>

        <!-- Card Pengguna 2 -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-xl">
                        P
                    </div>
                    <div>
                        <h4 class="font-bold text-slate-900">PT Luis Mdan</h4>
                        <p class="text-xs text-slate-500">dev@luismdan.com</p>
                    </div>
                </div>
                <span class="bg-amber-100 text-amber-700 text-[10px] font-bold uppercase tracking-wider px-2 py-1 rounded">Developer</span>
            </div>
            
            <div class="bg-slate-50 p-3 rounded-lg mb-4 border border-slate-100">
                <p class="text-xs text-slate-500 mb-1">Status Berlangganan</p>
                <p class="text-sm font-bold text-amber-600"><i class="ph-fill ph-crown"></i> VIP Aktif</p>
            </div>

            <button class="w-full text-red-600 text-sm font-bold border border-red-200 bg-red-50 hover:bg-red-100 py-2.5 rounded-xl transition">
                <i class="ph-bold ph-prohibit pr-1"></i> Blokir Pengguna
            </button>
        </div>
    </div>
</x-filament-panels::page>