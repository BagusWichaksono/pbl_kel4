<?php

use Illuminate\Support\Facades\Route;

// 1. Halaman Landing Page Utama
Route::get('/', function () {
    return view('preview');
});

// 2. Halaman Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
});

// 3. Halaman Pilih Paket
Route::get('/paket', function () {
    return view('paket');
});

// 4. Halaman Login
Route::get('/login', function () {
    return view('login');
});

// 5. Halaman Register
Route::get('/register', function () {
    return view('register');
});
