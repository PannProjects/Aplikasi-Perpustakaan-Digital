@extends('layouts.app')

@section('header', 'Pinjam Buku')

@section('content')
<div class="space-y-12">
    <!-- List Buku Tersedia -->
    <div>
        <h3 class="text-xl font-black text-theme-navy italic mb-6 border-l-8 border-theme-purple pl-4 uppercase tracking-tighter">Buku Tersedia</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($buku as $item)
            <div class="card hover:shadow-xl transition-all border-b-4 border-theme-navy p-6">
                <div class="w-12 h-12 bg-theme-offwhite rounded-full flex items-center justify-center mb-4 border border-theme-pink">
                    <span class="text-theme-purple font-black italic">{{ substr($item->Judul, 0, 1) }}</span>
                </div>
                <h4 class="text-lg font-bold text-theme-navy italic line-clamp-2 min-h-[3.5rem]">{{ $item->Judul }}</h4>
                <p class="text-gray-500 text-sm italic mb-1">Penulis: {{ $item->Penulis }}</p>
                <p class="text-gray-400 text-xs italic mb-6">{{ $item->Penerbit }} ({{ $item->TahunTerbit }})</p>
                <form action="{{ route('peminjaman.store', $item->BukuID) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full btn-primary text-xs font-black uppercase tracking-widest italic py-3">Pinjam Sekarang</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Riwayat Peminjaman Saya -->
    <div>
        <h3 class="text-xl font-black text-theme-navy italic mb-6 border-l-8 border-theme-pink pl-4 uppercase tracking-tighter">Riwayat Pinjaman Saya</h3>
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-theme-navy text-theme-offwhite uppercase text-xs font-black italic tracking-widest">
                            <th class="px-6 py-4">Buku</th>
                            <th class="px-6 py-4">Tgl Pinjam</th>
                            <th class="px-6 py-4">Harus Kembali</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 italic">
                        @foreach($peminjaman as $pinjam)
                        <tr>
                            <td class="px-6 py-4 font-bold text-theme-navy">{{ $pinjam->buku->Judul }}</td>
                            <td class="px-6 py-4 text-sm">{{ $pinjam->TanggalPeminjaman }}</td>
                            <td class="px-6 py-4 text-sm">{{ $pinjam->TanggalPengembalian }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter 
                                    {{ $pinjam->StatusPeminjaman == 'Dipinjam' ? 'bg-theme-pink text-theme-navy' : 
                                       ($pinjam->StatusPeminjaman == 'Menunggu Pengembalian' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }}">
                                    {{ $pinjam->StatusPeminjaman }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($pinjam->StatusPeminjaman == 'Dipinjam')
                                <form action="{{ route('peminjaman.update', $pinjam->PeminjamanID) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-theme-purple font-black text-xs hover:underline uppercase">Kembalikan</button>
                                </form>
                                @else
                                <span class="text-gray-300 text-xs uppercase font-bold italic">- Selesai -</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
