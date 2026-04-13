<?php

use Illuminate\Support\Facades\Route;

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
    return view('dashboard-tester');
});

// Halaman Pilih Paket
Route::get('/paket', function () {
    return view('paket');
});

// Halaman Login
Route::get('/login', function () {
    return view('login');
});

// Halaman Register
Route::get('/register', function () {
    return view('register');
});
