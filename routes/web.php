<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\PeminjamanController;

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // CRUD Buku: Administrator & Petugas
    Route::middleware(['role:administrator,petugas'])->group(function () {
        Route::resource('buku', BukuController::class)->except(['show']);
    });

    // Peminjaman: Peminjam
    Route::middleware(['role:peminjam'])->group(function () {
        Route::get('/pinjam', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::post('/pinjam/{buku}', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::post('/kembalikan/{peminjaman}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    });

    // Laporan & Konfirmasi: Administrator & Petugas
    Route::middleware(['role:administrator,petugas'])->group(function () {
        Route::get('/laporan', [PeminjamanController::class, 'laporan'])->name('laporan.index');
        Route::post('/konfirmasi/{peminjaman}', [PeminjamanController::class, 'konfirmasi'])->name('peminjaman.konfirmasi');
        Route::post('/tolak/{peminjaman}', [PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    });
});
