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
                        winter: {
                            900: '#141c33',
                            700: '#2f456f',
                            500: '#5374ac',
                            300: '#8bafd0',
                            50: '#eff5fa'
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: radial-gradient(circle, rgba(139, 175, 208, 0.15) 1px, transparent 1px);
        }

        .text-gradient-tes {
            background: linear-gradient(to right, #141c33, #2f456f);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .text-gradient-yuk {
            background: linear-gradient(to right, #5374ac, #8bafd0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        input:focus {
            transform: scale(1.01);
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #8bafd0;
            border-radius: 10px;
        }
    </style>
</head>

<body id="page-content" class="bg-winter-50 min-h-screen flex items-center justify-center p-6 py-12 md:py-20 relative bg-grid-pattern font-sans antialiased overflow-y-auto custom-scrollbar opacity-0 transition-opacity duration-500 ease-in-out">

    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden opacity-40">
        <div class="absolute top-[5%] left-[-10%] w-[600px] h-[600px] bg-winter-300 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[5%] right-[-10%] w-[500px] h-[500px] bg-winter-500 rounded-full blur-[120px]"></div>
    </div>

    <div class="bg-white/90 backdrop-blur-2xl p-8 lg:p-10 rounded-[2.5rem] border border-white max-w-md w-full shadow-[0_50px_100px_-20px_rgba(20,28,51,0.15)] relative z-10">

        <a href="/" class="absolute top-6 left-6 w-10 h-10 bg-winter-50 hover:bg-winter-100 rounded-xl flex items-center justify-center border border-winter-300/30 transition-all group group-hover:shadow-md">
            <i class="ph-bold ph-x text-winter-900 group-hover:rotate-90 transition-transform"></i>
        </a>

        <div class="text-center mb-10 mt-4">
            <h1 class="font-black text-5xl tracking-tighter mb-2 flex justify-center items-baseline">
                <span class="text-gradient-tes">REGIS</span>
                <span class="text-gradient-yuk">TER</span>
            </h1>
            <p class="text-winter-700/80 text-sm font-medium mt-3">Mulai pengujianmu hari ini</p>
        </div>

        <form action="/register" method="POST" class="space-y-4" novalidate>
            @csrf

            <div>
                <label class="block text-[10px] font-black text-winter-500 uppercase tracking-widest ml-1 mb-1.5">Nama</label>
                <input type="text" name="name" placeholder="Masukkan nama" required value="{{ old('name') }}"
                    pattern="^[a-zA-Z0-9\s]+$" title="Nama tidak boleh menggunakan simbol (hanya huruf, angka, dan spasi)"
                    class="w-full px-5 py-3 bg-white/50 border border-winter-300/30 rounded-2xl focus:ring-4 focus:ring-winter-500/10 focus:border-winter-500 outline-none transition-all placeholder:text-winter-300 text-winter-900 font-medium">
                @error('name')
                    <p class="text-rose-500 text-[10px] mt-1.5 ml-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-[10px] font-black text-winter-500 uppercase tracking-widest ml-1 mb-1.5">Email</label>
                <input type="email" name="email" placeholder="nama@gmail.com" required value="{{ old('email') }}"
                    class="w-full px-5 py-3 bg-white/50 border border-winter-300/30 rounded-2xl focus:ring-4 focus:ring-winter-500/10 focus:border-winter-500 outline-none transition-all placeholder:text-winter-300 text-winter-900 font-medium">
                @error('email')
                    <p class="text-rose-500 text-[10px] mt-1.5 ml-1 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2">
                <label class="block text-[10px] font-black text-winter-500 uppercase tracking-widest ml-1 mb-3 text-center">Daftar Sebagai</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative block cursor-pointer group">
                        <input type="radio" name="role" value="developer" class="peer sr-only" required {{ old('role') == 'developer' ? 'checked' : '' }}>
                        <div class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-winter-300/20 bg-white transition-all peer-checked:border-winter-500 peer-checked:bg-winter-50 peer-checked:ring-4 peer-checked:ring-winter-500/10 hover:border-winter-300 hover:shadow-md">
                            <div class="w-10 h-10 bg-winter-50 rounded-xl flex items-center justify-center text-winter-500 group-hover:scale-110 transition-transform peer-checked:bg-winter-500 peer-checked:text-white mb-2">
                                <i class="ph-fill ph-code text-xl"></i>
                            </div>
                            <h4 class="text-xs font-bold text-winter-900">Developer</h4>
                        </div>
                    </label>

                    <label class="relative block cursor-pointer group">
                        <input type="radio" name="role" value="tester" class="peer sr-only" required {{ old('role') == 'tester' ? 'checked' : '' }}>
                        <div class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 border-winter-300/20 bg-white transition-all peer-checked:border-winter-500 peer-checked:bg-winter-50 peer-checked:ring-4 peer-checked:ring-winter-500/10 hover:border-winter-300 hover:shadow-md text-center">
                            <div class="w-10 h-10 bg-winter-50 rounded-xl flex items-center justify-center text-winter-500 group-hover:scale-110 transition-transform peer-checked:bg-winter-500 peer-checked:text-white mb-2">
                                <i class="ph-fill ph-device-mobile text-xl"></i>
                            </div>
                            <h4 class="text-xs font-bold text-winter-900">Tester</h4>
                        </div>
                    </label>
                </div>
                @error('role')
                    <p class="text-rose-500 text-[10px] mt-1.5 font-bold text-center">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-4 pt-2">
                <div class="group relative">
                    <label class="block text-[10px] font-black text-winter-500 uppercase tracking-widest ml-1 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <input id="reg-password" type="password" name="password" placeholder="••••••••" required minlength="8" title="Password minimal 8 karakter"
                            class="w-full pl-5 pr-12 py-3 bg-white/50 border border-winter-300/30 rounded-2xl focus:ring-4 focus:ring-winter-500/10 focus:border-winter-500 outline-none transition-all">
                        <button type="button" onclick="togglePassword('reg-password', 'eye-icon-reg')" class="absolute right-4 top-1/2 -translate-y-1/2 text-winter-400 hover:text-winter-700 transition-colors focus:outline-none">
                            <i id="eye-icon-reg" class="ph-bold ph-eye-closed text-xl"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-500 text-[10px] mt-1.5 ml-1 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="group relative">
                    <label class="block text-[10px] font-black text-winter-500 uppercase tracking-widest ml-1 mb-1.5">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <input id="reg-password-confirm" type="password" name="password_confirmation" placeholder="••••••••" required minlength="8"
                            class="w-full pl-5 pr-12 py-3 bg-white/50 border border-winter-300/30 rounded-2xl focus:ring-4 focus:ring-winter-500/10 focus:border-winter-500 outline-none transition-all">
                        <button type="button" onclick="togglePassword('reg-password-confirm', 'eye-icon-confirm')" class="absolute right-4 top-1/2 -translate-y-1/2 text-winter-400 hover:text-winter-700 transition-colors focus:outline-none">
                            <i id="eye-icon-confirm" class="ph-bold ph-eye-closed text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-winter-900 text-white py-4 rounded-2xl font-bold hover:bg-winter-700 hover:-translate-y-1 active:scale-95 transition-all shadow-xl shadow-winter-900/20 mt-6 uppercase">
                Daftar
            </button>
        </form>

        <p class="text-center text-sm text-winter-700/80 font-medium mt-6">
            Sudah punya akun? <a href="/login" class="text-winter-500 font-bold hover:text-winter-700 transition-colors underline decoration-2 underline-offset-4">Login di sini</a>
        </p>
    </div>

    <script>
        // 1. LOGIKA SAAT HALAMAN DIMUAT (Restore Data & Fade In)
        window.addEventListener('DOMContentLoaded', () => {
            // Efek Fade In (Bawaan kamu)
            requestAnimationFrame(() => {
                const page = document.getElementById('page-content');
                if(page) page.classList.remove('opacity-0');
            });

            // Jalankan fungsi restore data
            restoreFormData();
        });

        // 2. LOGIKA AUTO-SAVE KE LOCALSTORAGE
        // Ambil semua input kecuali password (demi keamanan)
        const inputsToSave = document.querySelectorAll('input[name="name"], input[name="email"]');
        const roleRadios = document.querySelectorAll('input[name="role"]');

        // Simpan Nama & Email tiap kali ada perubahan
        inputsToSave.forEach(input => {
            input.addEventListener('input', () => {
                localStorage.setItem('reg_' + input.name, input.value);
            });
        });

        // Simpan Pilihan Role (Developer/Tester)
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
            });
        }

        // 5. NAVIGASI SMOOTH (Bawaan kamu - Hijack Link)
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

        // 6. FUNGSI TOGGLE PASSWORD (Bawaan kamu - Poles dikit pakai replace)
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ph-eye-closed', 'ph-eye'); // Lebih ringkas pakai replace
            } else {
                input.type = 'password';
                icon.classList.replace('ph-eye', 'ph-eye-closed');
            }
        }
    </script>
</body>

</html>