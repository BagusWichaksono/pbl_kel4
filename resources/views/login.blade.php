<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - TesYuk!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 relative">

    <a href="/" class="absolute top-6 left-6 md:top-8 md:left-8 flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 rounded-full text-sm font-bold text-slate-600 shadow-sm hover:shadow-md hover:text-blue-600 hover:border-blue-200 transition-all group z-10">
        <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
        <span class="hidden md:block">Kembali ke Beranda</span>
        <span class="block md:hidden">Kembali</span>
    </a>

    <div class="bg-white p-10 rounded-[32px] border border-slate-200 max-w-md w-full shadow-2xl shadow-slate-200/50">
        <div class="text-center mb-10">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 bg-slate-900 rounded-[30px] mx-auto mb-10 flex items-center justify-center shadow-xl rotate-3 hover:rotate-0 transition-transform">
            <h1 class="text-2xl font-bold text-slate-900">Selamat Datang Di TesYuk!</h1>
            <p class="text-slate-500 text-sm mt-2">Silahkan masuk ke dasbor Anda</p>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-5 py-4 rounded-2xl text-sm font-bold mb-6 text-center shadow-sm">
                {{ session('success') }}
            </div>
        @endif
        <form action="/login" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Email</label>
                <input name="email" type="email" placeholder="Masukkan email" value="{{ old('email') }}" required 
                    class="w-full px-5 py-3.5 bg-slate-50 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition placeholder:text-slate-300">
                
                @error('email')
                    <p class="text-red-500 text-xs mt-1 ml-1 font-bold">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Password</label>
                <input name="password" type="password" placeholder="•••••" required 
                    class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition placeholder:text-slate-300">
            </div>       
            
            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-slate-800 transition shadow-lg mt-6">MASUK SEKARANG</button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-8">
            Belum punya akun? <a href="/register" class="text-slate-900 font-bold hover:underline">Daftar Akun</a>
        </p>
    </div>

</body>
</html>