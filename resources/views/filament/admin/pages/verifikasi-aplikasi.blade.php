<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Menunggu Verifikasi</h3>
                <p class="text-sm text-slate-500">Daftar aplikasi yang baru diunggah oleh Developer.</p>
            </div>
            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold">12 Antrean</span>
        </div>
        
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-900 uppercase font-bold text-xs">
                <tr>
                    <th class="px-6 py-4">Nama Aplikasi</th>
                    <th class="px-6 py-4">Developer</th>
                    <th class="px-6 py-4">Platform</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">Kalkulator Pintar</div>
                        <div class="text-xs text-slate-500">Diunggah 2 jam lalu</div>
                    </td>
                    <td class="px-6 py-4">PT Luis Mdan</td>
                    <td class="px-6 py-4"><span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Android (APK)</span></td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <button class="bg-emerald-500 text-white px-4 py-2 rounded-lg font-bold text-xs hover:bg-emerald-600 transition shadow-sm"><i class="ph-bold ph-check pr-1"></i> Setujui</button>
                        <button class="bg-red-500 text-white px-4 py-2 rounded-lg font-bold text-xs hover:bg-red-600 transition shadow-sm"><i class="ph-bold ph-x pr-1"></i> Tolak</button>
                    </td>
                </tr>
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">E-Commerce Kasir</div>
                        <div class="text-xs text-slate-500">Diunggah 5 jam lalu</div>
                    </td>
                    <td class="px-6 py-4">IndoTech Dev</td>
                    <td class="px-6 py-4"><span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">Web App</span></td>
                    <td class="px-6 py-4 flex justify-center gap-2">
                        <button class="bg-emerald-500 text-white px-4 py-2 rounded-lg font-bold text-xs hover:bg-emerald-600 transition shadow-sm"><i class="ph-bold ph-check pr-1"></i> Setujui</button>
                        <button class="bg-red-500 text-white px-4 py-2 rounded-lg font-bold text-xs hover:bg-red-600 transition shadow-sm"><i class="ph-bold ph-x pr-1"></i> Tolak</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</x-filament-panels::page>