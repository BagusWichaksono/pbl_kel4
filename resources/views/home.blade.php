@php($winterColors = \App\Support\AppPalette::tailwindColors())
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
                        winter: @json($winterColors)
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
        :root {
            {!! \App\Support\AppPalette::cssVariables() !!}
        }

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
            background-image: radial-gradient(circle, rgba(var(--tesyuk-crimson-rgb), 0.055) 1px, transparent 1px);
        }

        /* --- Gradasi Teks Kustom --- */

        /* Gradasi untuk "Tes" (Ink ke primary) */
        .text-gradient-tes {
            background: linear-gradient(to right, var(--tesyuk-obsidian), var(--tesyuk-maroon));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        /* Gradasi untuk "Yuk!" (Primary ke accent) */
        .text-gradient-yuk {
            background: linear-gradient(to right, var(--tesyuk-crimson), var(--tesyuk-coral));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        nav a {
            letter-spacing: 0.15em;
        }

        @keyframes tesyuk-soft-enter {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @supports (view-transition-name: root) {
            ::view-transition-old(root),
            ::view-transition-new(root) {
                animation-duration: 0.26s;
                animation-timing-function: ease;
            }
        }

        nav,
        section,
        footer,
        .section-inner,
        .group,
        a,
        button {
            transition:
                background-color 0.24s ease,
                border-color 0.24s ease,
                box-shadow 0.24s ease,
                color 0.18s ease,
                opacity 0.24s ease,
                transform 0.24s ease;
        }

        section {
            animation: tesyuk-soft-enter 0.3s ease both;
        }

        .landing-nav-inner {
            width: 100%;
            max-width: 92rem;
            min-height: 4rem;
            margin: 0 auto;
            padding: 0 1.5rem;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .landing-brand,
        .landing-actions {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
        }

        .landing-brand {
            left: 1.5rem;
        }

        .landing-actions {
            right: 1.5rem;
        }

        .section-soft {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }

        .section-soft::before {
            content: "";
            position: absolute;
            top: -5.5rem;
            left: 50%;
            width: min(76rem, 92vw);
            height: 12rem;
            transform: translateX(-50%);
            background:
                radial-gradient(circle at 18% 50%, rgba(var(--tesyuk-coral-rgb), 0.09), transparent 34%),
                radial-gradient(circle at 82% 45%, rgba(var(--tesyuk-berry-rgb), 0.08), transparent 36%);
            filter: blur(28px);
            pointer-events: none;
            z-index: 0;
        }

        .section-soft::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: -6rem;
            width: min(72rem, 90vw);
            height: 10rem;
            transform: translateX(-50%);
            background: radial-gradient(circle, rgba(var(--tesyuk-accent-rgb), 0.08), transparent 68%);
            filter: blur(34px);
            pointer-events: none;
            z-index: 0;
        }

        .section-inner {
            position: relative;
            z-index: 1;
        }

        .section-hero {
            background: #fbfbfb;
        }

        .section-navy {
            background: var(--tesyuk-navy);
            color: #ffffff;
        }

        .section-cream {
            background: #f7f7f7;
        }

        .section-plain {
            background: #ffffff;
        }

        .section-berry {
            background: #fff7f8;
        }

        .accent-panel {
            border: 1px solid rgba(var(--tesyuk-berry-rgb), 0.16);
            background: rgba(255, 255, 255, 0.72);
            box-shadow: 0 18px 48px -38px rgba(var(--tesyuk-obsidian-rgb), 0.42);
        }

        .dark-panel {
            background: var(--tesyuk-obsidian);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 24px 60px -38px rgba(0, 0, 0, 0.85);
        }

        .role-logo-card {
            background: rgba(255, 255, 255, 0.84);
            border: 1px solid rgba(var(--tesyuk-berry-rgb), 0.14);
            box-shadow: 0 16px 40px -32px rgba(var(--tesyuk-obsidian-rgb), 0.48);
        }

        .section-navy .role-logo-card {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.12);
            box-shadow: none;
        }

        .section-navy .muted-text {
            color: rgba(255, 255, 255, 0.68);
        }

        .section-navy .soft-label {
            color: rgba(255, 255, 255, 0.58);
        }

        .palette-chip {
            width: 2.25rem;
            height: 0.42rem;
            border-radius: 999px;
            display: block;
        }

        .mini-metric {
            background: rgba(255, 255, 255, 0.86);
            border: 1px solid rgba(var(--tesyuk-berry-rgb), 0.13);
        }

        .footer-link {
            color: rgba(238, 238, 238, 0.68);
            transition: color 0.25s ease, transform 0.25s ease;
        }

        .footer-link:hover {
            color: #ffffff;
            transform: translateX(2px);
        }

        @media (min-width: 1024px) {
            .landing-nav-inner {
                padding-left: 2rem;
                padding-right: 2rem;
            }

            .landing-brand {
                left: 2rem;
            }

            .landing-actions {
                right: 2rem;
            }
        }

        @media (max-width: 767px) {
            .landing-nav-inner {
                justify-content: flex-start;
            }

            .landing-brand {
                position: static;
                transform: none;
            }
        }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }
    </style>
</head>

<body id="page-content" class="bg-[#fbfbfb] text-winter-900 font-sans antialiased overflow-x-hidden bg-grid-pattern opacity-0 transition-opacity duration-500 ease-in-out" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 50)">

    <nav
        class="fixed w-full top-0 z-[1000] transition-all duration-500"
        :class="scrolled ? 'bg-white/70 backdrop-blur-lg border-b border-winter-300/20 py-3 shadow-lg' : 'bg-transparent py-6'">
        <div class="landing-nav-inner">
            <a href="/" class="landing-brand flex items-center gap-3 group">
                <img src="{{ asset(\App\Support\AppPalette::LOGO_ASSET) }}" class="w-10 h-10 transition-transform group-hover:rotate-6" alt="Logo TesYuk">

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

            <div class="landing-actions hidden md:flex items-center p-1.5 rounded-full border border-winter-300/30 bg-winter-100/30 backdrop-blur-md">
                <a href="/login"
                    class="px-10 py-3 text-sm font-bold text-winter-700 rounded-full transition-all duration-300 hover:bg-winter-500 hover:text-white hover:shadow-md active:scale-95">
                    Masuk
                </a>

                <a href="/register"
                    class="ml-1 bg-winter-900 text-white text-sm font-bold px-7 py-3 rounded-full hover:bg-winter-700 transition-all shadow-xl shadow-winter-900/20 active:scale-95">
                    Buat Akun
                </a>
            </div>
        </div>
    </nav>

    <section class="section-soft section-hero relative pt-48 pb-20 overflow-hidden">
        <div class="absolute top-0 right-0 -translate-y-1/4 translate-x-1/4 w-[560px] h-[560px] bg-[rgba(232,69,69,0.10)] rounded-full blur-[140px] z-0"></div>

        <div class="section-inner max-w-7xl mx-auto px-6 lg:px-8 text-center" data-aos="fade-up">
            <h1 class="text-5xl lg:text-[72px] font-black leading-[1.1] tracking-tight mb-8">
                Validasi Aplikasi <br />
                dengan <span class="text-winter-500">Pengguna Langsung.</span>
            </h1>
            <p class="text-lg text-winter-700/70 max-w-2xl mx-auto mb-16 font-medium">
                Ekosistem pengujian aplikasi paling transparan. Membantu para Developer menyempurnakan sistem dan dapatkan apresiasi finansial di setiap misi.
            </p>

            <div class="mx-auto mb-16 grid max-w-4xl grid-cols-1 gap-4 text-left sm:grid-cols-3">
                <div class="role-logo-card rounded-3xl p-4 flex items-center gap-4" data-aos="fade-up" data-aos-delay="80">
                    <img src="{{ asset('assets/logo-developer.png') }}" class="h-14 w-14 object-contain" alt="Logo Developer">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-[var(--tesyuk-berry)]">Developer</p>
                        <p class="mt-1 text-sm font-bold text-winter-900">Upload aplikasi, pantau validasi.</p>
                    </div>
                </div>
                <div class="role-logo-card rounded-3xl p-4 flex items-center gap-4" data-aos="fade-up" data-aos-delay="140">
                    <img src="{{ asset('assets/logo-tester.png') }}" class="h-14 w-14 object-contain" alt="Logo Tester">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-[var(--tesyuk-coral)]">Tester</p>
                        <p class="mt-1 text-sm font-bold text-winter-900">Kerjakan misi, kumpulkan poin.</p>
                    </div>
                </div>
                <div class="role-logo-card rounded-3xl p-4 flex items-center gap-4" data-aos="fade-up" data-aos-delay="200">
                    <img src="{{ asset('assets/logo-bantuan.png') }}" class="h-14 w-14 object-contain" alt="Logo Bantuan">
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-[var(--tesyuk-crimson)]">Bantuan</p>
                        <p class="mt-1 text-sm font-bold text-winter-900">Admin siap bantu saat proses.</p>
                    </div>
                </div>
            </div>

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

                <div class="lg:col-span-4 flex flex-col items-center justify-center gap-5 relative animate-float">
                    <div class="absolute inset-0 bg-[rgba(144,55,73,0.12)] blur-[100px] rounded-full scale-75 -z-10"></div>
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

                    <div class="hidden w-full max-w-[300px] grid-cols-2 gap-3 lg:grid">
                        <div class="rounded-2xl bg-white/90 p-3 text-left shadow-xl ring-1 ring-[rgba(144,55,73,0.12)]">
                            <div class="flex items-center gap-2">
                                <span class="palette-chip bg-[var(--tesyuk-obsidian)]"></span>
                                <span class="palette-chip bg-[var(--tesyuk-crimson)]"></span>
                                <span class="palette-chip bg-[var(--tesyuk-coral)]"></span>
                            </div>
                            <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-winter-900/60">Live Progress</p>
                        </div>

                        <div class="rounded-2xl bg-[var(--tesyuk-navy)] px-4 py-3 text-left text-white shadow-xl">
                            <p class="text-[10px] font-black uppercase tracking-widest text-white/50">Report</p>
                            <p class="text-sm font-black leading-tight">3 laporan hari ini</p>
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
                        <p class="text-sm text-winter-700/60 leading-relaxed">Tukarkan poin hasil testingmu ke saldo E-Wallet favorit tanpa ribet.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="tentang" class="section-soft section-navy py-32">
        <div class="section-inner max-w-7xl mx-auto px-6 flex flex-col lg:flex-row gap-20 items-center">
            <div class="lg:w-1/2" data-aos="fade-right">
                <div class="mb-6 flex items-center gap-2">
                    <span class="palette-chip bg-[var(--tesyuk-coral)]"></span>
                    <span class="palette-chip bg-[var(--tesyuk-berry)]"></span>
                    <span class="palette-chip bg-[var(--tesyuk-plum)]"></span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-black mb-8 leading-tight text-white">Menjaga Kualitas <br> Ekosistem Digital.</h2>
                <p class="muted-text text-lg mb-8 leading-relaxed">TesYuk! hadir untuk menjawab tantangan dunia pengembangan aplikasi di mana feedback pengguna langsung seringkali sulit didapat secara cepat dan akurat.</p>
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-3xl font-black text-white">50+</h4>
                        <p class="soft-label text-xs font-bold uppercase mt-1">Startup Terbantu</p>
                    </div>
                    <div>
                        <h4 class="text-3xl font-black text-white">10k+</h4>
                        <p class="soft-label text-xs font-bold uppercase mt-1">Misi Berhasil</p>
                    </div>
                </div>

                <div class="mt-10 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="role-logo-card rounded-3xl p-5">
                        <img src="{{ asset('assets/logo-admin.png') }}" class="mb-4 h-16 w-16 object-contain" alt="Logo Admin">
                        <p class="text-sm font-bold text-white">Admin memvalidasi transaksi, aplikasi, dan laporan agar alur tetap rapi.</p>
                    </div>
                    <div class="role-logo-card rounded-3xl p-5">
                        <img src="{{ asset(\App\Support\AppPalette::LOGO_ASSET) }}" class="mb-4 h-16 w-16 object-contain" alt="Logo TesYuk">
                        <p class="text-sm font-bold text-white">TesYuk menjadi ruang kerja bersama untuk pengujian yang terukur.</p>
                    </div>
                </div>
            </div>
            <div class="lg:w-1/2 relative" data-aos="fade-left">
                <div class="dark-panel rounded-[3rem] p-12 text-white relative overflow-hidden group">
                    <div class="absolute inset-0 bg-[rgba(232,69,69,0.08)] group-hover:scale-110 transition-transform duration-700"></div>
                    <i class="ph-bold ph-quotes text-8xl opacity-10 absolute top-5 right-10"></i>
                    <p class="text-2xl font-light italic leading-relaxed relative z-10">"Sangat membantu para developer dalam mencari feedback dari pengguna langsung, Terimakasih TesYuk!."</p>
                    <div class="mt-12 flex items-center gap-4 relative z-10">
                        <div class="w-12 h-12 bg-[var(--tesyuk-coral)] rounded-full flex items-center justify-center font-bold text-lg">A</div>
                        <div>
                            <p class="font-bold">Adalah Pokoknya Developer</p>
                            <p class="text-xs text-white/55">PT Suhat Malang</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="cara-kerja" class="section-soft section-cream py-24 border-t border-[rgba(144,55,73,0.12)]">
        <div class="section-inner max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-black text-winter-900 mb-4">Proses Sederhana, Hasil Nyata.</h2>
                <p class="text-winter-700/80 max-w-xl mx-auto text-lg font-medium">Tiga langkah sistematis untuk memastikan kualitas perangkat lunak.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <div class="hidden md:block absolute top-[30%] left-[20%] right-[20%] h-[2px] bg-[rgba(43,46,74,0.16)] z-0"></div>

                <div class="relative z-10 text-center px-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 bg-[var(--tesyuk-obsidian)] border border-winter-300/50 shadow-lg rounded-2xl flex items-center justify-center text-3xl text-white mx-auto mb-6 rotate-3 hover:rotate-0 transition-transform duration-300">
                        <i class="ph-fill ph-user-plus"></i>
                    </div>
                    <h3 class="font-bold text-xl text-winter-900 mb-3">Registrasi Profil</h3>
                    <p class="text-winter-700 text-sm leading-relaxed">Bergabung ke dalam sistem sebagai Developer yang menyediakan sistem, atau Tester yang mengevaluasinya.</p>
                </div>

                <div class="relative z-10 text-center px-4 mt-8 md:mt-0" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 bg-[var(--tesyuk-coral)] border border-winter-300/50 shadow-lg rounded-2xl flex items-center justify-center text-3xl text-white mx-auto mb-6 -rotate-3 hover:rotate-0 transition-transform duration-300">
                        <i class="ph-fill ph-rocket-launch"></i>
                    </div>
                    <h3 class="font-bold text-xl text-winter-900 mb-3">Pengerjaan Misi</h3>
                    <p class="text-winter-700 text-sm leading-relaxed">Penguji menjalankan skenario sesuai instruksi, mencari celah keamanan, dan mendokumentasikan hasil temuan.</p>
                </div>

                <div class="relative z-10 text-center px-4 mt-8 md:mt-0" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-20 h-20 bg-[var(--tesyuk-plum)] border border-winter-300/50 shadow-lg rounded-2xl flex items-center justify-center text-3xl text-white mx-auto mb-6 rotate-3 hover:rotate-0 transition-transform duration-300">
                        <i class="ph-fill ph-medal"></i>
                    </div>
                    <h3 class="font-bold text-xl text-winter-900 mb-3">Validasi & Imbalan</h3>
                    <p class="text-winter-700 text-sm leading-relaxed">Developer memperoleh ulasan berharga, sementara Tester menerima poin yang dapat dikonversi menjadi saldo finansial.</p>
                </div>
            </div>

            <div class="mt-16 grid grid-cols-1 gap-5 md:grid-cols-3">
                <div class="mini-metric rounded-3xl p-6">
                    <p class="text-xs font-black uppercase tracking-widest text-[var(--tesyuk-berry)]">01</p>
                    <p class="mt-2 text-lg font-black text-winter-900">Data misi tersimpan rapi</p>
                </div>
                <div class="mini-metric rounded-3xl p-6">
                    <p class="text-xs font-black uppercase tracking-widest text-[var(--tesyuk-coral)]">02</p>
                    <p class="mt-2 text-lg font-black text-winter-900">Feedback tester lebih mudah dipantau</p>
                </div>
                <div class="mini-metric rounded-3xl p-6">
                    <p class="text-xs font-black uppercase tracking-widest text-[var(--tesyuk-navy)]">03</p>
                    <p class="mt-2 text-lg font-black text-winter-900">Reward dan validasi lebih transparan</p>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="section-soft section-berry py-24 border-t border-[rgba(144,55,73,0.12)] overflow-hidden">
        <div class="section-inner max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-3xl lg:text-5xl font-black text-winter-900 mb-6">Satu Ekosistem, Dua Manfaat.</h2>
                <p class="text-winter-700/80 max-w-2xl mx-auto text-lg font-medium">Solusi yang dirancang khusus dengan fitur unggulan untuk memenuhi kebutuhan spesifik kedua belah pihak.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-12">

                <div class="bg-[var(--tesyuk-obsidian)] rounded-[2.5rem] p-10 lg:p-12 relative overflow-hidden group shadow-2xl hover:-translate-y-2 hover:shadow-winter-900/40 transition-all duration-500 border border-white/10" data-aos="fade-right">
                    <div class="absolute right-0 top-0 h-32 w-32 bg-[var(--tesyuk-maroon)] opacity-70"></div>
                    <div class="absolute bottom-0 left-0 h-24 w-24 bg-[var(--tesyuk-crimson)] opacity-70"></div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="mb-8 flex items-center justify-between gap-4">
                            <div class="w-16 h-16 bg-white/10 text-white rounded-2xl flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-code-block"></i>
                            </div>
                            <img src="{{ asset('assets/logo-developer.png') }}" class="h-20 w-20 object-contain" alt="Logo Developer">
                        </div>

                        <h3 class="text-3xl font-black text-white mb-8">Untuk Developer</h3>

                        <ul class="space-y-4 mb-12 flex-1">
                            <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-winter-700/30 transition-colors">
                                <div class="mt-0.5 bg-white/10 p-1.5 rounded-full shrink-0"><i class="ph-bold ph-check text-[var(--tesyuk-coral)] text-sm"></i></div>
                                <span class="text-winter-50/90 text-sm leading-relaxed">Identifikasi masalah teknis lebih awal sebelum perangkat lunak rilis ke publik.</span>
                            </li>
                            <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-winter-700/30 transition-colors">
                                <div class="mt-0.5 bg-white/10 p-1.5 rounded-full shrink-0"><i class="ph-bold ph-check text-[var(--tesyuk-coral)] text-sm"></i></div>
                                <span class="text-winter-50/90 text-sm leading-relaxed">Terima ulasan objektif disertai bukti screenshot dari berbagai pengguna.</span>
                            </li>
                            <li class="flex items-start gap-4 p-4 rounded-2xl hover:bg-winter-700/30 transition-colors">
                                <div class="mt-0.5 bg-white/10 p-1.5 rounded-full shrink-0"><i class="ph-bold ph-check text-[var(--tesyuk-coral)] text-sm"></i></div>
                                <span class="text-winter-50/90 text-sm leading-relaxed">Tentukan kriteria target perangkat dan demografi tester yang sangat spesifik.</span>
                            </li>
                        </ul>

                        <a href="/paket" class="mt-auto inline-flex items-center justify-between w-full bg-white/10 hover:bg-[var(--tesyuk-crimson)] text-white font-bold px-8 py-4 rounded-xl transition-all duration-300 border border-white/10 group/btn">
                            <span>Lihat Skema Biaya</span>
                            <i class="ph-bold ph-arrow-right group-hover/btn:translate-x-2 transition-transform text-xl"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-10 lg:p-12 relative overflow-hidden group shadow-xl hover:shadow-2xl hover:-translate-y-2 hover:shadow-winter-300/40 transition-all duration-500 border border-[rgba(144,55,73,0.16)]" data-aos="fade-left">
                    <div class="absolute right-0 top-0 h-28 w-28 bg-[var(--tesyuk-coral)] opacity-15"></div>
                    <div class="absolute bottom-0 left-0 h-24 w-24 bg-[var(--tesyuk-plum)] opacity-15"></div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="mb-8 flex items-center justify-between gap-4">
                            <div class="w-16 h-16 bg-[var(--tesyuk-secondary)] text-[var(--tesyuk-berry)] rounded-2xl flex items-center justify-center text-3xl shadow-lg shadow-winter-300/20 border border-winter-300/30 group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-device-mobile"></i>
                            </div>
                            <img src="{{ asset('assets/logo-tester.png') }}" class="h-20 w-20 object-contain" alt="Logo Tester">
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

    <section class="section-soft section-plain py-20 border-t border-[rgba(144,55,73,0.12)]">
        <div class="section-inner max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-10 lg:grid-cols-12 lg:items-center">
                <div class="lg:col-span-5">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[var(--tesyuk-berry)]">Dashboard Terhubung</p>
                    <h2 class="mt-4 text-3xl font-black leading-tight text-winter-900 lg:text-5xl">
                        Semua proses validasi berada dalam satu alur.
                    </h2>
                    <p class="mt-5 text-base font-medium leading-8 text-winter-700/70">
                        Dari pendaftaran aplikasi, pengambilan misi, laporan harian, approval laporan akhir, sampai pencairan poin, semuanya dibuat ringkas agar tim tidak perlu lompat antar alat.
                    </p>
                </div>

                <div class="lg:col-span-7">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="accent-panel rounded-3xl p-6">
                            <div class="mb-5 flex items-center justify-between">
                                <img src="{{ asset(\App\Support\AppPalette::LOGO_ASSET) }}" class="h-14 w-14 object-contain" alt="Logo TesYuk">
                                <span class="rounded-full bg-[var(--tesyuk-obsidian)] px-4 py-2 text-xs font-black text-white">Live</span>
                            </div>
                            <h3 class="text-xl font-black text-winter-900">Pantau misi aktif</h3>
                            <p class="mt-3 text-sm font-medium leading-7 text-winter-700/70">Progress tester dan laporan terbaru bisa dibaca dari dashboard role masing-masing.</p>
                        </div>

                        <div class="accent-panel rounded-3xl p-6">
                            <div class="mb-5 flex items-center justify-between">
                                <img src="{{ asset('assets/logo-bantuan.png') }}" class="h-14 w-14 object-contain" alt="Logo Bantuan">
                                <span class="rounded-full bg-[var(--tesyuk-coral)] px-4 py-2 text-xs font-black text-white">Support</span>
                            </div>
                            <h3 class="text-xl font-black text-winter-900">Bantuan cepat</h3>
                            <p class="mt-3 text-sm font-medium leading-7 text-winter-700/70">Tester dan developer bisa menghubungi admin dari popup bantuan tanpa keluar halaman.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="relative overflow-hidden bg-[var(--tesyuk-obsidian)] text-white pt-20 pb-10">
        <div class="absolute right-0 top-0 h-36 w-36 bg-[var(--tesyuk-maroon)] opacity-80"></div>
        <div class="absolute bottom-0 left-0 h-28 w-28 bg-[var(--tesyuk-crimson)] opacity-70"></div>

        <div class="relative max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 gap-10 text-left md:grid-cols-2 lg:grid-cols-4">
                <div class="lg:col-span-2">
                    <div class="mb-6 flex items-center gap-3">
                        <img src="{{ asset(\App\Support\AppPalette::LOGO_ASSET) }}" class="h-11 w-11 object-contain" alt="Logo TesYuk">
                        <h3 class="text-3xl font-black">TesYuk!</h3>
                    </div>
                    <p class="max-w-md text-sm leading-7 text-winter-50/70">
                        Platform validasi aplikasi yang mempertemukan Developer dengan Tester aktif untuk mendapatkan feedback nyata, laporan harian, dan bukti pengujian yang lebih transparan.
                    </p>
                    <div class="mt-7 flex gap-4">
                        <a href="#" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-xl text-winter-300 transition hover:bg-white/10 hover:text-white">
                            <i class="ph-bold ph-whatsapp-logo"></i>
                        </a>
                        <a href="mailto:admin@tesyuk.my.id" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/5 text-xl text-winter-300 transition hover:bg-white/10 hover:text-white">
                            <i class="ph-bold ph-envelope-simple"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="mb-5 text-sm font-black uppercase tracking-widest text-white">Navigasi</h4>
                    <div class="flex flex-col gap-3 text-sm">
                        <a href="#tentang" class="footer-link">Tentang TesYuk</a>
                        <a href="#fitur" class="footer-link">Fitur Utama</a>
                        <a href="#cara-kerja" class="footer-link">Cara Kerja</a>
                        <a href="/paket" class="footer-link">Skema Biaya</a>
                    </div>
                </div>

                <div>
                    <h4 class="mb-5 text-sm font-black uppercase tracking-widest text-white">Ringkasnya</h4>
                    <div class="space-y-4 text-sm leading-7 text-winter-50/70">
                        <p>Developer mengunggah aplikasi dan memantau laporan tester dari satu dashboard.</p>
                        <p>Tester menjalankan misi, mengirim bukti pengujian, lalu mengumpulkan poin reward.</p>
                    </div>
                </div>
            </div>

            <div class="mt-16 flex flex-col gap-4 border-t border-white/10 pt-8 text-sm text-winter-300/70 md:flex-row md:items-center md:justify-between">
                <p>© 2026 TesYuk! • Solusi Validasi Aplikasi Terpercaya</p>
                <p>Dibuat untuk pengujian aplikasi yang lebih rapi, terukur, dan manusiawi.</p>
            </div>
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
