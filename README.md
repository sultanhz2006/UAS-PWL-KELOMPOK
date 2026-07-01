# ✈ VyanTravel — E-Travel Web Application

Aplikasi E-Travel berbasis PHP Native murni dengan arsitektur **MVC** (Model-View-Controller), menggunakan Bootstrap 5 untuk tampilan yang modern dan responsif.

---

## 📁 Struktur Folder

```
VyanTravel/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php       # Login, Register, Logout
│   │   ├── AdminController.php      # CRUD Paket Wisata, Booking Management
│   │   └── PelangganController.php  # Browse Paket, Booking, Download Tiket
│   ├── Core/
│   │   ├── BaseController.php       # View renderer, redirect, flash, json
│   │   └── Router.php               # URL dispatcher dengan parameter support
│   ├── Middleware/
│   │   └── AuthMiddleware.php       # Role-based access control
│   ├── Models/
│   │   ├── UserModel.php            # CRUD user dengan PDO
│   │   ├── PaketWisataModel.php     # CRUD paket wisata
│   │   └── BookingModel.php         # CRUD booking + statistik
│   └── Views/
│       ├── admin/
│       │   ├── dashboard.php
│       │   ├── paket/{index,create,edit}.php
│       │   └── booking/index.php
│       ├── pelanggan/
│       │   ├── dashboard.php
│       │   ├── paket.php
│       │   ├── paket_detail.php     # Termasuk widget cuaca API
│       │   └── booking.php
│       ├── auth/{login,register}.php
│       ├── layouts/{main,auth}.php  # Layout template
│       └── errors/{403,404}.php
├── config/
│   ├── app.php                      # Konstanta & konfigurasi global
│   └── Database.php                 # Singleton PDO connection
├── database/
│   └── schema.sql                   # DDL + seed data
├── public/
│   ├── index.php                    # Front Controller (entry point)
│   ├── .htaccess                    # URL rewriting + security headers
│   └── uploads/paket/               # Foto paket wisata
└── storage/
    └── pdf_tickets/                 # E-ticket PDF/HTML
```

---

## ⚡ Cara Install & Setup

### Prasyarat
- PHP 8.1+
- MySQL 5.7+ / MariaDB 10.3+
- Apache dengan `mod_rewrite` aktif (atau Nginx dengan config sejenis)
- Composer (opsional, untuk TCPDF di production)

### Langkah Setup

**1. Clone / ekstrak project ke folder web server**
```bash
# Apache: /var/www/html/VyanTravel
# XAMPP : C:/xampp/htdocs/VyanTravel
# Laragon: C:/laragon/www/VyanTravel
```

**2. Import database**
```bash
mysql -u root -p < database/schema.sql
```
Atau buka phpMyAdmin → Import → pilih `database/schema.sql`.

**3. Konfigurasi database**

Edit `config/Database.php`:
```php
private static string $host = 'localhost';
private static string $db   = 'vyantravel_db';
private static string $user = 'root';
private static string $pass = '';      // Sesuaikan password DB Anda
```

**4. Set BASE_URL**

Edit `config/app.php`:
```php
define('BASE_URL', 'http://localhost/VyanTravel/public');
// Atau jika di root: 'http://localhost'
```

**5. Atur permission folder**
```bash
chmod -R 775 public/uploads/paket/
chmod -R 775 storage/pdf_tickets/
```

**6. Aktifkan API Cuaca (Opsional)**

Daftar gratis di [openweathermap.org](https://openweathermap.org/api) → salin API key → isi di `config/app.php`:
```php
define('OPENWEATHER_API_KEY', 'your_real_api_key_here');
```

**7. Akses aplikasi**

Buka browser: `http://localhost/VyanTravel/public`

---

## 🔐 Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@vyantravel.com | Admin@1234 |
| User | user1@gmail.com | user1user1 |

> ⚠️ **Ganti password admin** setelah pertama login di production!

---

## 🔒 Fitur Keamanan

| Fitur | Implementasi |
|-------|-------------|
| Password Hashing | `password_hash()` BCRYPT cost=12 |
| SQL Injection Prevention | PDO Prepared Statements di semua query |
| Session Fixation | `session_regenerate_id(true)` saat login |
| Session Hijacking | `httponly` + `samesite=Lax` cookie |
| File Upload Validation | Cek ekstensi + MIME type via `mime_content_type()` |
| Role-Based Access | AuthMiddleware di setiap controller |
| XSS Prevention | `htmlspecialchars()` di semua output |
| Directory Listing | `Options -Indexes` di .htaccess |

---

## 🌐 Integrasi API

**OpenWeatherMap** — Cuaca real-time di destinasi wisata.

File: `app/Controllers/PelangganController.php` → method `getCuacaDestinasi()`

```php
// Contoh implementasi cURL
$ch = curl_init();
curl_setopt_array($ch, [
    CURLOPT_URL            => "https://api.openweathermap.org/data/2.5/weather?q={$kota}&appid={$apiKey}&units=metric",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 5,
]);
$response = curl_exec($ch);
```

---

## 📄 PDF E-Ticket

Tiket dihasilkan otomatis saat booking berhasil.
Untuk PDF sungguhan di production, install **TCPDF**:

```bash
composer require tecnickcom/tcpdf
```

Kemudian uncomment blok TCPDF di `PelangganController::generatePdfTiket()`.

---

## 🚀 Alur Aplikasi

```
Pengunjung → /login atau /register
    │
    ├─ Login berhasil (role: admin)     → /admin/dashboard
    │      ├── Kelola Paket Wisata (CRUD + Upload Foto)
    │      └── Kelola Booking (Update Status)
    │
    └─ Login berhasil (role: pelanggan) → /pelanggan/dashboard
           ├── Browse Paket Wisata (+ Cek Cuaca API)
           ├── Booking Paket
           └── Download E-Ticket PDF
```

---

## 🛠️ Teknologi

- **Backend**: PHP 8.1+ Native (MVC tanpa framework)
- **Database**: MySQL + PDO Prepared Statements
- **Frontend**: Bootstrap 5.3 + Bootstrap Icons + Google Fonts Inter
- **API**: OpenWeatherMap REST API via cURL
- **PDF**: HTML-to-PDF (placeholder; gunakan TCPDF di production)
