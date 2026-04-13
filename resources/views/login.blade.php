<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - TesYuk!</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { theme: { extend: { fontFamily: { sans: ['Poppins', 'sans-serif'] } } } }
    </script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="bg-white p-10 rounded-[32px] border border-slate-200 max-w-md w-full shadow-2xl shadow-slate-200/50">
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-slate-900 rounded-[20px] mx-auto mb-6 flex items-center justify-center shadow-xl rotate-3">
                <span class="text-white font-black text-3xl italic">T!</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900">Selamat Datang Kembali</h1>
            <p class="text-slate-500 text-sm mt-2">Silakan masuk ke dasbor Anda</p>
        </div>

        <form action="/admin" method="GET" class="space-y-5">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Email</label>
                <input type="email" value="example@gmail.com" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Password</label>
                <input type="password" value="password" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition">
            </div>
            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-slate-800 transition shadow-lg mt-6">MASUK SEKARANG</button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-8">
            Belum punya akun? <a href="/pilih-paket" class="text-slate-900 font-bold hover:underline">Daftar Akun</a>
        </p>
    </div>
</body>
</html>