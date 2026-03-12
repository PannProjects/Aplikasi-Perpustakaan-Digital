@extends('layouts.app')

@section('header', 'Tambah Buku')

@section('content')
<div class="card max-w-2xl mx-auto shadow-2xl">
    <div class="p-6 bg-theme-navy text-theme-offwhite font-bold italic uppercase tracking-wider">
        Formulir Input Buku
    </div>
    <form action="{{ route('buku.store') }}" method="POST" class="p-8">
        @csrf
        <div class="mb-6">
            <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Judul">Judul Buku</label>
            <input type="text" name="Judul" id="Judul" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all italic font-medium" placeholder="Contoh: Laskar Pelangi" required>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Penulis">Penulis</label>
                <input type="text" name="Penulis" id="Penulis" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all italic font-medium" placeholder="Nama Penulis" required>
            </div>
            <div>
                <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Penerbit">Penerbit</label>
                <input type="text" name="Penerbit" id="Penerbit" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all italic font-medium" placeholder="Nama Penerbit" required>
            </div>
        </div>
        <div class="mb-8">
            <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="TahunTerbit">Tahun Terbit</label>
            <input type="number" name="TahunTerbit" id="TahunTerbit" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all italic font-medium" placeholder="E.g. 2024" required>
        </div>
        <div class="flex items-center space-x-4">
            <button type="submit" class="flex-1 btn-primary py-4 font-bold italic uppercase tracking-widest shadow-lg">Simpan Buku</button>
            <a href="{{ route('buku.index') }}" class="btn-secondary py-4 font-bold italic uppercase tracking-widest text-center">Batal</a>
        </div>
    </form>
</div>
@endsection
