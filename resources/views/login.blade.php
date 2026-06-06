@php($winterColors = \App\Support\AppPalette::tailwindColors())
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - TesYuk!</title>

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
            background-image: radial-gradient(circle, rgba(var(--tesyuk-accent-rgb), 0.12) 1px, transparent 1px);
        }

        .text-gradient-tes {
            background: none;
            color: var(--tesyuk-ink);
            -webkit-text-fill-color: var(--tesyuk-ink);
        }

        .text-gradient-yuk {
            background: none;
            color: var(--tesyuk-ink);
            -webkit-text-fill-color: var(--tesyuk-ink);
        }

        input:not([type="radio"]) {
            color: var(--tesyuk-ink) !important;
            border-color: rgba(var(--tesyuk-ink-rgb), 0.15) !important;
            -webkit-text-fill-color: var(--tesyuk-ink);
        }

        input:not([type="radio"])::placeholder {
            color: rgba(var(--tesyuk-ink-rgb), 0.35) !important;
            -webkit-text-fill-color: rgba(var(--tesyuk-ink-rgb), 0.35);
        }

        input:focus {
            transform: scale(1.01);
            border-color: var(--tesyuk-ink) !important;
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

<body id="page-content" class="bg-winter-50 min-h-screen flex items-center justify-center p-4 sm:p-6 py-12 relative overflow-y-auto custom-scrollbar bg-grid-pattern font-sans antialiased opacity-0 transition-opacity duration-500 ease-in-out">

    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden opacity-50">
        <div class="absolute top-[5%] right-[-5%] w-[500px] h-[500px] bg-winter-300 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[5%] left-[-5%] w-[450px] h-[450px] bg-winter-500 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-[1000px] grid grid-cols-1 lg:grid-cols-2 bg-white/95 backdrop-blur-3xl rounded-[2.5rem] lg:rounded-[3rem] border border-white relative z-10 overflow-hidden transition-all duration-500 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.1)]">

        <a href="/"
            class="absolute top-6 right-6 lg:top-8 lg:right-8 w-10 h-10 lg:w-12 lg:h-12 bg-white hover:bg-winter-50 rounded-full flex items-center justify-center border border-winter-200 transition-all group z-30 shadow-sm hover:shadow active:scale-95">
            <i class="ph-bold ph-x text-winter-900 group-hover:rotate-90 transition-transform"></i>
        </a>

        <div class="hidden lg:flex relative min-h-[680px] bg-winter-900 text-white p-14 flex-col justify-between overflow-hidden">
            <div class="absolute -top-32 -right-32 w-80 h-80 bg-winter-500/40 rounded-full blur-[80px]"></div>
            <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-white/10 rounded-full blur-[100px]"></div>

            <div class="relative z-20">
                <h2 class="text-4xl font-black tracking-tight text-white drop-shadow-sm">TesYuk!</h2>
                <p class="text-sm text-white/60 font-semibold mt-1 tracking-wide">Platform Pengujian Aplikasi</p>
            </div>

            <div class="relative z-10 flex-1 flex flex-col justify-end items-center mt-12 pb-2">
                
                <div class="relative z-20 self-start ml-6 -mb-6">
                    <div class="relative bg-white text-winter-900 rounded-[1.5rem] px-6 py-4 shadow-2xl w-fit transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                        <p class="text-xs font-bold text-winter-500 mb-0.5 uppercase tracking-wider">
                            Halo!
                        </p>
                        <h3 class="text-[1.5rem] font-black leading-tight">
                            Selamat <br> datang!
                        </h3>
                        <div class="absolute -bottom-2 left-6 w-5 h-5 bg-white rotate-45 rounded-[4px]"></div>
                    </div>
                </div>

                <div class="relative flex justify-center">
                    <div class="absolute inset-0 bg-winter-500/20 rounded-full blur-[80px] scale-125"></div>
                    <img
                        src="{{ asset('assets/logo-login.png') }}"
                        class="relative z-10 w-[420px] h-[420px] object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.3)] hover:-translate-y-2 transition-transform duration-500"
                        alt="Maskot TesYuk">
                </div>
            </div>
        </div>

        <div class="relative p-10 sm:p-14 lg:p-16 flex flex-col justify-center bg-white">
            <div class="w-full max-w-[380px] mx-auto">

                <div class="text-center mb-10 mt-4 lg:mt-0">
                    <h1 class="font-black text-[2.75rem] tracking-tighter mb-1 flex justify-center items-baseline">
                        <span class="text-gradient-tes">LOG</span>
                        <span class="text-gradient-yuk">IN</span>
                    </h1>
                    <p class="text-winter-500 text-sm font-semibold tracking-wide">Akses dashboard pengujian Anda</p>
                </div>

                @if (session('success'))
                    <div class="bg-emerald-50/80 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-xl text-sm font-bold mb-6 text-center shadow-sm flex items-center justify-center gap-2">
                        <i class="ph-fill ph-check-circle text-lg"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('warning'))
                    <div class="bg-amber-50/80 border border-amber-200 text-amber-700 px-5 py-3.5 rounded-xl text-sm font-bold mb-6 text-center shadow-sm flex items-center justify-center gap-2">
                        <i class="ph-fill ph-warning-circle text-lg"></i>
                        {{ session('warning') }}
                    </div>
                @endif

                <form action="/login" method="POST" class="space-y-6">
                    @csrf

                    <div class="group">
                        <label class="block text-[11px] font-black text-winter-900/60 uppercase tracking-widest ml-1 mb-2.5 group-focus-within:text-winter-900 transition-colors">
                            Email
                        </label>
                        <input name="email" type="email" placeholder="nama@gmail.com" value="{{ old('email') }}" required
                            class="w-full px-5 py-4 bg-winter-50/50 border border-winter-200 rounded-2xl focus:ring-[3px] focus:ring-winter-900/10 focus:border-winter-900 focus:bg-white outline-none transition-all text-winter-900 font-semibold shadow-sm text-sm">

                        @error('email')
                            <p class="text-red-500 text-xs mt-2 ml-1 font-bold flex items-center gap-1">
                                <i class="ph-bold ph-warning"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="group relative">
                        <label class="block text-[11px] font-black text-winter-900/60 uppercase tracking-widest ml-1 mb-2.5 group-focus-within:text-winter-900 transition-colors">
                            Kata Sandi
                        </label>

                        <div class="relative">
                            <input id="login-password" name="password" type="password" placeholder="••••••••" required
                                class="w-full pl-5 pr-12 py-4 bg-winter-50/50 border border-winter-200 rounded-2xl focus:ring-[3px] focus:ring-winter-900/10 focus:border-winter-900 focus:bg-white outline-none transition-all text-winter-900 font-semibold shadow-sm text-sm tracking-widest placeholder:tracking-normal">

                            <button type="button" onclick="togglePassword('login-password', 'eye-icon-login')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-winter-400 hover:text-winter-900 transition-colors focus:outline-none p-1">
                                <i id="eye-icon-login" class="ph-bold ph-eye-closed text-[1.35rem]"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-winter-900 text-white py-4 rounded-2xl font-bold tracking-wide hover:bg-winter-800 active:scale-[0.98] transition-all shadow-[0_8px_20px_rgba(0,0,0,0.15)] hover:shadow-[0_12px_25px_rgba(0,0,0,0.2)] mt-8">
                        MASUK
                    </button>
                </form>

                <p class="text-center text-sm text-winter-500 font-medium mt-10">
                    Belum punya akun?
                    <a href="/register" class="text-winter-900 font-bold hover:text-winter-700 transition-colors underline decoration-2 underline-offset-[5px]">
                        Daftar Sekarang
                    </a>
                </p>
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

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ph-eye-closed');
                icon.classList.add('ph-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-closed');
            }
        }

        const loginForm = document.querySelector('form');
        if (loginForm) {
            loginForm.addEventListener('submit', () => {
                document.getElementById('page-content').classList.add('opacity-0');
                document.getElementById('page-content').style.transition = "opacity 0.8s ease-out";
            });
        }
    </script>
</body>

</html>