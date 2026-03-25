# 📚 Library Management System (Fullstack Monorepo)

Project ini adalah sistem manajemen perpustakaan yang dibangun sebagai bagian dari Technical Test untuk posisi Engineer. Sistem ini mengelola data Penulis (Authors), Penerbit (Publishers), dan Buku (Books) menggunakan arsitektur Fullstack Monorepo (REST API & Frontend disajikan dalam satu framework untuk efisiensi).

## ✨ Fitur Unggulan (Menemenuhi Semua Requirement)
- **RESTful API Backend**: Endpoint CRUD lengkap untuk Books, Authors, dan Publishers.
- **JWT Authentication**: Proteksi API yang aman menggunakan `tymondesigns/jwt-auth`.
- **Database Relasional**: Relasi 1:N (Author -> Books, Publisher -> Books) melalui Eloquent ORM.
- **Multi-parameter Filtering & Sorting**: Mendukung pencarian teks (Search), filter Dropdown (Author & Publisher), dan pengurutan (Sorting) yang berjalan harmonis secara bersamaan di sisi server.
- **Frontend SPA-like dengan UI Minimalis**: Antarmuka modern yang bersih (Clean & Minimalist). Fetching data, filter, dan sorting dilakukan secara asinkron (Axios) tanpa reload halaman.
- **Interactive Data Table**: Header tabel dapat diklik untuk mengubah urutan data (Ascending/Descending).
- **Seeder & Factory**: Dilengkapi dengan data dummy untuk kemudahan pengujian fitur list dan relasi.

## 🛠️ Tech Stack
- **Backend:** Laravel 13, PHP 8.3+, MySQL.
- **Frontend:** Laravel Blade, TailwindCSS v3, Javascript ES6 (Axios).
- **Authentication:** JSON Web Token (JWT).

## 🚀 Panduan Instalasi (Lokal)

Ikuti langkah-langkah berikut untuk menjalankan project ini di komputer lokal Anda:

### 1. Prasyarat
Pastikan Anda sudah menginstal:
- PHP (Min. versi 8.3) & Composer
- Node.js & NPM
- MySQL Database

### 2. Clone & Setup Lingkungan
```bash
# Clone repository ini (jika menggunakan Git)
# git clone <url-repo-anda>
# cd library-app

# Install dependensi PHP
composer install

# Install dependensi Node.js (Frontend)
npm install
```

### 3. Setup Konfigurasi (.env)
Copy file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan kredensial database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library_db  # Pastikan Anda sudah membuat database kosong dengan nama ini
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Key & Migrasi Database
Jalankan perintah berikut secara berurutan untuk men-generate application key, JWT secret, dan mengisi database dengan struktur tabel beserta data seeder:
```bash
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed
```

### 5. Menjalankan Server
Karena menggunakan integrasi Vite untuk TailwindCSS, Anda perlu menjalankan dua server secara bersamaan (di dua terminal terpisah):

**Terminal 1 (Backend PHP):**
```bash
php artisan serve
```

**Terminal 2 (Frontend Vite):**
```bash
npm run dev
```

Akses aplikasi melalui browser di: `http://127.0.0.1:8000`

## 🔐 Kredensial Login Demo
Gunakan akun berikut untuk masuk ke dalam Dashboard Admin (Akun ini digenerate otomatis melalui Seeder):
- **Email:** `admin@test.com`
- **Password:** `password123`

## 📡 API Documentation (Postman)
Project ini dilengkapi dengan file Postman Collection yang mencakup seluruh endpoint CRUD dan Authentication. 
Silakan import file **`Library_API_Postman_Collection.json`** yang terdapat di *root folder* ke dalam aplikasi Postman Anda.

---
*Dibuat oleh Muhammad Lutfi Amin Ghifarullah untuk Technical Test EDP gits.id.*