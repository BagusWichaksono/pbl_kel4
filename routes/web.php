<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// Halaman Landing Page Utama
Route::get('/', function () {
    return view('home');
});

// Redirect dashboard lama ke panel Filament Developer
Route::get('/dashboard-dev', function () {
    return redirect('/developer');
});

// Redirect dashboard lama ke panel Filament Tester
Route::get('/dashboard-tester', function () {
    return redirect('/tester');
});

// Halaman Pilih Paket
Route::get('/paket', function () {
    return view('paket');
});

// Halaman Login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Halaman Register
Route::get('/register', function () {
    return view('register');
});

// Proses Login
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ], [
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email salah.',
        'password.required' => 'Kata sandi wajib diisi.',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return match ($user->role) {
            'super_admin', 'admin' => redirect('/admin'),
            'developer' => redirect('/developer'),
            'tester' => redirect('/tester'),
            default => redirect('/'),
        };
    }

    return back()
        ->withErrors([
            'email' => 'Email atau password salah!',
        ])
        ->onlyInput('email');
});

// Proses Register
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'regex:/^[a-zA-Z0-9\s]+$/',
        ],
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'role' => 'required|in:developer,tester',
    ], [
        'name.required' => 'Nama wajib diisi.',
        'name.regex' => 'Nama tidak boleh menggunakan simbol, hanya huruf, angka, dan spasi.',
        'email.required' => 'Email wajib diisi.',
        'email.email' => 'Format email salah.',
        'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
        'password.required' => 'Kata sandi wajib diisi.',
        'password.min' => 'Kata sandi minimal 8 karakter.',
        'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        'role.required' => 'Pilih salah satu peran.',
        'role.in' => 'Peran tidak valid.',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);

    // Jika user adalah tester, otomatis buat tester profile
    if ($user->role === 'tester') {
        $user->testerProfile()->create([
            'points' => 0,
        ]);
    }

    return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan masuk dengan akun Anda.');
});

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');