# Modul Pembelajaran: Aplikasi Perpustakaan Digital (Laravel)

Modul ini disusun khusus untuk membantu Anda memahami alur kerja dan struktur kode dari **Aplikasi Perpustakaan Digital**. Pembelajaran ini mencakup analisis dari struktur Database hingga tampilan Antarmuka (Blade Template). Bahasa yang digunakan dibuat mudah dipahami bagi siapa saja yang baru belajar Laravel.

---

## 1. Konsep Database (Migrations)

Database adalah pondasi dari aplikasi ini. Laravel menggunakan file `Migration` (terdapat di folder `database/migrations`) untuk merancang dan membuat tabel di database secara otomatis tanpa harus membuka aplikasi database manajemen.

> [!NOTE] 
> Struktur _Primary Key_ pada aplikasi ini sedikit unik. Aplikasinya menggunakan format kustom seperti `UserID` dan `BukuID` dibandingkan format default Laravel yang hanya bernama `id`.

**Struktur Tabel Utama meliputi:**
- **`users`**: Tabel pengguna dengan fitur tingkat hak akses pengguna (Role) yang dibagi menjadi: `administrator`, `petugas`, dan `peminjam`.
- **`buku`**: Menyimpan pendataan koleksi buku (Judul, Penulis, Penerbit, Tahun Terbit).
- **`kategoribuku` & `kategoribuku_relasi`**: Tabel untuk mengelompokkan buku berdasarkan kategorinya (Relasi Many-to-Many).
- **`peminjaman`**: Jantung dari aplikasi ini. Mencatat seluruh transaksi peminjaman seperti buku apa yang dipinjam (`BukuID`), siapa yang meminjam (`UserID`), kapan batas akhirnya (`TanggalPengembalian`), hingga kejelasan `StatusPeminjaman`.

---

## 2. Model & Relasi (Eloquent ORM)

Model (direktori `app/Models`) berfungsi sebagai perwakilan cerdas untuk berinteraksi dengan database. Melalui Model, kita tidak perlu lagi repot menuliskan syntax *query SQL* murni yang panjang.

Contoh pembedahan pada `app/Models/Buku.php`:
```php
class Buku extends Model {
    protected $table = 'buku';       // Mengatur nama mutlak tabel di database
    protected $primaryKey = 'BukuID';// Mengubah standar "id" menjadi "BukuID"
    
    // Kolom apa saja yang diizinkan untuk diisi secara paksa/masal?
    protected $fillable = ['Judul', 'Penulis', 'Penerbit', 'TahunTerbit'];
    
    // Konfigurasi Relasi (Satu buku -> bisa dipinjam berkali-kali)
    public function peminjaman() {
        return $this->hasMany(Peminjaman::class, 'BukuID', 'BukuID');
    }
}
```
**Konsep Penting:**
Pemanggilan sintaks seperti `hasMany()` (Memiliki Banyak) dan `belongsToMany()` (Bagian Dari Banyak) memudahkan kita saat ingin memanggil rincian peminjaman beserta seluruh judul bukunya dalam satu perintah singkat.

---

## 3. Sistem Keamanan & Satpam Aplikasi (Middleware)

Middleware (direktori `app/Http/Middleware`) bertugas seperti satpam gedung yang mencegat pengunjung sebelum mereka bisa masuk ke sebuah halaman web.

Pada `RoleMiddleware.php`:
- Pos Pemeriksaan 1: Memastikan pengunjung harus **Login** terlebih dahulu (`auth()->check()`).
- Pos Pemeriksaan 2: Memeriksa tingkat Role dari pengunjung (`administrator`, `petugas`, atau `peminjam`).
- Keputusan: Jika level hak akses ternyata tidak diizinkan melintasi jalur ini, pengunjung langsung dialihkan ke Halaman `403 Unauthorized` (Anda tidak memiliki akses).

---

## 4. Jalur Peta Situs (Routes)

Route menentukan URL (Tautan/Link) apa yang harus dihubungkan dengan halaman/fitur apa. Sistem Route diletakkan sebagai peta pusat di `routes/web.php`.

```php
Route::middleware(['auth'])->group(function () {
    // Fitur Manajemen Buku: Diperuntukkan hanya bagi Administrator & Petugas
    Route::middleware(['role:administrator,petugas'])->group(function () {
        Route::resource('buku', BukuController::class);
    });

    // Fitur Peminjaman Asli: Spesifik hanya untuk Role Peminjam (User)
    Route::middleware(['role:peminjam'])->group(function () {
        Route::get('/pinjam', [PeminjamanController::class, 'index']);
    });
});
```
**Penjelasan:**
Aplikasi ini membungkus seluruh tautan aplikasinya dalam _selimut perlindungan_ Autentikasi (`auth`). Jika seseorang berhasil lewat selimut itu, mereka akan masuk ke jalur khusus sesuai level jabatannya.

---

## 5. Pengendali Logika (Controllers)

Controller (direktori `app/Http/Controllers`) menjadi otak penengah (bridging) antara Permintaan Pengguna, Model (Database), dan Blade (Tampilan).

### A. `BukuController` (Mengurus Data Katalog)
Dipakai oleh pengurus perpustakaan merawat buku. Jika menambah buku, Controller bertugas memastikan isiannya tidak boleh nakal (Validasi):
```php
$request->validate([
    'Judul' => 'required',
    // Tidak boleh kosong dan format harus berupa angka real
    'TahunTerbit' => 'required|integer', 
]);
```

### B. `PeminjamanController` (Logika Bisnis Transaksi Transisi)
Mengawal alur peminjaman yang pintar:
1. **Meminjam Buku**: Mencatat tanggal peminjaman dan secara mandiri menetapkan tanggal pengembalian `7 Hari ke depan` secara otomatis menggunakan penanggalan dari sistem lokal (Modul *Carbon*). Status akan tertulis *'Dipinjam'*.
2. **Saat Ingin Mengembalikan**: Pengguna menekan tombol kembalikan -> Status berubah sementara menjadi *'Menunggu Pengembalian'*.
3. **Validasi Fisik Admin**: Admin memastikan fisik buku kembali baik, kemudian menyetujuinya -> Status final berubah menjadi *'Dikembalikan'* dengan stempel waktu detik tersebut.

---

## 6. Antarmuka Pengguna (Blade Templates)

Blade adalah mesin canggih pembuat antarmuka dari Laravel. Terletak di palet artis `resources/views`.

### Contoh pembedahan file `dashboard.blade.php`:
1. **Pewarisan Desain (Extends Layout)**:
   Baris paling atas memakai `@extends('layouts.app')` yang berarti halaman dashboard hanya fokus mengembangkan apa isi content-nya, sedangkan kepala/Navigation Bar sudah otomatis dipinjam dari kerangka utama.
   
2. **Logika Kondisi Visual (Directives)**:
   File Tampilan Blade memahami kode seakan ia hidup:
   ```html
   @if(in_array(auth()->user()->Role, ['administrator', 'petugas']))
       <a href="/buku/create">TOMBOL Admin: Tambah Buku</a>
   @else
       <a href="/peminjaman">TOMBOL Member: Pinjam Buku Disini</a>
   @endif
   ```
   **Keistimewaan**: Interface akan _berubah wujud_ (Responsive & Dynamic User Interface) untuk peranan berbeda padahal file aplikasinya tidak berubah dan hanya file itu saja saturnya.

3. **Data Dinamis (Data Seeding)**:
   Perhitungan asli dari database bisa ditanam ke layar secara instan: `{{ \App\Models\Buku::count() }}`. Tulisan ini di layar pengunjung tidak terlihat sebagai kode, melainkan berubah menjadi jumlah murni seperti *"145"*.

> [!TIP]
> **Dimana Saya Harus Memulai Modifikasi Program Ini?**
> Jika ingin menambah sebuah halaman baru, mulailah dengan siklus fundamental Laravel: (1) Buat *URL Route*-nya di `routes/web.php` 👉 (2) Ciptakan *Method* logic-nya di `Controller` 👉 (3) Design halamannya dengan `nama.blade.php`.
