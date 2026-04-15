<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TesYuk! - Platform Pengujian Aplikasi Terpercaya</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = { 
            theme: { 
                extend: { 
                    fontFamily: { sans: ['Poppins', 'sans-serif'] }
                } 
            } 
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-x-hidden">

    <nav class="fixed w-full top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="flex items-center group">
                    <img src="{{ asset('assets/logo.png') }}" alt="Logo TesYuk!" class="h-12 w-auto group-hover:scale-105 transition-transform">
                    <span class="font-extrabold text-2xl text-slate-900 tracking-tight">TesYuk!</span>
                </a>

                <div class="hidden md:flex space-x-8">
                    <a href="#fitur" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Fitur Utama</a>
                    <a href="#cara-kerja" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Cara Kerja</a>
                    <a href="/paket" class="text-sm font-semibold text-slate-600 hover:text-slate-900 transition">Harga Developer</a>
                </div>

                <div class="flex items-center gap-4">
                    <a href="/login" class="hidden md:block text-sm font-bold text-slate-700 hover:text-slate-900 transition">Masuk</a>
                    <a href="/register" class="bg-slate-900 text-white text-sm font-bold px-6 py-2.5 rounded-full hover:bg-slate-800 transition shadow-lg shadow-slate-900/20">
                        Mulai Sekarang
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-12 translate-x-1/3">
            <div class="w-96 h-96 bg-amber-200/40 rounded-full blur-3xl"></div>
        </div>
        <div class="absolute bottom-0 left-0 translate-y-1/3 -translate-x-1/3">
            <div class="w-96 h-96 bg-blue-200/40 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            
            <h1 class="text-5xl lg:text-7xl font-black text-slate-900 tracking-tight leading-[1.1] mb-6">
                Validasi Aplikasimu <br class="hidden lg:block" />
                dengan <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-amber-700">Pengguna Nyata.</span>
            </h1>
            
            <p class="text-lg lg:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                Platform inovatif yang mempertemukan Developer untuk mendapatkan <i class="italic">feedback</i> berkualitas, dan Tester untuk menghasilkan pendapatan tambahan dari setiap bug yang ditemukan.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/register?role=tester" class="w-full sm:w-auto bg-slate-900 text-white text-lg font-bold px-8 py-4 rounded-2xl hover:bg-slate-800 transition shadow-xl shadow-slate-900/20 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-magnifying-glass"></i> Cari Misi Testing
                </a>
            </div>

            <div class="mt-20 pt-10 border-t border-slate-200 grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
                <div>
                    <h3 class="text-3xl font-black text-slate-900">50+</h3>
                    <p class="text-sm font-medium text-slate-500">Aplikasi Diuji</p>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-900">1k+</h3>
                    <p class="text-sm font-medium text-slate-500">Tester Aktif</p>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-900">Rp 10jt+</h3>
                    <p class="text-sm font-medium text-slate-500">Poin Dicairkan</p>
                </div>
                <div>
                    <h3 class="text-3xl font-black text-slate-900">99%</h3>
                    <p class="text-sm font-medium text-slate-500">Bebas Bug Rilis</p>
                </div>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="py-20 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4">Bagaimana Cara Kerjanya?</h2>
                <p class="text-slate-500 max-w-xl mx-auto text-lg">Tiga langkah mudah untuk memulai perjalananmu di TesYuk!.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <div class="hidden md:block absolute top-[40%] left-[15%] right-[15%] h-0.5 bg-slate-200 -z-0"></div>

                <div class="relative z-10 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-center hover:-translate-y-2 transition-transform">
                    <div class="w-16 h-16 bg-slate-900 text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-md border-4 border-slate-50">1</div>
                    <h3 class="font-bold text-xl text-slate-900 mb-2">Daftar & Pilih Peran</h3>
                    <p class="text-slate-500 text-sm">Buat akunmu secara gratis. Pilih apakah kamu ingin menjadi Tester yang mencari cuan, atau Developer yang butuh feedback.</p>
                </div>

                <div class="relative z-10 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-center hover:-translate-y-2 transition-transform">
                    <div class="w-16 h-16 bg-amber-500 text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-md border-4 border-slate-50">2</div>
                    <h3 class="font-bold text-xl text-slate-900 mb-2">Mulai Misi Pengujian</h3>
                    <p class="text-slate-500 text-sm">Developer memposting aplikasi mereka beserta misi. Tester memilih aplikasi dari katalog dan mulai melakukan pengujian.</p>
                </div>

                <div class="relative z-10 bg-white p-8 rounded-3xl border border-slate-200 shadow-sm text-center hover:-translate-y-2 transition-transform">
                    <div class="w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center text-2xl font-black mx-auto mb-6 shadow-md border-4 border-slate-50">3</div>
                    <h3 class="font-bold text-xl text-slate-900 mb-2">Validasi & Hasil</h3>
                    <p class="text-slate-500 text-sm">Developer mendapatkan insight berharga & laporan bug. Tester mendapatkan poin yang bisa dicairkan langsung ke e-Wallet!</p>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-black text-slate-900 mb-4">Satu Platform, Dua Solusi Utama.</h2>
                <p class="text-slate-500 max-w-xl mx-auto text-lg">Pilih peranmu dan nikmati ekosistem pengujian yang saling menguntungkan.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <div class="bg-amber-50 rounded-[2rem] p-10 border border-amber-100 relative overflow-hidden group hover:shadow-lg transition">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-amber-200/50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
                    
                    <div class="w-16 h-16 bg-amber-500 text-white rounded-2xl flex items-center justify-center text-3xl mb-8 relative z-10 shadow-lg shadow-amber-500/30">
                        <i class="ph-fill ph-code"></i>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-slate-900 mb-4 relative z-10">Bagi Developer</h3>
                    <ul class="space-y-4 mb-8 relative z-10">
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check-circle text-amber-500 text-xl mt-0.5"></i>
                            <span class="text-slate-700 font-medium">Validasi aplikasi sebelum rilis resmi ke Play Store.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check-circle text-amber-500 text-xl mt-0.5"></i>
                            <span class="text-slate-700 font-medium">Dapatkan feedback nyata dan laporan bug lengkap dengan screenshot.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check-circle text-amber-500 text-xl mt-0.5"></i>
                            <span class="text-slate-700 font-medium">Buat skenario (Katalog Misi) agar tester fokus pada fitur tertentu.</span>
                        </li>
                    </ul>
                    <a href="/paket" class="inline-flex items-center gap-2 text-amber-600 font-bold hover:text-amber-700 transition relative z-10">
                        Lihat Paket Developer <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>

                <div class="bg-blue-50 rounded-[2rem] p-10 border border-blue-100 relative overflow-hidden group hover:shadow-lg transition">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-blue-200/50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
                    
                    <div class="w-16 h-16 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl mb-8 relative z-10 shadow-lg shadow-blue-600/30">
                        <i class="ph-fill ph-device-mobile"></i>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-slate-900 mb-4 relative z-10">Bagi Tester</h3>
                    <ul class="space-y-4 mb-8 relative z-10">
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check-circle text-blue-600 text-xl mt-0.5"></i>
                            <span class="text-slate-700 font-medium">Coba aplikasi eksklusif yang belum rilis di pasaran.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check-circle text-blue-600 text-xl mt-0.5"></i>
                            <span class="text-slate-700 font-medium">Selesaikan misi harian dan kumpulkan poin reward yang melimpah.</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="ph-bold ph-check-circle text-blue-600 text-xl mt-0.5"></i>
                            <span class="text-slate-700 font-medium">Tukarkan poin langsung menjadi saldo e-Wallet (DANA, GoPay, OVO).</span>
                        </li>
                    </ul>
                    <a href="/register?role=tester" class="inline-flex items-center gap-2 text-blue-600 font-bold hover:text-blue-700 transition relative z-10">
                        Mulai Cari Cuan <i class="ph-bold ph-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="bg-slate-900 rounded-[3rem] p-12 text-center text-white shadow-2xl relative overflow-hidden">
                <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-white/5 rounded-full blur-2xl"></div>
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/5 rounded-full blur-2xl"></div>
                
                <h2 class="text-4xl font-black mb-6 relative z-10">Siap Mengubah Cara Menguji Aplikasi?</h2>
                <p class="text-slate-300 text-lg mb-10 max-w-2xl mx-auto relative z-10">
                    Bergabunglah sekarang! Platform pengujian terbaik yang memastikan aplikasimu bebas bug dan siap bersaing di pasar digital.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 relative z-10">
                    <a href="/register" class="bg-white text-slate-900 font-bold px-8 py-4 rounded-xl hover:bg-slate-100 transition shadow-lg">
                        Buat Akun Gratis
                    </a>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <a href="/" class="flex items-center justify-center gap-2 mb-6">
                <span class="font-extrabold text-xl text-slate-900">TesYuk!</span>
            </a>
            <p class="text-slate-500 font-medium mb-8">Platform Pengujian Aplikasi Terpercaya untuk Developer & Tester</p>
            <div class="flex justify-center gap-6 mb-8 text-slate-400">
                <a href="#" class="hover:text-slate-900 transition"><i class="ph-fill ph-instagram-logo text-2xl"></i></a>
                <a href="#" class="hover:text-slate-900 transition"><i class="ph-fill ph-github-logo text-2xl"></i></a>
                <a href="#" class="hover:text-slate-900 transition"><i class="ph-fill ph-envelope-simple text-2xl"></i></a>
            </div>
            <div class="border-t border-slate-100 pt-8">
                <p class="text-sm text-slate-400">© 2026 TesYuk!. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>