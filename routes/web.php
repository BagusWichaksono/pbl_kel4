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

// RUTE PROSES REGISTER (SIMPAN KE DATABASE)
Route::post('/register', function (Illuminate\Http\Request $request) {
    // 1. Validasi data yang masuk
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email', // Pastikan email belum dipakai
        'password' => 'required|min:5',
        'role' => 'required'
    ]);

    // 2. Simpan ke database dengan password yang sudah di-Hash
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role']
    ]);

    // Jika user adalah tester, otomatis buat tester_profile
    if ($user->role === 'tester') {
        $user->testerProfile()->create([
            'e_wallet_provider' => null,
            'e_wallet_number' => null
        ]);
    }

    // 3. Setelah berhasil daftar, otomatis ke halaman login dan muncul notif
    return redirect('/login')->with('success', 'Data Anda telah disimpan! Silahkan login.');
});
