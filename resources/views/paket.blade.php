<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Paket - TesYuk!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Poppins', 'sans-serif'] } } } }
    </script>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex items-center justify-center p-6">
    <div class="max-w-4xl w-full">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-extrabold text-slate-900">Pilih Langkah Awalmu</h1>
            <p class="text-slate-500 mt-2">Daftar sebagai penguji atau validasi aplikasimu sekarang</p>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm flex flex-col hover:border-blue-300 transition">
                <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center mb-6 text-slate-600">
                    <i class="ph-fill ph-user text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Reguler</h2>
                <p class="text-3xl font-extrabold my-4 text-slate-900">Rp 25.000<span class="text-xs font-normal text-slate-400">/aplikasi</span></p>
                <ul class="text-slate-600 text-sm space-y-4 mb-8 flex-1">
                    <li class="flex items-center gap-3"><i class="ph-bold ph-check text-green-500"></i> Maksimal 12 Tester</li>
                    <li class="flex items-center gap-3"><i class="ph-bold ph-check text-green-500"></i> Laporan Standar</li>
                    <li class="flex items-center gap-3 opacity-40"><i class="ph-bold ph-x text-slate-400"></i> Prioritas Listing</li>
                </ul>
                <a href="/register-custom" class="block w-full py-4 bg-slate-100 text-slate-800 text-center font-bold rounded-xl hover:bg-slate-200 transition">Pilih Reguler</a>
            </div>

            <div class="bg-white p-8 rounded-3xl border-2 border-slate-900 shadow-xl flex flex-col relative transform md:-translate-y-4">
                <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-black tracking-widest px-4 py-1.5 rounded-full uppercase">Most Popular</div>
                <div class="w-12 h-12 bg-slate-900 rounded-2xl flex items-center justify-center mb-6 text-white">
                    <i class="ph-fill ph-crown text-2xl"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900">VIP Developer</h2>
                <p class="text-3xl font-extrabold my-4 text-slate-900">Rp 150.000 <span class="text-xs font-normal text-slate-400">/aplikasi</span></p>
                <ul class="text-slate-600 text-sm space-y-4 mb-8 flex-1">
                    <li class="flex items-center gap-3"><i class="ph-bold ph-check text-green-500"></i> Tester Tak Terbatas</li>
                    <li class="flex items-center gap-3"><i class="ph-bold ph-check text-green-500"></i> Analytics UI/UX Detail</li>
                    <li class="flex items-center gap-3"><i class="ph-bold ph-check text-green-500"></i> Prioritas Halaman Utama</li>
                </ul>
                <a href="/register-custom" class="block w-full py-4 bg-slate-900 text-white text-center font-bold rounded-xl hover:bg-slate-800 transition">Pilih VIP</a>
            </div>
        </div>
    </div>
</body>
</html>