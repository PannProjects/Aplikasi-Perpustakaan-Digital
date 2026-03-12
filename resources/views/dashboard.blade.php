@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="card p-8 border-t-4 border-theme-purple">
        <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Total Buku</h3>
        <p class="text-4xl font-black text-theme-navy italic">{{ \App\Models\Buku::count() }}</p>
    </div>
    <div class="card p-8 border-t-4 border-theme-pink">
        <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Total Dipinjam</h3>
        <p class="text-4xl font-black text-theme-navy italic">{{ \App\Models\Peminjaman::where('StatusPeminjaman', 'Dipinjam')->count() }}</p>
    </div>
    <div class="card p-8 border-t-4 border-theme-navy">
        <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Total Anggota</h3>
        <p class="text-4xl font-black text-theme-navy italic">{{ \App\Models\User::where('Role', 'peminjam')->count() }}</p>
    </div>
</div>

<div class="mt-12">
    <div class="card">
        <div class="p-6 bg-theme-navy text-theme-offwhite font-bold italic tracking-wider uppercase">
            Aksi Cepat
        </div>
        <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            @if(in_array(auth()->user()->Role, ['administrator', 'petugas']))
                <a href="{{ route('buku.create') }}" class="btn-primary flex items-center justify-center py-4 font-bold italic">
                    Tambah Buku Baru
                </a>
                <a href="{{ route('laporan.index') }}" class="bg-gray-800 text-white flex items-center justify-center py-4 rounded-lg font-bold italic hover:bg-black transition-all">
                    Lihat Laporan
                </a>
            @else
                <a href="{{ route('peminjaman.index') }}" class="btn-primary flex items-center justify-center py-4 font-bold italic">
                    Cari & Pinjam Buku
                </a>
                <div class="bg-theme-offwhite p-4 rounded-lg border border-dashed border-theme-pink text-center italic font-medium">
                    Cek koleksi Anda untuk detail lebih lanjut
                </div>
            @endif
        </div>
    </div>

    @if(in_array(auth()->user()->Role, ['administrator', 'petugas']))
        @php
            $pendingCount = \App\Models\Peminjaman::where('StatusPeminjaman', 'Menunggu Pengembalian')->count();
        @endphp
        @if($pendingCount > 0)
        <div class="mt-8 bg-yellow-100 border-l-4 border-yellow-500 p-6 rounded-r-xl flex justify-between items-center shadow-sm">
            <div class="flex items-center space-x-4">
                <div class="p-3 bg-yellow-200 rounded-full text-yellow-700 font-black italic">!</div>
                <div>
                    <h4 class="font-black text-theme-navy italic uppercase text-sm">Konfirmasi Pengembalian</h4>
                    <p class="text-xs text-yellow-800 italic font-medium">Ada {{ $pendingCount }} permintaan pengembalian buku yang menunggu konfirmasi Anda.</p>
                </div>
            </div>
            <a href="{{ route('laporan.index') }}" class="bg-yellow-700 text-white px-6 py-2 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-yellow-800 transition-all">Lihat Semua</a>
        </div>
        @endif
    @endif
</div>
@endsection
