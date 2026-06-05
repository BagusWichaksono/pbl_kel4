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
                    fontFamily: { sans: ['Poppins', 'sans-serif'] }
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
            border-color: rgba(var(--tesyuk-ink-rgb), 0.12) !important;
            -webkit-text-fill-color: var(--tesyuk-ink);
        }
        input:not([type="radio"])::placeholder {
            color: rgba(var(--tesyuk-ink-rgb), 0.38) !important;
            -webkit-text-fill-color: rgba(var(--tesyuk-ink-rgb), 0.38);
        }
        input:focus { transform: scale(1.01); }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--tesyuk-accent); border-radius: 10px; }
    </style>
</head>
<body id="page-content" class="bg-winter-50 min-h-screen flex items-center justify-center p-6 py-12 relative overflow-y-auto custom-scrollbar bg-grid-pattern font-sans antialiased opacity-0 transition-opacity duration-500 ease-in-out">

    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden opacity-40">
        <div class="absolute top-[10%] right-[-5%] w-[500px] h-[500px] bg-winter-300 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[5%] left-[-5%] w-[400px] h-[400px] bg-winter-500 rounded-full blur-[120px]"></div>
    </div>

    <div class="bg-white/90 backdrop-blur-2xl p-10 rounded-[2.5rem] border border-white max-w-md w-full relative z-10 transition-all duration-500" style="box-shadow: 0 50px 100px -20px rgba(var(--tesyuk-ink-rgb), 0.15);">
        
        <a href="/" class="absolute top-6 left-6 w-10 h-10 bg-winter-50 hover:bg-winter-100 rounded-xl flex items-center justify-center border border-winter-300/30 transition-all group group-hover:shadow-md">
            <i class="ph-bold ph-x text-winter-900 group-hover:rotate-90 transition-transform"></i>
        </a>

        <div class="text-center mb-10 mt-4">
            <h1 class="font-black text-5xl tracking-tighter mb-2 flex justify-center items-baseline">
                <span class="text-gradient-tes">LOG</span>
                <span class="text-gradient-yuk">IN</span>
            </h1>
            <p class="text-winter-700/80 text-sm font-medium mt-3">Akses dasbor pengujian Anda</p>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-5 py-4 rounded-2xl text-sm font-bold mb-6 text-center shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <form action="/login" method="POST" class="space-y-5">
            @csrf
            <div class="group">
                <label class="block text-[10px] font-black text-winter-900/70 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-winter-900 transition-colors">Email</label>
                <input name="email" type="email" placeholder="nama@gmail.com" value="{{ old('email') }}" required 
                    class="w-full px-6 py-4 bg-white/50 border border-winter-300/30 rounded-2xl focus:ring-4 focus:ring-winter-900/10 focus:border-winter-900 outline-none transition-all text-winter-900 font-medium shadow-sm">
                @error('email')
                    <p class="text-red-500 text-xs mt-1.5 ml-1 font-bold">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="group relative">
                <label class="block text-[10px] font-black text-winter-900/70 uppercase tracking-widest ml-1 mb-2 group-focus-within:text-winter-900 transition-colors">Kata Sandi</label>
                <div class="relative">
                    <input id="login-password" name="password" type="password" placeholder="••••••••" required 
                        class="w-full pl-6 pr-12 py-4 bg-white/50 border border-winter-300/30 rounded-2xl focus:ring-4 focus:ring-winter-900/10 focus:border-winter-900 outline-none transition-all text-winter-900 font-medium shadow-sm">
                    
                    <button type="button" onclick="togglePassword('login-password', 'eye-icon-login')" class="absolute right-4 top-1/2 -translate-y-1/2 text-winter-900/40 hover:text-winter-900 transition-colors focus:outline-none">
                        <i id="eye-icon-login" class="ph-bold ph-eye-closed text-xl"></i>
                    </button>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-winter-900 text-white py-4 rounded-2xl font-bold hover:bg-winter-800 hover:-translate-y-1 active:scale-95 transition-all shadow-xl shadow-winter-900/20 mt-8">
                MASUK
            </button>
        </form>

        <p class="text-center text-sm text-winter-700/80 font-medium mt-8">
            Belum punya akun? <a href="/register" class="text-winter-900 font-bold hover:text-winter-700 transition-colors underline decoration-2 underline-offset-4">Daftar Sekarang</a>
        </p>
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

        // Fungsi untuk toggle password
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ph-eye-closed');
                icon.classList.add('ph-eye'); // Ubah ke icon mata terbuka
            } else {
                input.type = 'password';
                icon.classList.remove('ph-eye');
                icon.classList.add('ph-eye-closed'); // Ubah ke icon mata tertutup
            }
        }

        const loginForm = document.querySelector('form');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => {
                // Jangan cegah default, biarkan form submit berjalan
                // Tapi kita picu animasi fade out dulu
                document.getElementById('page-content').classList.add('opacity-0');
                document.getElementById('page-content').style.transition = "opacity 0.8s ease-out";
            });
        }
    </script>
</body>
</html>
