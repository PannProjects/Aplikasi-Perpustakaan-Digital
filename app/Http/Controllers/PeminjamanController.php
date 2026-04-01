<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $buku = Buku::all();
        $peminjaman = Peminjaman::where('UserID', auth()->id())->with('buku')->get();

        return view('peminjaman.index', compact('buku', 'peminjaman'));
    }

    public function store(Request $request, Buku $buku)
    {
        Peminjaman::create([
            'UserID' => auth()->id(),
            'BukuID' => $buku->BukuID,
            'TanggalPeminjaman' => Carbon::now(),
            'TanggalPengembalian' => Carbon::now()->addDays(7),
            'StatusPeminjaman' => 'Dipinjam',
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Buku berhasil dipinjam.');
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'StatusPeminjaman' => 'Menunggu Pengembalian',
        ]);

        return redirect()->route('peminjaman.index')->with('success', 'Permintaan pengembalian dikirim. Menunggu konfirmasi petugas.');
    }

    public function konfirmasi(Request $request, Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'StatusPeminjaman' => 'Dikembalikan',
            'TanggalPengembalian' => Carbon::now(),
        ]);

        return back()->with('success', 'Pengembalian buku berhasil dikonfirmasi.');
    }

    public function tolak(Request $request, Peminjaman $peminjaman)
    {
        $peminjaman->update([
            'StatusPeminjaman' => 'Dipinjam',
        ]);

        return back()->with('success', 'Permintaan pengembalian ditolak. Status kembali menjadi Dipinjam.');
    }

    public function laporan(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('TanggalPeminjaman', [$request->start_date, $request->end_date]);
        }

        $peminjaman = $query->get();

        return view('laporan.index', compact('peminjaman'));
    }
}
