# PannPerpus - Digital Library Application

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

**PannPerpus** adalah aplikasi manajemen perpustakaan digital berbasis web yang dibangun menggunakan framework Laravel. Aplikasi ini dirancang untuk memudahkan pengelolaan pendataan buku, proses peminjaman, serta pelaporan aktivitas perpustakaan secara efisien dan terorganisir.

## 🚀 Fitur Utama

- **Sistem Autentikasi Kustom:** Sistem login dan registrasi manual dengan 3 level akses (Administrator, Petugas, Peminjam).
- **Manajemen Buku (CRUD):** Pengelolaan data buku lengkap (Judul, Penulis, Penerbit, Tahun Terbit).
- **Sistem Peminjaman & Pengembalian:**
    - Alur peminjaman buku yang mudah bagi anggota.
    - Sistem konfirmasi pengembalian oleh petugas untuk validasi fisik buku.
    - Fitur penolakan pengembalian jika data tidak sesuai.
- **Pelaporan Aktivitas:**
    - Laporan peminjaman yang dapat difilter berdasarkan rentang tanggal.
    - Tampilan laporan siap cetak (Print-ready).
- **Dashboard Multi-Role:** Ringkasan statistik yang berbeda untuk setiap peran pengguna.
- **Antarmuka Premium:** Desain modern menggunakan Tailwind CSS dengan tema warna kustom (*Navy, Purple, Pink*).
- **Lokalisasi Penuh:** Seluruh antarmuka menggunakan Bahasa Indonesia.

## 🛠️ Tech Stack

- **Backend:** Laravel (v11/v12)
- **Frontend:** HTML5, Vanilla JavaScript, Tailwind CSS (via Play CDN)
- **Database:** MariaDB / MySQL
- **Tanpa Dependency Node.js:** Dibangun murni menggunakan Laravel & CDN untuk kemudahan deployment.

## 📥 Instalasi

1. **Clone Repository**
   ```bash
   git clone
   cd aplikasi-perpustakaan-digital
   ```

2. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Lingkungan**
   Salin file `.env.example` menjadi `.env` dan sesuaikan konfigurasi database Anda.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Migrasi & Seeding Database**
   Jalankan perintah berikut untuk membuat tabel dan mengisi data awal (Admin, Petugas, Peminjam).
   ```bash
   php artisan migrate --seed
   ```

5. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```

## 🔑 Akun Demo (Seeded)

| Role | Username | Password |
| :--- | :--- | :--- |
| **Administrator** | `admin` | `admin123` |
| **Petugas** | `petugas` | `petugas123` |
| **Peminjam** | `peminjam` | `peminjam123` |

## 📐 Skema Database

Aplikasi ini menggunakan 7 tabel utama:
1. `users` - Data pengguna dan peran.
2. `buku` - Koleksi buku perpustakaan.
3. `kategoribuku` - Daftar kategori buku.
4. `kategoribuku_relasi` - Relasi antara buku dan kategori.
5. `peminjaman` - Transaksi peminjaman buku.
6. `ulasanbuku` - Ulasan dan rating dari pembaca.
7. `koleksipribadi` - Daftar buku favorit pengguna.

---