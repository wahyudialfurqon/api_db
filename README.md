<img src="https://user-images.githubusercontent.com/73097560/115834477-dbab4500-a447-11eb-908a-139a6edaec5c.gif">

# ThriftCycle API

## Deskripsi
ThriftCycle API adalah backend yang dikembangkan menggunakan Laravel untuk mendukung platform ThriftCycle. API ini menyediakan endpoint untuk mengelola barang bekas, pengguna, dan transaksi donasi.

## Fitur Utama
- **Manajemen Barang**: CRUD untuk barang bekas yang didonasikan.
- **Pencarian Barang**: Filter dan pencarian barang yang tersedia.

## Teknologi yang Digunakan
- **Laravel**: Framework utama backend.
- **MySQL**: Database untuk menyimpan data.
- **RESTful API**: Untuk komunikasi antar sistem.

## Instalasi
1. **Clone Repository**
   ```bash
   git clone https://github.com/wahyudialfurqon/api_db.git
   ```
2. **Masuk ke Direktori Proyek**
   ```bash
   cd api_db
   ```
3. **Pasang Dependency**
   ```bash
   composer install
   ```
4. **Konfigurasi Environment**
   ```bash
   cp .env.example .env
   ```
   Atur konfigurasi database pada file `.env`
5. **Generate Key Aplikasi**
   ```bash
   php artisan key:generate
   ```
6. **Migrasi dan Seeding Database**
   ```bash
   php artisan migrate --seed
   ```
7. **Jalankan Server Laravel**
   ```bash
   php artisan serve
   ```
