<?php

namespace App\Filament\Tester\Pages;

use Filament\Pages\Page;

class HubungiAdmin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationGroup = 'Akun & Bantuan';
    protected static ?string $navigationLabel = 'Hubungi Admin';
    protected static ?string $title = 'Pusat Bantuan & Chat';
    protected static string $view = 'tester.hubungi-admin';

    // 1. Tempat menyimpan ketikan user
    public string $pesanBaru = '';

    // 2. Tempat menyimpan riwayat chat di layar
    public array $riwayatChat = [];

    // 3. Fungsi yang dijalankan pertama kali halaman dibuka
    public function mount()
    {
        // Memberikan pesan sambutan otomatis dari admin
        $this->riwayatChat[] = [
            'pengirim' => 'admin',
            'teks' => 'Halo Bagus, selamat datang di layanan bantuan TesYuk! Ada yang bisa kami bantu terkait pengujian aplikasimu hari ini?'
        ];
    }

    // 4. Fungsi ketika tombol "Kirim" ditekan
    public function kirimPesan()
    {
        // Kalau input kosong, jangan lakukan apa-apa
        if (trim($this->pesanBaru) === '') {
            return;
        }

        // Masukkan pesan user (warna biru rata kanan) ke layar
        $this->riwayatChat[] = [
            'pengirim' => 'user',
            'teks' => $this->pesanBaru
        ];

        // Kosongkan kolom input setelah dikirim
        $this->pesanBaru = '';

        // Simulasi balasan otomatis dari admin (Biar kelihatan hidup)
        $this->riwayatChat[] = [
            'pengirim' => 'admin',
            'teks' => 'Terima kasih! Pesan kamu sudah kami terima. Mohon tunggu sebentar ya.'
        ];
    }
}