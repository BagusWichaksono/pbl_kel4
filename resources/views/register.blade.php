<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - TesYuk!</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Poppins', 'sans-serif'] } } } }
    </script>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-10 rounded-[32px] border border-slate-200 max-w-md w-full shadow-2xl shadow-slate-200/50 my-8">
        <div class="text-center mb-8">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 bg-slate-900 rounded-[30px] mx-auto mb-10 flex items-center justify-center shadow-xl rotate-3 hover:rotate-0 transition-transform">
            <h1 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h1>
            <p class="text-slate-500 text-sm mt-2">Mulai perjalananmu di TesYuk!</p>
        </div>

        <form action="/register" method="POST" class="space-y-4">
            @csrf 
            @if ($errors->any())
                <div class="bg-red-50 text-red-500 p-3 rounded-xl text-xs font-bold mb-4">
                    Terdapat kesalahan, pastikan email belum terdaftar.
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Nama Lengkap</label>
                <input type="text" name="name" placeholder="Masukkan nama" required value="{{ old('name') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition placeholder:text-slate-300">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Email</label>
                <input type="email" name="email" placeholder="nama@gmail.com" required value="{{ old('email') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition placeholder:text-slate-300">
            </div>

            <div class="pt-2">
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-3">Daftar Sebagai</label>
                <div class="grid grid-cols-2 gap-4">
                    
                    <label class="relative block cursor-pointer group h-full">
                        <input type="radio" name="role" value="developer" class="peer sr-only" required>
                        <div class="flex flex-col items-center justify-center text-center gap-3 p-4 h-full rounded-2xl border-2 border-slate-200 bg-white transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-600 hover:border-blue-300 relative">
                            
                            <i class="ph-fill ph-check-circle text-blue-600 text-xl absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity"></i>

                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 transition-colors group-hover:bg-blue-100 group-hover:text-blue-600 peer-checked:bg-blue-600 peer-checked:text-white mt-2">
                                <i class="ph-fill ph-monitor text-2xl"></i>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Developer</h4>
                                <p class="text-[10px] font-medium text-slate-500 mt-0.5">Pembuat Aplikasi</p>
                            </div>
                        </div>
                    </label>

                    <label class="relative block cursor-pointer group h-full">
                        <input type="radio" name="role" value="tester" class="peer sr-only" required>
                        <div class="flex flex-col items-center justify-center text-center gap-3 p-4 h-full rounded-2xl border-2 border-slate-200 bg-white transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:ring-1 peer-checked:ring-blue-600 hover:border-blue-300 relative">
                            
                            <i class="ph-fill ph-check-circle text-blue-600 text-xl absolute top-3 right-3 opacity-0 peer-checked:opacity-100 transition-opacity"></i>

                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 transition-colors group-hover:bg-blue-100 group-hover:text-blue-600 peer-checked:bg-blue-600 peer-checked:text-white mt-2">
                                <div class="flex items-end justify-center -mb-1">
                                    <div class="relative">
                                        <i class="ph-fill ph-monitor text-[26px]"></i>
                                        <i class="ph-bold ph-check text-slate-100 group-hover:text-blue-100 peer-checked:text-blue-600 transition-colors text-[10px] absolute top-[35%] left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
                                    </div>
                                    <div class="relative -ml-2 mb-0.5">
                                        <i class="ph-fill ph-device-mobile text-[18px]"></i>
                                        <i class="ph-bold ph-check text-slate-100 group-hover:text-blue-100 peer-checked:text-blue-600 transition-colors text-[8px] absolute top-[45%] left-1/2 -translate-x-1/2 -translate-y-1/2"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-bold text-slate-900">Tester</h4>
                                <p class="text-[10px] font-medium text-slate-500 mt-0.5">Penguji Aplikasi</p>
                            </div>
                        </div>
                    </label>
                    
                </div>
            </div>

            <div class="pt-2">
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Password</label>
                <input type="password" name="password" placeholder="•••••" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition placeholder:text-slate-300">
            </div>
            
            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-slate-800 transition shadow-lg mt-6">DAFTAR SEKARANG</button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-8">
            Sudah punya akun? <a href="/login" class="text-slate-900 font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>
</body>

</html>