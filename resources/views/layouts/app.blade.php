<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Digital Library') }}</title>
    <!-- Tailwind Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'theme-navy': '#15173d',
                        'theme-purple': '#982598',
                        'theme-pink': '#e491c9',
                        'theme-offwhite': '#f1e9e9',
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer base {
            body { @apply bg-theme-offwhite text-theme-navy font-sans; }
        }
        .sidebar-link { @apply flex items-center px-6 py-3 text-gray-400 hover:bg-theme-navy hover:text-theme-offwhite transition-colors duration-200; }
        .sidebar-link.active { @apply bg-theme-navy text-theme-offwhite border-r-4 border-theme-purple; }
        .btn-primary { @apply bg-theme-purple text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-all; }
        .btn-secondary { @apply bg-theme-pink text-theme-navy px-4 py-2 rounded-lg hover:bg-opacity-90 transition-all; }
        .card { @apply bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden; }
    </style>
</head>
<body class="bg-theme-offwhite">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @auth
        <aside class="w-64 bg-white shadow-xl flex-shrink-0 hidden md:flex flex-col">
            <div class="p-6 bg-theme-navy">
                <h1 class="text-2xl font-bold text-theme-offwhite italic">PannPerpus</h1>
            </div>
            <nav class="mt-6 flex-1">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="ml-3 italic font-semibold">Dashboard</span>
                </a>
                
                @if(in_array(auth()->user()->Role, ['administrator', 'petugas']))
                <a href="{{ route('buku.index') }}" class="sidebar-link {{ request()->routeIs('buku.*') ? 'active' : '' }}">
                    <span class="ml-3 italic font-semibold">Pendataan Buku</span>
                </a>
                @endif

                @if(auth()->user()->Role == 'peminjam')
                <a href="{{ route('peminjaman.index') }}" class="sidebar-link {{ request()->routeIs('peminjaman.index') ? 'active' : '' }}">
                    <span class="ml-3 italic font-semibold">Pinjam Buku</span>
                </a>
                @endif

                @if(in_array(auth()->user()->Role, ['administrator', 'petugas']))
                <a href="{{ route('laporan.index') }}" class="sidebar-link {{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                    <span class="ml-3 italic font-semibold">Laporan</span>
                </a>
                @endif
            </nav>
            <div class="p-4 border-t">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left sidebar-link text-red-500 hover:text-red-700">
                        <span class="ml-3 italic font-semibold">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>
        @endauth

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            @auth
            <header class="bg-white shadow-sm px-8 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-theme-navy uppercase tracking-wider">
                    @yield('header', 'Ringkasan')
                </h2>
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-500 italic">{{ auth()->user()->NamaLengkap }} ({{ ucfirst(auth()->user()->Role === 'peminjam' ? 'Peminjam' : (auth()->user()->Role === 'petugas' ? 'Petugas' : 'Administrator')) }})</span>
                    <div class="w-10 h-10 rounded-full bg-theme-pink flex items-center justify-center text-theme-navy font-bold">
                        {{ substr(auth()->user()->Username, 0, 1) }}
                    </div>
                </div>
            </header>
            @endauth

            <div class="p-8">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
