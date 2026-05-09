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
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        winter: {
                            50: '#eff5fa',
                            300: '#8bafd0',
                            500: '#5374ac',
                            700: '#2f456f',
                            900: '#141c33',
                        }
                    },
                    keyframes: {
                        'fade-in-up': {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'float': {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    },
                    animation: {
                        'fade-in-up': 'fade-in-up 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                        'float': 'float 3s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
</head>

<body id="page-content" class="bg-winter-50 min-h-screen flex items-center justify-center p-6 overflow-hidden opacity-0 transition-opacity duration-500 ease-in-out">
    <div class="max-w-4xl w-full">
        <a href="/" class="inline-flex items-center gap-2 text-winter-500 hover:text-winter-900 font-bold mb-6 transition opacity-0 animate-[fade-in-up_0.8s_ease-out_forwards]">
            <i class="ph-bold ph-x text-3xl"></i>
        </a>

        <div class="text-center mb-12 opacity-0 animate-[fade-in-up_0.8s_ease-out_0.1s_forwards]">
            <h1 class="text-3xl md:text-4xl font-extrabold text-winter-900 tracking-tight">Satu Harga, Semua Keuntungan</h1>
            <p class="text-winter-700 mt-3 text-lg">Validasi aplikasimu sekarang tanpa pusing memikirkan batasan fitur.</p>
        </div>

        <div class="bg-white rounded-[2rem] border-2 border-winter-900 shadow-2xl overflow-hidden flex flex-col md:flex-row relative transform transition-all hover:shadow-winter-900/10 hover:-translate-y-1 opacity-0 animate-[fade-in-up_0.8s_ease-out_0.2s_forwards]">
            
            <div class="absolute top-0 right-0 bg-winter-900 text-white text-[10px] font-black tracking-widest px-5 py-2 rounded-bl-2xl uppercase shadow-md z-10">
                All-in-One Access
            </div>

            <div class="p-8 md:p-12 md:w-1/2 bg-white flex flex-col justify-center border-b md:border-b-0 md:border-r border-winter-300/30 relative overflow-hidden">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-winter-300/10 rounded-full blur-3xl"></div>

                <div class="w-16 h-16 bg-winter-900 rounded-2xl flex items-center justify-center mb-6 text-white shadow-lg shadow-winter-900/20 animate-float relative z-10">
                    <i class="ph-fill ph-rocket-launch text-3xl"></i>
                </div>
                <h2 class="text-3xl font-black text-winter-900 relative z-10">Developer Pass</h2>
                <p class="text-winter-700 text-sm mt-2 mb-8 leading-relaxed relative z-10">Nikmati seluruh fasilitas pengujian tanpa biaya tambahan. Sekali bayar untuk kualitas aplikasi yang lebih baik.</p>
                <div class="mt-auto relative z-10">
                    <p class="text-4xl font-black text-winter-900 tracking-tight">Rp 300.000</p>
                    <p class="text-sm font-semibold text-winter-500 mt-1 uppercase tracking-wider">/ Aplikasi (Sekali Bayar)</p>
                </div>
            </div>

            <div class="p-8 md:p-12 md:w-1/2 flex flex-col justify-center bg-winter-50/30">
                <h3 class="text-xs font-bold text-winter-500 uppercase tracking-widest mb-6">Yang Akan Kamu Dapatkan:</h3>
                
                <ul class="text-winter-900 space-y-5 mb-10 flex-1 font-medium">
                    <li class="flex items-start gap-4 opacity-0 animate-[fade-in-up_0.8s_ease-out_0.4s_forwards]">
                        <div class="bg-winter-300/20 p-1 rounded-full mt-0.5 border border-winter-300/50">
                            <i class="ph-bold ph-check text-winter-700 text-sm"></i>
                        </div>
                        <span>Akses ke seluruh <strong>Tester aktif</strong> TesYuk!</span>
                    </li>
                    <li class="flex items-start gap-4 opacity-0 animate-[fade-in-up_0.8s_ease-out_0.5s_forwards]">
                        <div class="bg-winter-300/20 p-1 rounded-full mt-0.5 border border-winter-300/50">
                            <i class="ph-bold ph-check text-winter-700 text-sm"></i>
                        </div>
                        <span>Laporan <strong>UI/UX Analytics & Feedback</strong> detail</span>
                    </li>
                    <li class="flex items-start gap-4 opacity-0 animate-[fade-in-up_0.8s_ease-out_0.6s_forwards]">
                        <div class="bg-winter-300/20 p-1 rounded-full mt-0.5 border border-winter-300/50">
                            <i class="ph-bold ph-check text-winter-700 text-sm"></i>
                        </div>
                        <span><strong>Prioritas tayang</strong> di halaman utama</span>
                    </li>
                    <li class="flex items-start gap-4 opacity-0 animate-[fade-in-up_0.8s_ease-out_0.7s_forwards]">
                        <div class="bg-winter-300/20 p-1 rounded-full mt-0.5 border border-winter-300/50">
                            <i class="ph-bold ph-check text-winter-700 text-sm"></i>
                        </div>
                        <span>Proses verifikasi kilat <strong>(Max 1x24 Jam)</strong></span>
                    </li>
                </ul>

                <a href="/register" class="opacity-0 animate-[fade-in-up_0.8s_ease-out_0.8s_forwards] group relative block w-full py-4 bg-winter-900 text-white text-center font-bold text-lg rounded-xl overflow-hidden transition-all hover:bg-winter-700 hover:shadow-xl hover:shadow-winter-900/20">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Mulai Upload Aplikasi
                        <i class="ph-bold ph-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </span>
                </a>
            </div>

        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => {
                document.getElementById('page-content').classList.remove('opacity-0');
            });
        });

        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
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