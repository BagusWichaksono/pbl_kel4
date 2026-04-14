<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="bg-slate-900 rounded-2xl p-8 lg:p-12 text-center text-white mb-8 shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
        <h1 class="text-3xl md:text-4xl font-extrabold mb-4 leading-tight">Selamat Datang Developer TesYuk!</h1>
        <p class="text-slate-300 mb-8 max-w-lg mx-auto">Pantau progress validasi aplikasimu secara real-time dan lihat laporan bug dari tester.</p>
        
        <a href="/developer/apps/create" class="inline-block bg-white text-slate-900 px-10 py-3 rounded-xl font-bold hover:bg-slate-100 transition shadow-lg">
            + Upload Aplikasi Baru
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-10 mt-4">
        <div class="lg:w-1/3">
            <h2 class="text-2xl font-bold text-slate-900 mb-3">Statistik Aplikasi</h2>
            <p class="text-slate-500 text-sm">Lihat aplikasi mana saja yang sedang aktif dites oleh para tester.</p>
        </div>
        
        <div class="lg:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm hover:shadow-md transition group">
                <i class="ph-duotone ph-device-mobile text-5xl text-blue-600 mb-4"></i>
                <h3 class="font-bold text-lg text-slate-900">Aplikasi Kasir</h3>
                <p class="text-xs text-slate-500 mb-4">Sedang diuji oleh 12 Tester</p>
                <p class="font-bold text-sm text-green-600">Status: Pengujian Aktif</p>
            </div>
            
            <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm flex flex-col justify-center items-center">
                <i class="ph-duotone ph-crown text-5xl text-amber-500 mb-4"></i>
                <h3 class="font-bold text-lg text-slate-900">Paket VIP</h3>
                <p class="text-xs text-slate-500 mb-4">Akses fitur analitik penuh</p>
                <p class="font-bold text-sm text-blue-600">Status: Tidak Aktif</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>