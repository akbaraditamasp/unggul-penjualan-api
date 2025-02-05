# Aplikasi Penjualan - Backend

**Mini Project Test - PT Unggul Mitra Solusi**

## Deskripsi

Ini adalah repository backend dari aplikasi penjualan, yang dikembangkan sebagai mini project test untuk PT Unggul Mitra Solusi. Backend ini berfungsi sebagai REST API untuk aplikasi frontend (web dan mobile).

---

## Tech Stack

**Backend:**

-   Laravel 11
-   MySQL
-   Laravel Octane
-   FrankenPHP

---

## Dokumentasi API

Dokumentasi lengkap REST API dapat diakses melalui:  
[https://unggul.njin.co.id/docs](https://unggul.njin.co.id/docs)

---

## Akun Demo

Gunakan akun berikut untuk mencoba fitur aplikasi:

-   **Email:** admin@unggulsolusi.com
-   **Password:** 123456

---

## Repositori Terkait

-   **Frontend Web:** [unggul-penjualan-web](https://github.com/akbaraditamasp/unggul-penjualan-web)
-   **Frontend Mobile:** [unggul-penjualan-mobile](https://github.com/akbaraditamasp/unggul-penjualan-mobile)

---

## Demo

-   **Web:** [unggul.njin.co.id](https://unggul.njin.co.id)
-   **APK (Android):** [Download APK](https://github.com/akbaraditamasp/unggul-penjualan-mobile/releases/download/1.0.0/application-4e25498c-3e34-4eff-854b-9794ff0af42f.apk)

---

## Cara Menjalankan Project

### 1. Clone Repo

```bash
git clone https://github.com/akbaraditamasp/unggul-penjualan-backend.git
cd unggul-penjualan-backend
```

### 2. Instal Dependencies

```bash
composer install
```

### 3. Konfigurasi Environment

Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi:

```bash
cp .env.example .env
```

**Pastikan untuk mengatur database dan konfigurasi lainnya:**

```env
DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database
```

### 4. Generate Key & Jalankan Migration

```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Jalankan Server

Untuk development biasa:

```bash
php artisan serve
```

Untuk menjalankan dalam mode production (FrankenPHP):

```bash
php artisan octane:start --server=frankenphp
```

---

## Fitur Utama

-   **Manajemen Pelanggan:** CRUD pelanggan
-   **Manajemen Produk:** CRUD produk.
-   **Manajemen Penjualan:** CRUD transaksi penjualan.
-   **Autentikasi:** Login dan registrasi menggunakan Laravel Sanctum.
