<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - TesYuk!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Poppins', 'sans-serif'] } } } }
    </script>
</head>

<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-10 rounded-[32px] border border-slate-200 max-w-md w-full shadow-2xl shadow-slate-200/50">
        <div class="text-center mb-8">
            <img src="{{ asset('assets/logo.png') }}" class="w-20 h-20 bg-slate-900 rounded-[30px] mx-auto mb-10 flex items-center justify-center shadow-xl rotate-3 hover:rotate-0 transition-transform">
            <h1 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h1>
            <p class="text-slate-500 text-sm mt-2">Mulai perjalananmu di TesYuk!</p>
        </div>

        <form action="/register" method="POST" class="space-y-4">
            @csrf @if ($errors->any())
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
                <input type="email" name="email" placeholder="nama@email.com" required value="{{ old('email') }}" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition placeholder:text-slate-300">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Daftar Sebagai</label>
                <div class="relative">
                    <select name="role" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition appearance-none cursor-pointer">
                        <option value="" disabled selected>Pilih Peran Anda</option>
                        <option value="developer">Developer (Pembuat Aplikasi)</option>
                        <option value="tester">Tester (Penguji Aplikasi)</option>
                        <option value="admin">Admin</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-500">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                    </div>
                </div>
            </div>

            <div>
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