<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div>
        <!-- Welcome Banner Khusus Admin -->
        <div class="bg-slate-900 rounded-2xl p-10 text-white mb-8 shadow-xl relative overflow-hidden flex flex-col md:flex-row justify-between items-center">
            <div class="absolute -right-10 -top-10 text-white/5 text-[200px] pointer-events-none">
                <i class="ph-fill ph-shield-check"></i>
            </div>
            
            <div class="relative z-10 md:w-2/3">
                <h1 class="text-3xl md:text-4xl font-extrabold mb-4">Pusat Kendali Admin TesYuk!</h1>
                <p class="text-slate-300 text-sm leading-relaxed mb-0">
                    Pantau kesehatan ekosistem platform. Verifikasi aplikasi baru sebelum diuji, kelola pengguna, dan proses permintaan penarikan saldo e-Wallet dari para Tester.
                </p>
            </div>
            
            <div class="relative z-10 mt-6 md:mt-0">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-xl text-center">
                    <p class="text-xs text-slate-300 font-bold uppercase tracking-wider mb-1">Status Server</p>
                    <div class="flex items-center gap-2 text-emerald-400 font-bold text-lg">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        Aman & Stabil
                    </div>
                </div>
            </div>
        </div>

        <!-- 3 Kartu Statistik Admin -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Menunggu Verifikasi -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-app-window text-3xl text-amber-500"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase">Menunggu Verifikasi</p>
                    <h3 class="font-extrabold text-2xl text-slate-900">12 Aplikasi</h3>
                </div>
            </div>

            <!-- Total Pengguna Aktif -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-users text-3xl text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase">Total Pengguna</p>
                    <h3 class="font-extrabold text-2xl text-slate-900">5 <span class="text-sm text-slate-400">User</span></h3>
                </div>
            </div>

            <!-- Permintaan Pencairan Dana -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="ph-fill ph-money text-3xl text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-500 uppercase">Pencairan Dana</p>
                    <h3 class="font-extrabold text-2xl text-slate-900">5 <span class="text-sm text-slate-400">Pending</span></h3>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>