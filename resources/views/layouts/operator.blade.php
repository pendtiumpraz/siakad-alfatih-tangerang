<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIAKAD') }} - Operator</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-green-800 to-green-900 shadow-xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
        >
            <!-- Logo & Title -->
            <div class="flex items-center justify-center h-20 px-6 bg-green-900 border-b border-green-700">
                <div class="text-center">
                    <h1 class="text-xl font-bold text-white">STAI AL-FATIH</h1>
                    <p class="text-xs text-green-200">Operator Keuangan</p>
                </div>
            </div>

            <!-- Islamic Decorative Pattern -->
            <div class="px-6 py-3 border-b border-green-700">
                <div class="flex justify-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="px-4 py-6 space-y-2 overflow-y-auto h-[calc(100vh-140px)]">
                <a href="{{ route('operator.dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('operator.dashboard') ? 'bg-green-700 border-l-4 border-yellow-400' : 'hover:bg-green-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('operator.pembayaran.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('operator.pembayaran.*') ? 'bg-green-700 border-l-4 border-yellow-400' : 'hover:bg-green-700' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">Pembayaran</span>
                </a>

                <!-- Master Data Section -->
                <div class="pt-4 mt-4 border-t border-green-700">
                    <p class="px-4 mb-2 text-xs font-semibold text-green-300 uppercase">Master Data</p>

                    <a href="{{ route('operator.mahasiswa.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('operator.mahasiswa.*') ? 'bg-green-700 border-l-4 border-yellow-400' : 'hover:bg-green-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <span class="font-medium">Mahasiswa</span>
                    </a>

                    <a href="{{ route('operator.dosen.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg transition-colors duration-200 {{ request()->routeIs('operator.dosen.*') ? 'bg-green-700 border-l-4 border-yellow-400' : 'hover:bg-green-700' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="font-medium">Dosen</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b-2 border-green-100">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-600 lg:hidden">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>

                    <div class="flex items-center space-x-4 ml-auto">
                        <!-- Notifications -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="relative p-2 text-gray-600 hover:text-green-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50">
                                <div class="px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 border-b border-green-500">
                                    <h3 class="text-sm font-semibold text-white">Notifikasi</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <x-notification-item
                                        title="Pembayaran Baru"
                                        message="Pembayaran SPP dari Ahmad Nur (2301001) menunggu verifikasi"
                                        time="5 menit yang lalu"
                                        :read="false"
                                    />
                                    <x-notification-item
                                        title="Pembayaran Terverifikasi"
                                        message="Pembayaran UKT Semester 3 telah diverifikasi"
                                        time="1 jam yang lalu"
                                        :read="true"
                                    />
                                </div>
                                <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 text-center">
                                    <a href="#" class="text-sm text-green-600 hover:text-green-800 font-medium">Lihat Semua</a>
                                </div>
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-700">{{ Auth::user()->name ?? 'Operator' }}</p>
                                    <p class="text-xs text-gray-500">Operator Keuangan</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold">
                                    {{ substr(Auth::user()->name ?? 'O', 0, 1) }}
                                </div>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-green-50 transition-colors">
                                    Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors border-t border-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
