<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="space-y-6">
        <!-- Header & Search -->
        <div class="flex flex-col md:flex-row gap-4 justify-between items-start md:items-center bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Misi Tersedia</h2>
                <p class="text-sm text-slate-500">Pilih aplikasi yang ingin kamu uji dan kumpulkan poinnya.</p>
            </div>
            <div class="relative w-full md:w-72">
                <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" placeholder="Cari nama aplikasi..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-slate-900">
            </div>
        </div>

        <!-- Grid Misi -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Dummy Card Misi -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 flex flex-col justify-between hover:shadow-lg transition cursor-pointer group">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-slate-900 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                            K
                        </div>
                        <span class="bg-emerald-100 text-emerald-600 text-xs font-bold px-3 py-1 rounded-full">Baru</span>
                    </div>
                    <h3 class="font-bold text-lg text-slate-900 mb-1 group-hover:text-blue-600 transition">Kalkulator Pintar</h3>
                    <p class="text-sm text-slate-500 mb-4 line-clamp-2">Uji fitur kalkulator saintifik pada perangkat Android dan pastikan tidak ada lag saat animasi.</p>
                </div>
                <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-1 font-bold text-amber-500">
                        <i class="ph-fill ph-coin"></i> 2.500
                    </div>
                    <button class="bg-slate-900 text-white text-sm font-bold px-4 py-2 rounded-lg hover:bg-slate-800 transition">Ambil Misi</button>
                </div>
            </div>
            
            <!-- Tambahkan dummy card lain jika perlu -->
        </div>
    </div>
</x-filament-panels::page>