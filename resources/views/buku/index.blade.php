@extends('layouts.app')

@section('header', 'Pendataan Buku')

@section('content')
<div class="card">
    <div class="p-6 border-b flex justify-between items-center">
        <h3 class="text-lg font-bold italic text-theme-navy underline decoration-theme-pink decoration-4">Daftar Buku</h3>
        <a href="{{ route('buku.create') }}" class="btn-primary text-sm font-bold italic">Tambah Buku</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-theme-offwhite text-theme-navy uppercase text-xs font-black tracking-widest">
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Judul</th>
                    <th class="px-6 py-4">Penulis</th>
                    <th class="px-6 py-4">Penerbit</th>
                    <th class="px-6 py-4">Tahun</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($buku as $item)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium">{{ $loop->iteration }}</td>
                    <td class="px-6 py-4 font-bold text-theme-navy italic">{{ $item->Judul }}</td>
                    <td class="px-6 py-4 italic">{{ $item->Penulis }}</td>
                    <td class="px-6 py-4 italic">{{ $item->Penerbit }}</td>
                    <td class="px-6 py-4 italic">{{ $item->TahunTerbit }}</td>
                    <td class="px-6 py-4 flex justify-center space-x-2">
                        <a href="{{ route('buku.edit', $item->BukuID) }}" class="p-2 text-theme-purple hover:bg-theme-purple/10 rounded transition-all">
                            Ubah
                        </a>
                        <form action="{{ route('buku.destroy', $item->BukuID) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded transition-all">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
