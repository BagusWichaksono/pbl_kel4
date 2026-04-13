<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Developer - TesYuk!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Poppins', 'sans-serif'] } } } }
    </script>
</head>
<body class="bg-[#F8FAFC] text-slate-800 min-h-screen flex flex-col">
    <header class="bg-white border-b border-slate-200 px-8 py-4 flex justify-between items-center sticky top-0 z-10 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-8 h-8 bg-slate-800 rounded-lg"></div>
            <span class="font-bold text-xl text-slate-900">Tes<span class="text-blue-600">Yuk!</span></span>
        </div>
        <nav class="flex items-center gap-6 text-sm font-semibold text-slate-500">
            <a href="/" class="text-slate-900 hidden md:block">Home</a>
            <a href="#" class="hover:text-blue-600 transition hidden md:block">Daftar Aplikasi</a>
            
            <div class="w-px h-6 bg-slate-200 hidden md:block"></div>
            
            <a href="/login" class="text-slate-600 hover:text-slate-900 transition font-bold">Masuk</a>
            
            <a href="/register" class="bg-slate-900 text-white px-5 py-2.5 rounded-xl hover:bg-slate-800 transition shadow-md">Daftar</a>
        </nav>
    </header>

    <div class="flex flex-1">
        <aside class="w-64 bg-white border-r border-slate-200 p-6 space-y-4 hidden md:block">
            <a href="#" class="flex items-center gap-3 p-3 rounded-xl bg-slate-100 text-blue-600 font-semibold border border-slate-200"><i class="ph-fill ph-house text-xl"></i>Dashboard</a>
            <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition text-slate-500"><i class="ph-fill ph-coin text-xl"></i>Pilih Paket</a>
            <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition text-slate-500"><i class="ph-fill ph-user text-xl"></i>Pembayaran</a>
            <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition text-slate-500"><i class="ph-fill ph-coin text-xl"></i>Upload Aplikasi</a>
            <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition text-slate-500"><i class="ph-fill ph-coin text-xl"></i>ACC Tester</a>
            <a href="#" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 transition text-slate-500"><i class="ph-fill ph-coin text-xl"></i>Hasil testing</a>

        </aside>

        <main class="flex-1 p-8 lg:p-12">
            <div class="bg-slate-800 rounded-2xl p-12 text-center text-white mb-12 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                <h1 class="text-3xl md:text-4xl font-extrabold mb-4 leading-tight">Selamat Datang di TesYuk!</h1>
                <p class="text-slate-300 mb-8 max-w-lg mx-auto">Dapatkan kesempatan untuk menguji aplikasi baru sebelum peluncuran</p>
                <a href="/register" class="inline-block bg-white text-slate-900 px-10 py-3 rounded-xl font-bold hover:bg-slate-100 transition shadow-lg">Mulai Mendaftar</a>
            </div>

            <div class="flex flex-col lg:flex-row gap-10">
                <div class="lg:w-1/3">
                    <h2 class="text-2xl font-bold text-slate-900 mb-3">Daftar Aplikasi</h2>
                    <p class="text-slate-500 text-sm">Screenshot dan deskripsi aplikasi yang akan segera diluncurkan</p>
                </div>
                <div class="lg:w-2/3 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-8 rounded-2xl border border-slate-200 text-center shadow-sm hover:shadow-md transition group">
                        <i class="ph-duotone ph-device-mobile text-5xl text-blue-600 mb-4"></i>
                        <h3 class="font-bold text-lg">Aplikasi 1</h3>
                        <p class="text-xs text-slate-400 mb-4">Deskripsi singkat aplikasi</p>
                        <p class="font-bold text-sm text-slate-700">Tersedia untuk testing</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>