<x-filament-panels::page>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Sedang Dikerjakan</h2>
                <p class="text-sm text-slate-500">Selesaikan tugas ini sebelum batas waktu habis.</p>
            </div>
            <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full animate-pulse">Sisa Waktu: 2 Hari</span>
        </div>

        <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-blue-900 rounded-xl flex items-center justify-center text-white font-bold text-2xl shadow-md">
                    E
                </div>
                <div>
                    <h3 class="font-bold text-xl text-slate-900">E-Commerce Kasir</h3>
                    <p class="text-sm text-slate-500">Developer: PT Maju Mundur</p>
                </div>
            </div>
            
            <hr class="border-slate-200 my-4">
            
            <h4 class="font-bold text-slate-900 mb-2">Instruksi Pengujian:</h4>
            <ul class="list-disc list-inside text-sm text-slate-600 space-y-1 mb-6">
                <li>Buka aplikasi dan buat akun baru.</li>
                <li>Lakukan simulasi checkout hingga ke halaman pembayaran.</li>
                <li>Screenshot halaman bukti pembayaran jika berhasil.</li>
            </ul>

            <button class="w-full bg-blue-900 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/30">
                <i class="ph-bold ph-bug pr-1"></i> Lapor Bug / Kumpulkan Tugas
            </button>
        </div>
    </div>
</x-filament-panels::page>