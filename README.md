# TesYuk!

TesYuk! adalah platform pengujian aplikasi berbasis web yang mempertemukan developer dengan tester.  
Platform ini membantu developer mendapatkan tester untuk proses pengujian aplikasi, sementara tester dapat menjalankan misi testing dan memperoleh reward berupa poin yang dapat ditukarkan ke e-wallet.

---

## Tentang Project

TesYuk! dibuat sebagai project PBL untuk sistem pengujian aplikasi.  
Sistem ini dirancang untuk membantu proses testing aplikasi setelah aplikasi lulus pengujian awal di Google Play Console.

Developer dapat mendaftarkan aplikasi, admin melakukan verifikasi, dan tester menjalankan misi testing selama 14 hari.

---

## Teknologi yang Digunakan

| Teknologi | Keterangan |
|---|---|
| Laravel 11 | Framework backend |
| Filament 3 | Admin panel dan dashboard |
| PHP 8.2+ | Bahasa pemrograman backend |
| MySQL | Database |
| Blade | Template engine Laravel |
| Tailwind CSS | Styling frontend |
| Vite | Frontend build tool |

---

## Role Pengguna

### Super Admin

Super Admin memiliki akses tertinggi untuk mengelola sistem.

Fitur utama:

- Mengelola data admin
- Memantau sistem secara keseluruhan
- Mengakses panel admin

### Admin

Admin bertugas melakukan verifikasi aplikasi yang diajukan oleh developer.

Fitur utama:

- Melihat data aplikasi yang diajukan
- Mengecek bukti pembayaran
- Mengecek bukti lulus review awal
- Menyetujui atau menolak aplikasi
- Memantau proses testing

### Developer

Developer adalah pengguna yang mendaftarkan aplikasi untuk diuji oleh tester.

Fitur utama:

- Mendaftarkan aplikasi
- Mengisi data aplikasi
- Upload bukti pembayaran
- Upload bukti lulus review awal
- Melihat daftar tester yang mengambil misi
- Memantau status testing aplikasi

### Tester

Tester adalah pengguna yang menjalankan misi testing aplikasi.

Fitur utama:

- Melihat daftar aplikasi yang tersedia
- Mengambil misi testing
- Melakukan absensi harian selama 14 hari
- Upload screenshot bukti testing
- Melaporkan bug jika ditemukan
- Mendapatkan poin reward
- Menukarkan poin ke e-wallet

---

## Alur Sistem

### Alur Developer

1. Developer login ke panel developer.
2. Developer mendaftarkan aplikasi.
3. Developer mengisi form pendaftaran aplikasi.
4. Developer melakukan pembayaran biaya upload sebesar Rp300.000.
5. Developer upload bukti pembayaran.
6. Developer upload bukti lulus review awal.
7. Developer menunggu approval dari admin.
8. Jika disetujui, aplikasi akan muncul di halaman Cari Misi milik tester.

Form aplikasi developer berisi:

| Field | Status |
|---|---|
| Nama Aplikasi | Wajib |
| Platform | Wajib |
| Deskripsi Aplikasi | Wajib |
| Link Aplikasi | Wajib |
| Bukti Pembayaran | Wajib |
| Bukti Lulus Review Awal | Wajib |

### Alur Admin

1. Admin login ke panel admin.
2. Admin melihat daftar aplikasi yang diajukan developer.
3. Admin mengecek kelengkapan data aplikasi.
4. Admin mengecek bukti pembayaran.
5. Admin mengecek bukti lulus review awal.
6. Admin menyetujui atau menolak aplikasi.
7. Jika aplikasi disetujui, aplikasi akan tersedia untuk tester.

### Alur Tester

1. Tester login ke panel tester.
2. Tester membuka menu Cari Misi.
3. Tester mengambil misi aplikasi yang tersedia.
4. Tester menunggu testing dimulai setelah kuota tester terpenuhi.
5. Tester menjalankan misi testing selama 14 hari.
6. Setiap hari tester wajib melakukan absensi testing.
7. Tester dapat melaporkan bug jika ditemukan.
8. Setelah menyelesaikan misi 14 hari, tester mendapatkan reward poin.
9. Tester dapat menukarkan poin ke e-wallet.

---

## Alur Testing 14 Hari

Tester wajib menjalankan testing selama 14 hari.

Setiap hari tester wajib:

- Membuka aplikasi minimal 5 menit
- Upload screenshot bukti penggunaan aplikasi
- Mengirim laporan harian

Jika menemukan bug, tester dapat mengirim:

- Screenshot bagian yang bermasalah
- Deskripsi bug
- Catatan tambahan

Laporan bug bersifat opsional, tetapi jika tester menemukan bug maka bukti screenshot dan deskripsi bug wajib diisi.

---

## Reward Tester

Sistem reward menggunakan poin.

| Poin | Nilai |
|---|---|
| 1 poin | Rp1.000 |
| 10 poin | Rp10.000 |

Setelah tester menyelesaikan misi selama 14 hari, tester akan mendapatkan reward sebesar 10 poin.

Poin tersebut dapat ditukarkan melalui fitur Penukaran Poin ke e-wallet tester.

---

## Pembayaran Developer

Developer membayar biaya upload aplikasi sebesar:

```txt
Rp300.000