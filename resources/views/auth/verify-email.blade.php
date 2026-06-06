@php($winterColors = \App\Support\AppPalette::tailwindColors())
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - TesYuk!</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        winter: @json($winterColors)
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            {!! \App\Support\AppPalette::cssVariables() !!}
        }

        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: radial-gradient(circle, rgba(var(--tesyuk-accent-rgb), 0.15) 1px, transparent 1px);
        }

        .text-gradient-tes {
            background: linear-gradient(to right, var(--tesyuk-ink), var(--tesyuk-primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .text-gradient-yuk {
            background: linear-gradient(to right, var(--tesyuk-primary), var(--tesyuk-accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: var(--tesyuk-accent);
            border-radius: 10px;
        }
    </style>
</head>

<body id="page-content" class="bg-winter-50 min-h-screen flex items-center justify-center p-6 py-12 relative overflow-y-auto custom-scrollbar bg-grid-pattern font-sans antialiased opacity-0 transition-opacity duration-500 ease-in-out">

    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden opacity-40">
        <div class="absolute top-[10%] right-[-5%] w-[500px] h-[500px] bg-winter-300 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[5%] left-[-5%] w-[400px] h-[400px] bg-winter-500 rounded-full blur-[120px]"></div>
    </div>

    <div class="bg-white/90 backdrop-blur-2xl p-10 rounded-[2.5rem] border border-white max-w-md w-full relative z-10 transition-all duration-500" style="box-shadow: 0 50px 100px -20px rgba(var(--tesyuk-ink-rgb), 0.15);">

        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 rounded-3xl bg-winter-900 flex items-center justify-center shadow-xl shadow-winter-900/20">
                <i class="ph-bold ph-envelope-simple text-white text-4xl"></i>
            </div>
        </div>

        <div class="text-center mb-8">
            <h1 class="font-black text-4xl tracking-tighter mb-2 flex justify-center items-baseline">
                <span class="text-gradient-tes">VERIFIKASI</span>
            </h1>

            <h2 class="font-black text-4xl tracking-tighter flex justify-center items-baseline">
                <span class="text-gradient-yuk">EMAIL</span>
            </h2>

            <p class="text-winter-700/80 text-sm font-medium mt-5 leading-relaxed">
                Kami telah mengirimkan link verifikasi ke alamat email Anda.
                Silakan cek inbox atau folder spam untuk mengaktifkan akun TesYuk.
            </p>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-5 py-4 rounded-2xl text-sm font-bold mb-6 text-center shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('message'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-5 py-4 rounded-2xl text-sm font-bold mb-6 text-center shadow-sm">
                {{ session('message') }}
            </div>
        @endif

        @if (session('warning'))
            <div class="bg-amber-50 border border-amber-200 text-amber-700 px-5 py-4 rounded-2xl text-sm font-bold mb-6 text-center shadow-sm">
                {{ session('warning') }}
            </div>
        @endif

        <div class="bg-winter-50 border border-winter-300/30 rounded-3xl p-5 mb-6">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-2xl bg-white flex items-center justify-center shadow-sm shrink-0">
                    <i class="ph-bold ph-info text-winter-700 text-xl"></i>
                </div>

                <div>
                    <h3 class="text-winter-900 font-black text-sm mb-1">
                        Belum menerima email?
                    </h3>
                    <p class="text-winter-700/80 text-xs font-medium leading-relaxed">
                        Tunggu beberapa saat, lalu klik tombol kirim ulang. Pastikan juga alamat email yang digunakan sudah benar.
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
            @csrf
            <button type="submit" class="w-full bg-winter-900 text-white py-4 rounded-2xl font-bold hover:bg-winter-700 hover:-translate-y-1 active:scale-95 transition-all shadow-xl shadow-winter-900/20">
                KIRIM ULANG EMAIL VERIFIKASI
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-white text-winter-700 py-4 rounded-2xl font-bold border border-winter-300/40 hover:bg-winter-50 hover:-translate-y-1 active:scale-95 transition-all">
                LOGOUT
            </button>
        </form>

        <p class="text-center text-xs text-winter-700/70 font-medium mt-8 leading-relaxed">
            Setelah email berhasil diverifikasi, Anda dapat mengakses dasbor sesuai peran akun Anda.
        </p>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => {
                document.getElementById('page-content').classList.remove('opacity-0');
            });
        });

        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', () => {
                document.getElementById('page-content').classList.add('opacity-0');
                document.getElementById('page-content').style.transition = "opacity 0.8s ease-out";
            });
        });
    </script>
</body>
</html>
