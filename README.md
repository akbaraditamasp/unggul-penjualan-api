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

## Repositori Terkait

-   **Frontend Web:** [unggul-penjualan-web](https://github.com/akbaraditamasp/unggul-penjualan-web)
-   **Frontend Mobile:** [unggul-penjualan-mobile](https://github.com/akbaraditamasp/unggul-penjualan-mobile)

---

## Demo

-   **Web:** [unggul.njin.co.id](https://unggul.njin.co.id)
-   **APK (Android):** [Download APK]({placeholder})

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
