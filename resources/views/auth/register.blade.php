@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center -mt-16 py-12">
    <div class="card w-full max-w-lg p-8 shadow-2xl border-t-4 border-theme-purple">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-theme-navy italic tracking-tighter">PannPerpus</h1>
            <p class="text-gray-500 mt-2 italic font-medium">Bergabung dengan perpustakaan digital kami</p>
        </div>

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Username">Username</label>
                    <input type="text" name="Username" id="Username" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all" required>
                </div>
                <div>
                    <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Email">Email</label>
                    <input type="email" name="Email" id="Email" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="NamaLengkap">Nama Lengkap</label>
                <input type="text" name="NamaLengkap" id="NamaLengkap" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all" required>
            </div>
            <div class="mb-4">
                <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Password">Password</label>
                <input type="password" name="Password" id="Password" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all" required>
            </div>
            <div class="mb-4">
                <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Alamat">Alamat</label>
                <textarea name="Alamat" id="Alamat" rows="3" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all" required></textarea>
            </div>
            <div class="mb-8">
                <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Role">Daftar Sebagai</label>
                <select name="Role" id="Role" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all appearance-none" required>
                    <option value="peminjam">Peminjam</option>
                    <option value="administrator">Administrator</option>
                </select>
            </div>
            <button type="submit" class="w-full btn-primary py-4 text-lg font-bold uppercase tracking-widest shadow-lg hover:shadow-theme-purple/20 transition-all">Daftar Sekarang</button>
        </form>

        <div class="mt-8 text-center text-gray-500 text-sm italic font-medium">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-theme-purple font-bold hover:underline">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
