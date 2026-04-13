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
            <h1 class="text-2xl font-bold text-slate-900">Buat Akun Baru</h1>
            <p class="text-slate-500 text-sm mt-2">Mulai perjalananmu di TesYuk!</p>
        </div>

        <form action="/login-custom" method="GET" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Nama Lengkap</label>
                <input type="text" placeholder="Masukkan nama" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition placeholder:text-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Email</label>
                <input type="email" placeholder="nama@email.com" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition placeholder:text-slate-300">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase ml-1 mb-2">Password</label>
                <input type="password" placeholder="••••••••" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-slate-900 outline-none transition placeholder:text-slate-300">
            </div>
            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-slate-800 transition shadow-lg mt-6">DAFTAR SEKARANG</button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-8">
            Sudah punya akun? <a href="/login-custom" class="text-slate-900 font-bold hover:underline">Masuk di sini</a>
        </p>
    </div>
</body>
</html>