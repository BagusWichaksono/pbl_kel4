<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TesYuk! - Platform Pengujian Aplikasi Terpercaya</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        winter: {
                            900: '#141c33', // Big Stone
                            700: '#2f456f', // San Juan
                            500: '#5374ac', // Wedgewood
                            300: '#8bafd0', // Polo Blue
                            50: '#eff5fa', // Black Squeeze
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    },
                    animation: {
                        'float': 'float 5s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translateY(0)'
                            },
                            '50%': {
                                transform: 'translateY(-15px)'
                            },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* iPhone Dynamic Island Style */
        .dynamic-island {
            width: 85px;
            height: 25px;
            background: #000;
            border-radius: 20px;
            position: absolute;
            top: 12px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 50;
        }

        /* Glassmorphism Background Pattern */
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: radial-gradient(circle, rgba(139, 175, 208, 0.1) 1px, transparent 1px);
        }

        /* --- Gradasi Teks Kustom --- */

        /* Gradasi untuk "Tes" (Gaya Gelap ke Sedang) */
        .text-gradient-tes {
            background: linear-gradient(to right, #141c33, #2f456f);
            /* winter-900 ke winter-700 */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        /* Gradasi untuk "Yuk!" (Gaya Sedang ke Terang) */
        .text-gradient-yuk {
            background: linear-gradient(to right, #5374ac, #8bafd0);
            /* winter-500 ke winter-300 */
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        nav a {
            letter-spacing: 0.15em;
        }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
</head>

<body id="page-content" class="bg-winter-50 text-winter-900 font-sans antialiased overflow-x-hidden bg-grid-pattern opacity-0 transition-opacity duration-500 ease-in-out" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <nav
        class="fixed w-full top-0 z-[1000] transition-all duration-500"
        :class="scrolled ? 'bg-white/70 backdrop-blur-lg border-b border-winter-300/20 py-3 shadow-lg' : 'bg-transparent py-6'">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex justify-between items-center">
            <a href="/" class="flex items-center gap-3 group">
                <img src="{{ asset('assets/logo.png') }}" class="w-10 h-10 transition-transform group-hover:rotate-6" alt="Logo">

                <div class="h-6 w-px bg-winter-300"></div>

                <span class="font-extrabold text-2xl tracking-tighter flex items-baseline">
                    <span class="text-gradient-tes">Tes</span>
                    <span class="text-gradient-yuk">Yuk!</span>
                </span>
            </a>

            <div class="hidden md:flex items-center p-1.5 bg-winter-100/50 backdrop-blur-md rounded-full border border-winter-300/20">
                <a href="#tentang" class="px-6 py-2 text-xs font-black uppercase tracking-widest text-winter-700 rounded-full transition-all duration-300 hover:bg-winter-500 hover:text-white hover:shadow-md active:scale-95">
                    Tentang
                </a>
                <a href="#fitur" class="px-6 py-2 text-xs font-black uppercase tracking-widest text-winter-700 rounded-full transition-all duration-300 hover:bg-winter-500 hover:text-white hover:shadow-md active:scale-95">
                    Fitur
                </a>
                <a href="#cara-kerja" class="px-6 py-2 text-xs font-black uppercase tracking-widest text-winter-700 rounded-full transition-all duration-300 hover:bg-winter-500 hover:text-white hover:shadow-md active:scale-95">
                    Cara Kerja
                </a>
            </div>

            <div class="flex items-center gap-6">
                <a href="/login" class="text-sm font-bold text-winter-700 hover:text-winter-900 transition">Masuk</a>
                <a href="/register" class="bg-winter-900 text-white text-sm font-bold px-7 py-3 rounded-full hover:bg-winter-700 transition-all shadow-xl shadow-winter-900/20 active:scale-95">
                    Buat Akun
                </a>
            </div>
        </div>
    </nav>

    <section class="relative pt-48 pb-20 overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[700px] h-[700px] bg-winter-300/20 rounded-full blur-[150px] -z-10"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center" data-aos="fade-up">
            <h1 class="text-5xl lg:text-[72px] font-black leading-[1.1] tracking-tight mb-8">
                Validasi Aplikasi <br />
                dengan <span class="text-winter-500">User Nyata.</span>
            </h1>
            <p class="text-lg text-winter-700/70 max-w-2xl mx-auto mb-16 font-medium">
                Ekosistem pengujian aplikasi paling transparan. Bantu Developer menyempurnakan sistem dan dapatkan apresiasi finansial di setiap misi.
            </p>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">

                <div class="hidden lg:flex lg:col-span-4 flex-col gap-16 text-right" data-aos="fade-right" data-aos-delay="200">
                    <div class="group">
                        <div class="w-14 h-14 bg-white shadow-xl rounded-2xl flex items-center justify-center text-winter-500 ml-auto mb-5 border border-winter-300/20 group-hover:bg-winter-500 group-hover:text-white transition-all">
                            <i class="ph-fill ph-target text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">Target Presisi</h4>
                        <p class="text-sm text-winter-700/60 leading-relaxed">Pengujian dilakukan oleh tester yang sesuai dengan profil target pengguna Anda.</p>
                    </div>
                    <div class="group">
                        <div class="w-14 h-14 bg-white shadow-xl rounded-2xl flex items-center justify-center text-winter-500 ml-auto mb-5 border border-winter-300/20 group-hover:bg-winter-500 group-hover:text-white transition-all">
                            <i class="ph-fill ph-chart-pie-slice text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">Analisis Perilaku</h4>
                        <p class="text-sm text-winter-700/60 leading-relaxed">Pahami bagaimana pengguna sebenarnya berinteraksi dengan fitur aplikasi Anda.</p>
                    </div>
                </div>

                <div class="lg:col-span-4 flex justify-center relative animate-float">
                    <div class="absolute inset-0 bg-winter-500/20 blur-[100px] rounded-full scale-75 -z-10"></div>
                    <div class="w-[300px] h-[620px] bg-white rounded-[3.5rem] border-[10px] border-winter-900 shadow-2xl relative overflow-hidden flex flex-col ring-8 ring-winter-900/5">
                        <div class="dynamic-island"></div>

                        <div class="bg-winter-700 pt-14 pb-8 px-6 text-white text-left">
                            <p class="text-[10px] text-winter-300 font-bold uppercase tracking-widest">Saldo Poin</p>
                            <h3 class="text-3xl font-black mt-1">2.450 <span class="text-xs font-normal">pts</span></h3>
                        </div>

                        <div class="flex-1 bg-winter-50 p-5 space-y-4 text-left">
                            <div class="bg-white p-4 rounded-2xl shadow-sm border border-winter-300/10">
                                <h5 class="text-[11px] font-bold text-winter-900 mb-3">Misi Sedang Berjalan</h5>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-winter-900 rounded-xl flex items-center justify-center text-white font-bold">E</div>
                                    <div class="flex-1">
                                        <div class="w-full bg-winter-300/20 h-1.5 rounded-full">
                                            <div class="bg-winter-500 h-full rounded-full" style="width: 70%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-white p-4 rounded-2xl shadow-sm border border-winter-300/10 opacity-60">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-winter-300 rounded-xl"></div>
                                    <div class="h-2 w-20 bg-winter-300 rounded"></div>
                                </div>
                            </div>
                            <button class="w-full py-3 bg-winter-900 text-white rounded-xl text-xs font-bold mt-auto shadow-lg">Ambil Reward</button>
                        </div>
                    </div>
                </div>

                <div class="hidden lg:flex lg:col-span-4 flex-col gap-16 text-left" data-aos="fade-left" data-aos-delay="200">
                    <div class="group">
                        <div class="w-14 h-14 bg-white shadow-xl rounded-2xl flex items-center justify-center text-winter-500 mb-5 border border-winter-300/20 group-hover:bg-winter-500 group-hover:text-white transition-all">
                            <i class="ph-fill ph-shield-check text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">Proteksi Aset</h4>
                        <p class="text-sm text-winter-700/60 leading-relaxed">Kerahasiaan data aplikasi Anda adalah prioritas utama keamanan sistem kami.</p>
                    </div>
                    <div class="group">
                        <div class="w-14 h-14 bg-white shadow-xl rounded-2xl flex items-center justify-center text-winter-500 mb-5 border border-winter-300/20 group-hover:bg-winter-500 group-hover:text-white transition-all">
                            <i class="ph-fill ph-wallet text-3xl"></i>
                        </div>
                        <h4 class="text-xl font-bold mb-2">Pencairan Instan</h4>
                        <p class="text-sm text-winter-700/60 leading-relaxed">Tukarkan poin hasil kerja kerasmu ke saldo E-Wallet favorit tanpa ribet.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="tentang" class="py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row gap-20 items-center">
            <div class="lg:w-1/2" data-aos="fade-right">
                <h2 class="text-4xl lg:text-5xl font-black mb-8 leading-tight">Menjaga Kualitas <br> Ekosistem Digital.</h2>
                <p class="text-lg text-winter-700/70 mb-8 leading-relaxed">TesYuk! hadir untuk menjawab tantangan dunia pengembangan aplikasi di mana feedback nyata seringkali sulit didapat secara cepat dan akurat.</p>
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-3xl font-black text-winter-900">50+</h4>
                        <p class="text-xs font-bold text-winter-500 uppercase mt-1">Startup Terbantu</p>
                    </div>
                    <div>
                        <h4 class="text-3xl font-black text-winter-900">10k+</h4>
                        <p class="text-xs font-bold text-winter-500 uppercase mt-1">Misi Berhasil</p>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 relative" data-aos="fade-left">
                <div class="bg-winter-900 rounded-[3rem] p-12 text-white relative overflow-hidden group">
                    <div class="absolute inset-0 bg-winter-500/10 group-hover:scale-110 transition-transform duration-700"></div>
                    <i class="ph-bold ph-quotes text-8xl opacity-10 absolute top-5 right-10"></i>
                    <p class="text-2xl font-light italic leading-relaxed relative z-10">"Sangat membantu para developer dalam mencari feedback dari pengguna nyata, Thanks TesYuk!."</p>
                    <div class="mt-12 flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 bg-winter-500 rounded-full flex items-center justify-center font-bold text-lg">A</div>
                        <div>
                            <p class="font-bold">Adalah Pokoknya Developer</p>
                            <p class="text-xs text-winter-300">PT Gacorrr</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="py-24 bg-winter-50 border-t border-winter-300/20">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-black text-winter-900 mb-4">Proses Sederhana, Hasil Nyata.</h2>
                <p class="text-winter-700/80 max-w-xl mx-auto text-lg font-medium">Tiga langkah sistematis untuk memastikan kualitas perangkat lunak.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <div class="hidden md:block absolute top-[30%] left-[20%] right-[20%] h-[2px] bg-gradient-to-r from-transparent via-winter-300 to-transparent z-0"></div>

                <div class="relative z-10 text-center px-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 bg-white border border-winter-300/50 shadow-lg rounded-2xl flex items-center justify-center text-3xl text-winter-700 mx-auto mb-6 rotate-3 hover:rotate-0 transition-transform duration-300">
                        <i class="ph-fill ph-user-plus"></i>
                    </div>
                    <h3 class="font-bold text-xl text-winter-900 mb-3">Registrasi Profil</h3>
                    <p class="text-winter-700 text-sm leading-relaxed">Bergabung ke dalam sistem sebagai Developer yang menyediakan sistem, atau Tester yang mengevaluasinya.</p>
                </div>

                <div class="relative z-10 text-center px-4 mt-8 md:mt-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 bg-white border border-winter-300/50 shadow-lg rounded-2xl flex items-center justify-center text-3xl text-winter-500 mx-auto mb-6 -rotate-3 hover:rotate-0 transition-transform duration-300">
                        <i class="ph-fill ph-rocket-launch"></i>
                    </div>
                    <h3 class="font-bold text-xl text-winter-900 mb-3">Pengerjaan Misi</h3>
                    <p class="text-winter-700 text-sm leading-relaxed">Penguji menjalankan skenario sesuai instruksi, mencari celah keamanan, dan mendokumentasikan hasil temuan.</p>
                </div>

                <div class="relative z-10 text-center px-4 mt-8 md:mt-0" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-20 h-20 bg-white border border-winter-300/50 shadow-lg rounded-2xl flex items-center justify-center text-3xl text-winter-900 mx-auto mb-6 rotate-3 hover:rotate-0 transition-transform duration-300">
                        <i class="ph-fill ph-medal"></i>
                    </div>
                    <h3 class="font-bold text-xl text-winter-900 mb-3">Validasi & Imbalan</h3>
                    <p class="text-winter-700 text-sm leading-relaxed">Developer memperoleh ulasan berharga, sementara Tester menerima poin yang dapat dikonversi menjadi saldo finansial.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-24 bg-white border-t border-winter-300/20 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-3xl lg:text-5xl font-black text-winter-900 mb-6">Satu Ekosistem, Dua Manfaat.</h2>
                <p class="text-winter-700/80 max-w-2xl mx-auto text-lg font-medium">Solusi yang dirancang khusus dengan fitur unggulan untuk memenuhi kebutuhan spesifik kedua belah pihak.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-12">

                <div class="bg-winter-900 rounded-[2.5rem] p-10 lg:p-12 relative overflow-hidden group shadow-2xl hover:-translate-y-2 hover:shadow-winter-900/40 transition-all duration-500 border border-winter-700/50" data-aos="fade-right">
                    <div class="absolute right-0 top-0 w-96 h-96 bg-winter-700/30 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 group-hover:bg-winter-500/30 transition-colors duration-700"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-winter-500/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="w-16 h-16 bg-gradient-to-br from-winter-500 to-winter-700 text-white rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-lg shadow-winter-500/30 group-hover:scale-110 transition-transform duration-500">
                            <i class="ph-fill ph-code-block"></i>
                        </div>

                        <h3 class="text-3xl font-black text-white mb-8">Untuk Developer</h3>

                        <ul class="space-y-4 mb-12 flex-1">
                            <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-winter-700/30 transition-colors">
                                <div class="mt-0.5 bg-winter-700/80 p-1.5 rounded-full shrink-0"><i class="ph-bold ph-check text-winter-300 text-sm"></i></div>
                                <span class="text-winter-50/90 text-sm leading-relaxed">Identifikasi masalah teknis secara dini sebelum perangkat lunak rilis ke publik.</span>
                            </li>
                            <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-winter-700/30 transition-colors">
                                <div class="mt-0.5 bg-winter-700/80 p-1.5 rounded-full shrink-0"><i class="ph-bold ph-check text-winter-300 text-sm"></i></div>
                                <span class="text-winter-50/90 text-sm leading-relaxed">Terima ulasan objektif disertai bukti tangkapan layar dari berbagai jenis gawai.</span>
                            </li>
                            <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-winter-700/30 transition-colors">
                                <div class="mt-0.5 bg-winter-700/80 p-1.5 rounded-full shrink-0"><i class="ph-bold ph-check text-winter-300 text-sm"></i></div>
                                <span class="text-winter-50/90 text-sm leading-relaxed">Tentukan kriteria target perangkat dan demografi tester yang sangat spesifik.</span>
                            </li>
                        </ul>

                        <a href="/paket" class="mt-auto inline-flex items-center justify-between w-full bg-winter-700/50 hover:bg-winter-500 text-white font-bold px-8 py-4 rounded-xl transition-all duration-300 border border-winter-500/30 group/btn">
                            <span>Lihat Skema Biaya</span>
                            <i class="ph-bold ph-arrow-right group-hover/btn:translate-x-2 transition-transform text-xl"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-gradient-to-b from-white to-winter-50 rounded-[2.5rem] p-10 lg:p-12 relative overflow-hidden group shadow-xl hover:shadow-2xl hover:-translate-y-2 hover:shadow-winter-300/40 transition-all duration-500 border border-winter-300/40" data-aos="fade-left">
                    <div class="absolute right-0 top-0 w-96 h-96 bg-winter-300/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 group-hover:bg-winter-300/40 transition-colors duration-700"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-white rounded-full blur-3xl translate-y-1/2 -translate-x-1/3"></div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="w-16 h-16 bg-white text-winter-700 rounded-2xl flex items-center justify-center text-3xl mb-8 shadow-lg shadow-winter-300/20 border border-winter-300/30 group-hover:scale-110 transition-transform duration-500">
                            <i class="ph-fill ph-device-mobile"></i>
                        </div>

                        <h3 class="text-3xl font-black text-winter-900 mb-8">Untuk Tester</h3>

                        <ul class="space-y-4 mb-12 flex-1">
                            <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-white shadow-sm hover:shadow-md transition-all border border-transparent hover:border-winter-300/30">
                                <div class="mt-0.5 bg-winter-100 p-1.5 rounded-full shrink-0"><i class="ph-bold ph-check text-winter-700 text-sm"></i></div>
                                <span class="text-winter-900/80 text-sm leading-relaxed font-medium">Dapatkan akses eksklusif ke sistem yang belum tersedia di pasaran.</span>
                            </li>
                            <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-white shadow-sm hover:shadow-md transition-all border border-transparent hover:border-winter-300/30">
                                <div class="mt-0.5 bg-winter-100 p-1.5 rounded-full shrink-0"><i class="ph-bold ph-check text-winter-700 text-sm"></i></div>
                                <span class="text-winter-900/80 text-sm leading-relaxed font-medium">Kerjakan tugas pengujian dan laporkan temuan untuk kompensasi.</span>
                            </li>
                            <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-white shadow-sm hover:shadow-md transition-all border border-transparent hover:border-winter-300/30">
                                <div class="mt-0.5 bg-winter-100 p-1.5 rounded-full shrink-0"><i class="ph-bold ph-check text-winter-700 text-sm"></i></div>
                                <span class="text-winter-900/80 text-sm leading-relaxed font-medium">Konversikan apresiasi menjadi saldo dompet digital yang langsung cair.</span>
                            </li>
                        </ul>

                        <a href="/register?role=tester" class="mt-auto inline-flex items-center justify-between w-full bg-white hover:bg-winter-50 text-winter-900 font-bold px-8 py-4 rounded-xl transition-all duration-300 border-2 border-winter-300/50 hover:border-winter-500 shadow-sm group/btn">
                            <span>Mulai Hasilkan Pendapatan</span>
                            <i class="ph-bold ph-arrow-right group-hover/btn:translate-x-2 transition-transform text-winter-700 text-xl"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <footer class="bg-winter-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <h3 class="text-3xl font-black mb-10">TesYuk!</h3>
            <div class="flex justify-center gap-6 mb-16">
                <!-- <a href="#" class="text-winter-300 hover:text-white transition text-2xl"><i class="ph-bold ph-mail-send"></i></a> -->
                <a href="#" class="text-winter-300 hover:text-white transition text-2xl"><i class="ph-bold ph-whatsapp-logo"></i></a>
                <!-- <a href="#" class="text-winter-300 hover:text-white transition text-2xl"><i class="ph-bold ph-youtube-logo"></i></a> -->
            </div>
            <p class="text-sm text-winter-500">© 2026 TesYuk! • Solusi Validasi Aplikasi Terpercaya</p>
        </div>
    </footer>

    <script>
        // AOS Initialization
        AOS.init({
            duration: 1000,
            once: true,
            mirror: false,
        });

        // ANIMASI TRANSISI HALAMAN
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => {
                document.getElementById('page-content').classList.remove('opacity-0');
            });
        });

        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                // Abaikan jika link itu anchor/scroll ke bawah (seperti #fitur, #tentang)
                if (this.hostname === window.location.hostname && this.getAttribute('href') !== '#' && !this.getAttribute('href').startsWith('#') && this.target !== '_blank') {
                    e.preventDefault();
                    let destination = this.href;
                    document.getElementById('page-content').classList.add('opacity-0');
                    setTimeout(() => {
                        window.location.href = destination;
                    }, 500);
                }
            });
        });
    </script>
</body>

</html>