@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center -mt-16">
    <div class="card w-full max-w-md p-8 shadow-2xl border-t-4 border-theme-purple">
        <div class="text-center mb-10">
            <h1 class="text-4xl font-black text-theme-navy italic tracking-tighter">PannPerpus</h1>
            <p class="text-gray-500 mt-2 italic font-medium">Selamat datang kembali, silakan login</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Username">Username</label>
                <input type="text" name="Username" id="Username" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all" placeholder="Masukkan username Anda" required>
            </div>
            <div class="mb-8">
                <label class="block text-theme-navy text-sm font-bold mb-2 uppercase tracking-wide" for="Password">Password</label>
                <input type="password" name="Password" id="Password" class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-theme-purple focus:border-transparent transition-all" placeholder="••••••••" required>
            </div>
            <button type="submit" class="w-full btn-primary py-4 text-lg font-bold uppercase tracking-widest shadow-lg hover:shadow-theme-purple/20 transition-all">Masuk</button>
        </form>

        <div class="mt-8 text-center text-gray-500 text-sm italic font-medium">
            Belum punya akun? <a href="{{ route('register') }}" class="text-theme-purple font-bold hover:underline">Daftar di sini</a>
        </div>
    </div>
</div>
@endsection
