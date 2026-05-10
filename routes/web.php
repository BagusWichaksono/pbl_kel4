<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Halaman Landing Page Utama
Route::get('/', function () {
    return view('home');
});

// Halaman Dashboard Developer
Route::get('/dashboard-dev', function () {
    return view('dashboard-dev');
});

// Halaman Dashboard Tester
Route::get('/dashboard-tester', function () {
    return redirect('/tester');
});

// Halaman Pilih Paket
Route::get('/paket', function () {
    return view('paket');
});

// Halaman Login (Tampilan)
Route::get('/login', function () {
    return view('login');
})->name('login');

// Halaman Register (Tampilan)
Route::get('/register', function () {
    return view('register');
});

// ─── PROSES LOGIN ───
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Tentukan redirect berdasarkan role
        return match($user->role) {
            'super_admin' => redirect('/admin'),
            'admin'       => redirect('/admin'),
            'developer'   => redirect('/developer'),
            'tester'      => redirect('/tester'),
            default       => redirect('/'),
        };
    }

    return back()->withErrors([
        'email' => 'Email atau password salah!',
    ])->onlyInput('email');
});

// ─── PROSES REGISTER (SUPER KETAT) ───
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s]+$/'],
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed', 
        'role' => 'required|in:developer,tester'
    ], [
        // PESAN CUSTOM BAHASA INDONESIA
        'name.required' => 'Nama wajib diisi.',
        'name.regex' => 'Nama tidak boleh menggunakan simbol.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email salah.',
        'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
        'password.required' => 'Kata sandi wajib diisi.',
        'password.min' => 'Kata sandi minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        'role.required' => 'Pilih salah satu peran.',
    ]);
    
    // 1. Validasi data dengan keamanan tambahan (Anti Simbol & Min 8 Karakter)
    $validated = $request->validate([
        'name' => [
            'required', 
            'string', 
            'max:255', 
            'regex:/^[a-zA-Z0-9\s]+$/' // SATPAM ANTI SIMBOL
        ],
        'email' => 'required|email|unique:users,email',
        // Harus ada password_confirmation di form, minimal 8 karakter
        'password' => 'required|min:8|confirmed', 
        'role' => 'required|in:developer,tester'
    ], [
        // Pesan error custom pakai bahasa Indonesia
        'name.regex' => 'Nama tidak boleh menggunakan simbol (hanya huruf, angka, dan spasi).',
        'password.min' => 'Kata sandi terlalu pendek, minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
    ]);

    // 2. Simpan ke database
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role']
    ]);

    // 3. Jika user adalah tester, otomatis buat tester_profile
    if ($user->role === 'tester') {
        $user->testerProfile()->create([
            'e_wallet_provider' => null,
            'e_wallet_number' => null
        ]);
    }

    // 4. Kembali ke login dengan notif sukses
    return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan masuk dengan akun Anda.');
});

// Tambahan rute untuk Logout biar aman
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');