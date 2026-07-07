# VyanTravel

Aplikasi web pemesanan paket wisata berbasis PHP native dengan arsitektur MVC sederhana. Proyek ini dikembangkan sebagai tugas UAS Praktikum Pemrograman Web Lanjut (PWL) dengan fokus pada manajemen paket wisata, proses booking, serta role pengguna admin dan pelanggan.

## Anggota Kelompok
1. Muhammad Sultan Hafidz - A12.2024.07163
2. Dimas Arya Pratama - A12.2024.07180
3. Muhammad Rafael Rasya N - A12.2024.07153
4. Muhammad Rafly Setyo Handika - A12.2024.07183

## Deskripsi Proyek
VyanTravel adalah sistem informasi perjalanan yang memudahkan pelanggan untuk:
- melihat daftar paket wisata,
- melakukan pemesanan paket,
- melihat status booking,
- serta mengelola data booking secara lebih terstruktur.

Sementara itu, admin dapat mengelola:
- paket wisata,
- data booking,
- dan dashboard administrasi secara mudah.

## Fitur Utama
- Autentikasi pengguna (login dan register)
- Dashboard pelanggan dan admin
- Manajemen paket wisata (CRUD)
- Proses booking paket wisata
- Tampilan halaman detail paket
- Pengelolaan booking oleh admin
- Desain antarmuka yang responsif

## Teknologi yang Digunakan
- PHP
- MySQL
- Bootstrap / CSS custom
- MVC Pattern
- PDO untuk koneksi database

## Struktur Folder
- app/Controllers : controller aplikasi
- app/Models : model untuk data dan query
- app/Views : tampilan halaman web
- config/ : konfigurasi aplikasi dan database
- database/ : file SQL database
- public/ : entry point aplikasi

## Persiapan dan Jalankan Aplikasi
1. Pastikan XAMPP atau server lokal sudah aktif.
2. Import database dari file berikut:
   - database/vyantravel_db.sql
3. Pindah ke folder proyek Anda, misalnya:
   - D:\xampp\htdocs\UAS-PWL-KELOMPOK
4. Buka browser dan akses:
   - http://localhost/UAS-PWL-KELOMPOK/public

## Cara Menggunakan
- Daftar akun baru melalui halaman register.
- Login sebagai pelanggan untuk melihat paket wisata dan melakukan booking.
- Login sebagai admin untuk mengelola paket dan booking.

## Catatan
Proyek ini dibuat untuk memenuhi kebutuhan tugas UAS PWL dan dapat dikembangkan lebih lanjut sesuai kebutuhan bisnis atau pembelajaran.
