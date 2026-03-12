@extends('layouts.app')

@section('header', 'Laporan Peminjaman')

@section('content')
<div class="card shadow-2xl border-t-8 border-theme-navy">
    <div class="p-8 border-b bg-theme-offwhite">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-2xl font-black text-theme-navy italic tracking-tighter">Laporan Aktivitas Perpustakaan</h3>
                <p class="text-gray-500 italic font-medium">Rekapitulasi data peminjaman buku oleh anggota</p>
            </div>
            <button onclick="window.print()" class="btn-primary text-sm font-bold italic px-8 py-3 no-print">CETAK LAPORAN</button>
        </div>
        
        <!-- Filter Tanggal -->
        <form action="{{ route('laporan.index') }}" method="GET" class="no-print bg-white p-6 rounded-xl border border-gray-100 flex flex-wrap items-end gap-4 shadow-sm">
            <div>
                <label class="block text-xs font-black text-theme-navy uppercase mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-theme-purple outline-none">
            </div>
            <div>
                <label class="block text-xs font-black text-theme-navy uppercase mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-4 py-2 rounded-lg border border-gray-200 text-sm focus:ring-2 focus:ring-theme-purple outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-primary text-xs font-bold uppercase py-2 px-6">Filter</button>
                <a href="{{ route('laporan.index') }}" class="btn-secondary text-xs font-bold uppercase py-2 px-6">Reset</a>
            </div>
        </form>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-white text-theme-navy border-b-2 border-theme-purple uppercase text-xs font-black italic tracking-widest">
                    <th class="px-8 py-6">Peminjam</th>
                    <th class="px-8 py-6">Judul Buku</th>
                    <th class="px-8 py-6">Tgl Pinjam</th>
                    <th class="px-8 py-6">Tgl Kembali</th>
                    <th class="px-8 py-6 text-center">Status</th>
                    <th class="px-8 py-6 text-center no-print">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 font-medium italic">
                @foreach($peminjaman as $item)
                <tr class="hover:bg-theme-offwhite transition-colors">
                    <td class="px-8 py-6">
                        <div class="font-black text-theme-navy">{{ $item->user->NamaLengkap }}</div>
                        <div class="text-[10px] text-gray-400">@ {{ $item->user->Username }}</div>
                    </td>
                    <td class="px-8 py-6 text-theme-purple font-bold">{{ $item->buku->Judul }}</td>
                    <td class="px-8 py-6 text-gray-500 text-sm">{{ $item->TanggalPeminjaman }}</td>
                    <td class="px-8 py-6 text-gray-500 text-sm">{{ $item->TanggalPengembalian ?? '-' }}</td>
                    <td class="px-8 py-6 text-center">
                        <span class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest 
                            {{ $item->StatusPeminjaman == 'Dipinjam' ? 'bg-theme-pink text-theme-navy' : 
                               ($item->StatusPeminjaman == 'Menunggu Pengembalian' ? 'bg-yellow-100 text-yellow-700' : 'bg-theme-navy text-theme-offwhite') }}">
                            {{ $item->StatusPeminjaman }}
                        </span>
                    </td>
                    <td class="px-8 py-6 text-center no-print">
                        @if($item->StatusPeminjaman == 'Menunggu Pengembalian')
                        <div class="flex justify-center space-x-2">
                            <form action="{{ route('peminjaman.konfirmasi', $item->PeminjamanID) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-green-600 transition-all">Konfirmasi</button>
                            </form>
                            <form action="{{ route('peminjaman.tolak', $item->PeminjamanID) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-red-600 transition-all">Tolak</button>
                            </form>
                        </div>
                        @else
                        <span class="text-gray-300 text-[10px] uppercase font-bold italic">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
@media print {
    .no-print, aside, header { display: none !important; }
    main { padding: 0 !important; width: 100% !important; }
    .card { border: none !important; box-shadow: none !important; }
}
</style>
@endsection
