<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'Username' => 'admin',
            'Password' => bcrypt('admin123'),
            'Email' => 'admin@app.com',
            'NamaLengkap' => 'Admin Utama',
            'Alamat' => 'Jl. Perpustakaan No. 1',
            'Role' => 'administrator',
        ]);

        User::create([
            'Username' => 'petugas',
            'Password' => bcrypt('petugas123'),
            'Email' => 'petugas@app.com',
            'NamaLengkap' => 'Petugas Satu',
            'Alamat' => 'Jl. Buku No. 2',
            'Role' => 'petugas',
        ]);

        User::create([
            'Username' => 'peminjam',
            'Password' => bcrypt('peminjam123'),
            'Email' => 'peminjam@gmail.com',
            'NamaLengkap' => 'Budi Peminjam',
            'Alamat' => 'Jl. Pelajar No. 3',
            'Role' => 'peminjam',
        ]);
    }
}
