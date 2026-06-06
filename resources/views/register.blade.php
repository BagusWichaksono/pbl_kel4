@php($winterColors = \App\Support\AppPalette::tailwindColors())
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - TesYuk!</title>
    
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

    <!-- Efek Latar Belakang -->
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden opacity-50">
        <div class="absolute top-[5%] left-[-5%] w-[500px] h-[500px] bg-winter-300 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[5%] right-[-5%] w-[450px] h-[450px] bg-winter-500 rounded-full blur-[120px]"></div>
    </div>

    <!-- Container Utama -->
    <div class="w-full max-w-[1000px] grid grid-cols-1 lg:grid-cols-2 bg-white/95 backdrop-blur-3xl rounded-[2.5rem] lg:rounded-[3rem] border border-white relative z-10 overflow-hidden transition-all duration-500 shadow-[0_40px_80px_-20px_rgba(0,0,0,0.1)]">

        <!-- Tombol Tutup (Di Kiri untuk Register) -->
        <a href="/"
            class="absolute top-6 left-6 lg:top-8 lg:left-8 w-10 h-10 lg:w-12 lg:h-12 bg-winter-50 hover:bg-winter-100 rounded-full flex items-center justify-center border border-winter-200 transition-all group z-30 shadow-sm hover:shadow active:scale-95">
            <i class="ph-bold ph-x text-winter-900 group-hover:rotate-90 transition-transform"></i>
        </a>

        <!-- SECTION KIRI (Form Register) -->
        <div class="relative p-8 sm:p-10 lg:p-12 flex flex-col justify-center bg-white">
            <div class="w-full max-w-[380px] mx-auto">

                <!-- Judul Form -->
                <div class="text-center mb-8 mt-6 lg:mt-0">
                    <h1 class="font-black text-[2.75rem] tracking-tighter mb-1 flex justify-center items-baseline">
                        <span class="text-gradient-tes">REGIS</span>
                        <span class="text-gradient-yuk">TER</span>
                    </h1>
                    <p class="text-winter-500 text-sm font-semibold tracking-wide">Mulai pengujianmu hari ini</p>
                </div>

                <!-- Form Register -->
                <form action="/register" method="POST" class="space-y-4" novalidate>
                    @csrf

                    <!-- Input Nama -->
                    <div class="group">
                        <label class="block text-[11px] font-black text-winter-900/60 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-winter-900 transition-colors">
                            Nama
                        </label>
                        <input type="text" name="name" placeholder="Masukkan nama" required value="{{ old('name') }}"
                            pattern="^[a-zA-Z0-9\s]+$" title="Nama tidak boleh menggunakan simbol (hanya huruf, angka, dan spasi)"
                            class="w-full px-5 py-3.5 bg-winter-50/50 border border-winter-200 rounded-2xl focus:ring-[3px] focus:ring-winter-900/10 focus:border-winter-900 focus:bg-white outline-none transition-all text-winter-900 font-semibold shadow-sm text-sm">
                        @error('name')
                            <p class="text-rose-500 text-xs mt-1.5 ml-1 font-bold flex items-center gap-1"><i class="ph-bold ph-warning"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Email -->
                    <div class="group">
                        <label class="block text-[11px] font-black text-winter-900/60 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-winter-900 transition-colors">
                            Email
                        </label>
                        <input type="email" name="email" placeholder="nama@gmail.com" required value="{{ old('email') }}"
                            class="w-full px-5 py-3.5 bg-winter-50/50 border border-winter-200 rounded-2xl focus:ring-[3px] focus:ring-winter-900/10 focus:border-winter-900 focus:bg-white outline-none transition-all text-winter-900 font-semibold shadow-sm text-sm">
                        @error('email')
                            <p class="text-rose-500 text-xs mt-1.5 ml-1 font-bold flex items-center gap-1"><i class="ph-bold ph-warning"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pilihan Role -->
                    <div class="pt-1">
                        <label class="block text-[11px] font-black text-winter-900/60 uppercase tracking-widest ml-1 mb-2.5 text-center">
                            Daftar Sebagai
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Role Developer -->
                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="role" value="developer" class="peer sr-only" required {{ old('role') == 'developer' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-winter-100 bg-winter-50/50 transition-all peer-checked:border-winter-900 peer-checked:bg-white peer-checked:shadow-md hover:border-winter-300">
                                    <div class="w-10 h-10 bg-white border border-winter-100 rounded-xl flex items-center justify-center text-winter-400 group-hover:scale-110 group-hover:text-winter-600 transition-all peer-checked:bg-winter-900 peer-checked:text-white mb-2 shadow-sm">
                                        <i class="ph-fill ph-code text-xl"></i>
                                    </div>
                                    <h4 class="text-xs font-bold text-winter-900">Developer</h4>
                                </div>
                            </label>

                            <!-- Role Tester -->
                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="role" value="tester" class="peer sr-only" required {{ old('role') == 'tester' ? 'checked' : '' }}>
                                <div class="flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-winter-100 bg-winter-50/50 transition-all peer-checked:border-winter-900 peer-checked:bg-white peer-checked:shadow-md hover:border-winter-300">
                                    <div class="w-10 h-10 bg-white border border-winter-100 rounded-xl flex items-center justify-center text-winter-400 group-hover:scale-110 group-hover:text-winter-600 transition-all peer-checked:bg-winter-900 peer-checked:text-white mb-2 shadow-sm">
                                        <i class="ph-fill ph-device-mobile text-xl"></i>
                                    </div>
                                    <h4 class="text-xs font-bold text-winter-900">Tester</h4>
                                </div>
                            </label>
                        </div>
                        @error('role')
                            <p class="text-rose-500 text-xs mt-2 font-bold text-center flex items-center justify-center gap-1"><i class="ph-bold ph-warning"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Passwords Grid -->
                    <div class="grid grid-cols-2 gap-4 pt-1">
                        <!-- Password -->
                        <div class="group relative">
                            <label class="block text-[11px] font-black text-winter-900/60 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-winter-900 transition-colors">
                                Kata Sandi
                            </label>
                            <div class="relative">
                                <input id="reg-password" type="password" name="password" placeholder="••••••••" required minlength="8" title="Password minimal 8 karakter"
                                    class="w-full pl-4 pr-10 py-3.5 bg-winter-50/50 border border-winter-200 rounded-2xl focus:ring-[3px] focus:ring-winter-900/10 focus:border-winter-900 focus:bg-white outline-none transition-all text-winter-900 font-semibold shadow-sm text-sm tracking-widest placeholder:tracking-normal">
                                <button type="button" onclick="togglePassword('reg-password', 'eye-icon-reg')" class="absolute right-3 top-1/2 -translate-y-1/2 text-winter-400 hover:text-winter-900 transition-colors focus:outline-none p-1">
                                    <i id="eye-icon-reg" class="ph-bold ph-eye-closed text-lg"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="group relative">
                            <label class="block text-[11px] font-black text-winter-900/60 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-winter-900 transition-colors">
                                Konfirmasi
                            </label>
                            <div class="relative">
                                <input id="reg-password-confirm" type="password" name="password_confirmation" placeholder="••••••••" required minlength="8"
                                    class="w-full pl-4 pr-10 py-3.5 bg-winter-50/50 border border-winter-200 rounded-2xl focus:ring-[3px] focus:ring-winter-900/10 focus:border-winter-900 focus:bg-white outline-none transition-all text-winter-900 font-semibold shadow-sm text-sm tracking-widest placeholder:tracking-normal">
                                <button type="button" onclick="togglePassword('reg-password-confirm', 'eye-icon-confirm')" class="absolute right-3 top-1/2 -translate-y-1/2 text-winter-400 hover:text-winter-900 transition-colors focus:outline-none p-1">
                                    <i id="eye-icon-confirm" class="ph-bold ph-eye-closed text-lg"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-rose-500 text-xs mt-1 ml-1 font-bold flex items-center gap-1"><i class="ph-bold ph-warning"></i> {{ $message }}</p>
                    @enderror

                    <!-- Tombol Submit -->
                    <button type="submit"
                        class="w-full bg-winter-900 text-white py-4 rounded-2xl font-bold tracking-wide hover:bg-winter-800 active:scale-[0.98] transition-all shadow-[0_8px_20px_rgba(0,0,0,0.15)] hover:shadow-[0_12px_25px_rgba(0,0,0,0.2)] mt-6">
                        DAFTAR SEKARANG
                    </button>
                </form>

                <!-- Link Login -->
                <p class="text-center text-sm text-winter-500 font-medium mt-8">
                    Sudah punya akun?
                    <a href="/login" class="text-winter-900 font-bold hover:text-winter-700 transition-colors underline decoration-2 underline-offset-[5px]">
                        Login di sini
                    </a>
                </p>
            </div>
        </div>

        <!-- SECTION KANAN (Branding - Form Register) -->
        <div class="hidden lg:flex relative min-h-[680px] bg-winter-900 text-white p-14 flex-col justify-between overflow-hidden">
            <!-- Glow Effect -->
            <div class="absolute -top-32 -left-32 w-80 h-80 bg-winter-500/40 rounded-full blur-[80px]"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-white/10 rounded-full blur-[100px]"></div>

            <!-- Header Text -->
            <div class="relative z-20 text-right">
                <h2 class="text-4xl font-black tracking-tight text-white drop-shadow-sm">TesYuk!</h2>
                <p class="text-sm text-white/60 font-semibold mt-1 tracking-wide">Platform Pengujian Aplikasi</p>
            </div>

            <!-- Area Maskot & Bubble Chat -->
            <div class="relative z-10 flex-1 flex flex-col justify-end items-center mt-12 pb-2">
                
                <!-- Bubble chat (Kiri Maskot) -->
                <div class="relative z-20 self-start ml-6 -mb-6">
                    <div class="relative bg-white text-winter-900 rounded-[1.5rem] px-6 py-4 shadow-2xl w-fit transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                        <p class="text-xs font-bold text-winter-500 mb-0.5 uppercase tracking-wider">
                            Tunggu apa lagi?
                        </p>
                        <h3 class="text-[1.5rem] font-black leading-tight">
                            Yuk <br> daftar!
                        </h3>
                        <!-- Segitiga Bubble (Arah Bawah Kiri) -->
                        <div class="absolute -bottom-2 left-6 w-5 h-5 bg-white rotate-45 rounded-[4px]"></div>
                    </div>
                </div>

                <!-- Maskot -->
                <div class="relative flex justify-center">
                    <div class="absolute inset-0 bg-winter-500/20 rounded-full blur-[80px] scale-125"></div>
                    <img
                        src="{{ asset('assets/logo-register.png') }}"
                        class="relative z-10 w-[420px] h-[420px] object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.3)] hover:-translate-y-2 transition-transform duration-500"
                        alt="Maskot TesYuk">
                </div>
            </div>
        </div>

    </div>

    <!-- Script Fungsional -->
    <script>
        // 1. LOGIKA SAAT HALAMAN DIMUAT (Restore Data & Fade In)
        window.addEventListener('DOMContentLoaded', () => {
            requestAnimationFrame(() => {
                document.getElementById('page-content').classList.remove('opacity-0');
            });

            restoreFormData();
        });

        // 2. LOGIKA AUTO-SAVE KE LOCALSTORAGE
        const inputsToSave = document.querySelectorAll('input[name="name"], input[name="email"]');
        const roleRadios = document.querySelectorAll('input[name="role"]');

        inputsToSave.forEach(input => {
            input.addEventListener('input', () => {
                localStorage.setItem('reg_' + input.name, input.value);
            });
        });

        roleRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                localStorage.setItem('reg_role', radio.value);
            });
        });

        // 3. FUNGSI UNTUK MENARIK DATA (RESTORE) SETELAH REFRESH
        function restoreFormData() {
            inputsToSave.forEach(input => {
                const savedValue = localStorage.getItem('reg_' + input.name);
                if (savedValue) input.value = savedValue;
            });

            const savedRole = localStorage.getItem('reg_role');
            if (savedRole) {
                const targetRadio = document.querySelector(`input[name="role"][value="${savedRole}"]`);
                if (targetRadio) targetRadio.checked = true;
            }
        }

        // 4. BERSIHKAN PENYIMPANAN SAAT FORM BERHASIL DI-SUBMIT
        const registrationForm = document.querySelector('form');
        if (registrationForm) {
            registrationForm.addEventListener('submit', () => {
                localStorage.removeItem('reg_name');
                localStorage.removeItem('reg_email');
                localStorage.removeItem('reg_role');
                
                document.getElementById('page-content').classList.add('opacity-0');
                document.getElementById('page-content').style.transition = "opacity 0.8s ease-out";
            });
        }

        // 5. NAVIGASI SMOOTH (Hijack Link)
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (this.hostname === window.location.hostname && href !== '#' && !href.startsWith('#') && this.target !== '_blank') {
                    e.preventDefault(); 
                    let destination = this.href;
                    document.getElementById('page-content').classList.add('opacity-0');
                    setTimeout(() => {
                        window.location.href = destination;
                    }, 500); 
                }
            });
        });

        // 6. FUNGSI TOGGLE PASSWORD
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ph-eye-closed', 'ph-eye');
            } else {
                input.type = 'password';
                icon.classList.replace('ph-eye', 'ph-eye-closed');
            }
        }
    </script>
</body>

</html>