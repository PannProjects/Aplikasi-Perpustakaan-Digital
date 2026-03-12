<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('UserID');
            $table->string('Username')->unique();
            $table->string('Password');
            $table->string('Email')->unique();
            $table->string('NamaLengkap');
            $table->text('Alamat');
            $table->enum('Role', ['administrator', 'petugas', 'peminjam']);
            $table->timestamps();
        });

        Schema::create('buku', function (Blueprint $table) {
            $table->id('BukuID');
            $table->string('Judul');
            $table->string('Penulis');
            $table->string('Penerbit');
            $table->integer('TahunTerbit');
            $table->timestamps();
        });

        Schema::create('kategoribuku', function (Blueprint $table) {
            $table->id('KategoriID');
            $table->string('NamaKategori');
            $table->timestamps();
        });

        Schema::create('kategoribuku_relasi', function (Blueprint $table) {
            $table->id('KategoriBukuID');
            $table->foreignId('BukuID')->constrained('buku', 'BukuID')->onDelete('cascade');
            $table->foreignId('KategoriID')->constrained('kategoribuku', 'KategoriID')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('PeminjamanID');
            $table->foreignId('UserID')->constrained('users', 'UserID')->onDelete('cascade');
            $table->foreignId('BukuID')->constrained('buku', 'BukuID')->onDelete('cascade');
            $table->date('TanggalPeminjaman');
            $table->date('TanggalPengembalian');
            $table->string('StatusPeminjaman');
            $table->timestamps();
        });

        Schema::create('ulasanbuku', function (Blueprint $table) {
            $table->id('UlasanID');
            $table->foreignId('UserID')->constrained('users', 'UserID')->onDelete('cascade');
            $table->foreignId('BukuID')->constrained('buku', 'BukuID')->onDelete('cascade');
            $table->text('Ulasan');
            $table->integer('Rating');
            $table->timestamps();
        });

        Schema::create('koleksipribadi', function (Blueprint $table) {
            $table->id('KoleksiID');
            $table->foreignId('UserID')->constrained('users', 'UserID')->onDelete('cascade');
            $table->foreignId('BukuID')->constrained('buku', 'BukuID')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('koleksipribadi');
        Schema::dropIfExists('ulasanbuku');
        Schema::dropIfExists('peminjaman');
        Schema::dropIfExists('kategoribuku_relasi');
        Schema::dropIfExists('kategoribuku');
        Schema::dropIfExists('buku');
        Schema::dropIfExists('users');
    }
};
