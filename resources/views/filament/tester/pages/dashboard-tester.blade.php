<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div>
        <!-- Welcome Banner -->
        <div class="bg-slate-900 rounded-2xl p-10 text-center text-white mb-8 shadow-xl relative overflow-hidden">
            <!-- Dekorasi Lingkaran -->
            <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -mr-16 -mt-16"></div>
            
            <h1 class="text-3xl md:text-4xl font-extrabold mb-4">Selamat Datang, Tester Jagoan!</h1>
            <p class="text-slate-300 mb-8 max-w-lg mx-auto text-sm leading-relaxed">
                Temukan aplikasi baru, selesaikan misi pengujian, laporkan bug yang kamu temukan, dan kumpulkan poin untuk ditukar dengan saldo e-Wallet!
            </p>
            <a href="/tester/cari-misi" class="inline-flex items-center gap-2 bg-white text-slate-900 px-8 py-3.5 rounded-xl font-bold hover:bg-slate-100 transition shadow-lg">
                <i class="ph-bold ph-magnifying-glass text-lg"></i> Cari Misi Baru
            </a>
        </div>

        <!-- Statistik Tester -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-slate-900 mb-1">Statistik Pengujianmu</h2>
            <p class="text-slate-500 text-sm mb-4">Pantau performa dan poin yang sudah kamu kumpulkan.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card 1: Poin -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="ph-fill ph-coins text-3xl text-amber-500"></i>
                    </div>
                </div>

                <!-- Card 2: Misi -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 text-center shadow-sm hover:shadow-md transition">
                    <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="ph-fill ph-check-circle text-3xl text-blue-600"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>