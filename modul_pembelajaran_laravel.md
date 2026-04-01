# Modul Pembelajaran Komprehensif: Aplikasi Perpustakaan Digital (Laravel)

Modul ini disusun untuk membedah kode sumber dari **Aplikasi Perpustakaan Digital**. Mulai dari rancangan Database hingga logika Tampilan, setiap sintaks akan dibahas baris demi baris agar mudah dipahami, baik bagi pemula maupun lanjutan.

---

## 1. Database (Migrations)

File Migration (`database/migrations/...`) adalah cetak biru (blueprint) untuk membangun tabel database. Kita tidak perlu membuat tabel satu per satu lewat aplikasi seperti phpMyAdmin, cukup melalui kode PHP ini.

### Contoh Sintaks: Tabel `buku` & Tabel `peminjaman`

```php
// File: 2026_03_12_004904_create_digital_library_tables.php

public function up(): void
{    
    // 1. Membuat tabel 'buku' biasa
    Schema::create('buku', function (Blueprint $table) {
        $table->id('BukuID');              // Membuat kolom Primary Key dengan nama khusus "BukuID"
        $table->string('Judul');           // Kolom teks pendek (Varchar) untuk Judul
        $table->integer('TahunTerbit');    // Kolom angka bulat untuk Tahun Terbit
        $table->timestamps();              // Otomatis membuat 2 kolom: created_at & updated_at
    });

    // 2. Membuat tabel 'peminjaman' sebagai anak/relasi dari tabel 'buku'
    Schema::create('peminjaman', function (Blueprint $table) {
        $table->id('PeminjamanID');
        
        // Relasi (Foreign Key) yang merujuk ke 'UserID' di tabel 'users'. 
        // cascade = jika user dihapus, riwayat peminjamannya otomatis ikut terhapus.
        $table->foreignId('UserID')->constrained('users', 'UserID')->onDelete('cascade');
        $table->foreignId('BukuID')->constrained('buku', 'BukuID')->onDelete('cascade');
        
        $table->date('TanggalPeminjaman'); // Kolom tipe Tanggal (YYYY-MM-DD)
        $table->string('StatusPeminjaman');
        $table->timestamps();
    });
}
```

**Penjelasan Sintaks:**
- `Schema::create`: Perintah ke database untuk membuat sebuah tabel baru.
- `Blueprint $table`: Kerangka struktur kolom. Anda menambahkan kolom dengan memanggil variable `$table` ini.
- `constrained()`: Perintah ampuh pembentuk "Foreign Key". Kita memaksa kolom ini agar isinya harus tersambung dengan tabel targetnya.

---

## 2. Models (Penghubung Database)

Model (`app/Models`) berperan sebagai perwakilan masing-masing Tabel. Dengan model, kita bisa menggunakan **Eloquent ORM**, di mana kita memanggil database layaknya objek PHP biasa tanpa mengetik SQL (`SELECT * FROM...`).

### Contoh Sintaks: `Buku.php`

```php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    // 1. Konfigurasi Dasar
    protected $table = 'buku';         // (Opsional) Mengikat model ke tabel "buku"
    protected $primaryKey = 'BukuID';  // PENTING! Beritahu Laravel kalau ID kita bukan 'id', tapi 'BukuID'

    // 2. Izin Pengisian Masal
    protected $fillable = [
        'Judul', 'Penulis', 'Penerbit', 'TahunTerbit'
    ]; // Kolom-kolom ini wajib didaftarkan agar bisa disisipkan data sekaligus (Mass Assignment)

    // 3. Konfigurasi Relasi "HasMany" (Satu Ke Banyak)
    public function peminjaman()
    {
        // "Buku ini bisa punya BANYAK riwayat peminjaman"
        // Target: Model Peminjaman. Disambungkan melalui kolom 'BukuID'.
        return $this->hasMany(Peminjaman::class, 'BukuID', 'BukuID');
    }
}
```

**Penjelasan Sintaks:**
- `$fillable`: Lapisan Keamanan. Laravel menolak mengisi data secara "rombongan" tanpa izin dari variabel ini (melindungi dari *Mass-Assignment Vulnerability*).

---

## 3. Jalur Aplikasi (Routes)

Routes (`routes/web.php`) adalah pintu gerbang aplikasi. Ia mengatur saat pengunjung mengetik URL tertentu / klik tombol apa, sistem harus merespon dengan alat apa.

### Contoh Sintaks: Pengelompokan & Hak Akses

```php
// Menggunakan 'middleware' untuk memastikan yg masuk hanyalah org yang sudah login
Route::middleware(['auth'])->group(function () {
    
    // Group untuk Hak Akses Khusus: Hanya Administrator dan Petugas
    Route::middleware(['role:administrator,petugas'])->group(function () {
        // resource() = Jalan pintas membuat 7 URL sekaligus (index, create, store, edit, update, destroy)
        Route::resource('buku', BukuController::class)->except(['show']);
    });

    // Group untuk Hak Akses Khusus: Hanya bagi User Biasa (Peminjam)
    Route::middleware(['role:peminjam'])->group(function () {
        // [NamaController::class, 'NamaFungsi']
        Route::post('/pinjam/{buku}', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    });

});
```

**Penjelasan Sintaks:**
- `middleware([...])`: Memanggil Satpam "auth" dan "role".
- `Route::post`: Metode spesifik untuk menerima "Kirim Data dari Form" ke server. 
- `{buku}`: Parameter dinamis di URL (Misal url akan berubah menjadi: `/pinjam/45`). Nilai `45` ini akan dikirim ke Controller.
- `->name(...)`: Memberi julukan unik ke rute ini. Nantinya di file HTML, kita cukup memanggil julukannya daripada menulis URL panjangnya.

---

## 4. Pengendali Logika (Controllers)

Controllers (`app/Http/Controllers/...`) adalah Otak aplikasi. Menerima data dari Route, mengolahnya ke Model, lalu melemparkan hasilnya ke Tampilan (Blade).

### Contoh Sintaks: Menyimpan & Menambah Buku Baru (BukuController.php)

```php
use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Fungsi 'store' dipicu oleh Route dari form pendaftaran buku baru
    public function store(Request $request)
    {
        // 1. PROSES VALIDASI
        // $request berisi seluruh data ketikan (input) form dari pengunjung
        $request->validate([
            'Judul' => 'required',             // Wajib diisi!
            'TahunTerbit' => 'required|integer'// Wajib diisi dan harus berupa angka!
        ]);

        // 2. PROSES INSERT DATABASE
        // Menanam data ke tabel mengandalkan model 'Buku'
        Buku::create($request->all());

        // 3. PROSES HALUAN / REDIRECT
        // Melempar pengunjung kembali ke halaman daftar buku membawa "Pesan Sukses"
        return redirect()->route('buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }
}
```

**Penjelasan Sintaks:**
- `Request $request`: Alat penangkap data _Post/Get_ (misal text dari form `input name="Judul"` akan tertangkap oleh alat ini menjadi `$request->Judul`).
- `$request->validate([...])`:  Akan langsung otomatis menggagalkan proses dan mengembalikan pengguna ke halaman sebelumnya dengan memunculkan pesan _Error_ berwarna merah jika aturan tidak terpenuhi.
- `Buku::create(...)`: Query SQL cerdas tanpa `INSERT INTO`. Langsung menyimpan array ke basis data.

---

## 5. Tampilan (Blade Templates)

Blade (`resources/views/...`) adalah alat canggih membuat HTML yang interaktif dengan membubuhkan simbol `@` (Directives). 

### Contoh Sintaks: `resources/views/buku/index.blade.php`

```html
<!-- 1. Pewarisan: Ambil wajah / template utama dari file 'layouts/app.blade.php' -->
@extends('layouts.app')

<!-- 2. Isi Area Content yang bolong pada template 'layouts/app.blade.php' -->
@section('content')

    <!-- Mencetak Link berdasarkan URL Julukan tadi -->
    <a href="{{ route('buku.create') }}">Tambah Buku</a>
    
    <table>
        <tbody>
            <!-- 3. Perulangan Logika Blade (Mirip foreach di PHP) -->
            <!-- $buku diterima dari BukuController `view('buku.index', compact('buku'))` -->
            @foreach($buku as $item)
            <tr>
                <!-- $loop->iteration otomatis mencetak angka 1, 2, 3 berurutan -->
                <td>{{ $loop->iteration }}</td>
                <!-- $item->Judul memanggil data dari tabel Database -->
                <td>{{ $item->Judul }}</td>
                
                <td>
                    <!-- Link ubah. Contoh hasil URL: /buku/15/edit -->
                    <a href="{{ route('buku.edit', $item->BukuID) }}">Ubah</a>
                    
                    <!-- 4. Form Penghapusan Data (Fitur Keamanan CSRF) -->
                    <!-- Karena HTML hanya tahu GET dan POST, kita akali dengan '@method' -->
                    <form action="{{ route('buku.destroy', $item->BukuID) }}" method="POST">
                        @csrf 
                        @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

@endsection
```

**Penjelasan Sintaks:**
- `{{ ... }}`: Ini sama halnya dengan `<?php echo ...; ?>`. Mencetak data dan menangkal serangan XSS secara otomatis.
- `@foreach ... @endforeach`: Mengulang *block HTML* sejumlah data yang ada ditarik dari tabel. Jika ada 10 buku, maka `<tr>` (baris tabel) otomatis tergandakan jadi 10 kali.
- `@method('DELETE')` : Membantu Laravel mengizinkan lalu lintas Route penghapusan data dengan aman.
- `@csrf`: **Sangat krusial untuk Form**. Menempatkan token kunci rahasia ke dalam form. Tanpa `@csrf`, form `POST` mana pun di Laravel akan memunculkan halaman error *419 Page Expired* untuk mencegah peretasan (Cross-Site Request Forgery).
