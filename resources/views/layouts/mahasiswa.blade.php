<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: false }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard Mahasiswa') - STAI AL-FATIH</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">
        <script src="{{ asset('js/app.js') }}?v={{ config('app.asset_version', '1.0') }}" defer></script>

    <style>
        /* Islamic Geometric Patterns */
        .islamic-pattern {
            background-image:
                linear-gradient(45deg, #4A7C5920 25%, transparent 25%),
                linear-gradient(-45deg, #4A7C5920 25%, transparent 25%),
                linear-gradient(45deg, transparent 75%, #4A7C5920 75%),
                linear-gradient(-45deg, transparent 75%, #4A7C5920 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }

        .islamic-border {
            border: 2px solid #D4AF37;
            position: relative;
        }

        .islamic-border::before {
            content: '';
            position: absolute;
            top: -6px;
            left: -6px;
            right: -6px;
            bottom: -6px;
            border: 1px solid #D4AF3750;
            pointer-events: none;
        }

        .sidebar-item {
            transition: all 0.3s ease;
        }

        .sidebar-item:hover {
            background-color: #4A7C59;
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: linear-gradient(135deg, #D4AF37 0%, #F4E5C3 100%);
            color: #2d5a3d;
            font-weight: 600;
            border-left: 4px solid #4A7C59;
        }

        .card-islamic {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(74, 124, 89, 0.1);
            border: 1px solid #F4E5C3;
        }

        .gold-accent {
            background: linear-gradient(135deg, #D4AF37 0%, #F4E5C3 100%);
        }

        .green-accent {
            background: linear-gradient(135deg, #4A7C59 0%, #5a9c6f 100%);
        }

        /* Islamic Decorative Elements */
        .islamic-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, #D4AF37, transparent);
            margin: 1.5rem 0;
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside
            x-show="sidebarOpen || window.innerWidth >= 1024"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            @click.away="if (window.innerWidth < 1024) sidebarOpen = false"
            class="fixed lg:static inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-[#4A7C59] to-[#2d5a3d] text-white shadow-2xl overflow-y-auto"
        >
            <!-- Logo Section -->
            <div class="p-6 border-b border-white/20 islamic-pattern">
                <div class="flex items-center justify-center space-x-3">
                    <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo STAI AL-FATIH" class="h-16 w-16 object-contain">
                    <div class="text-left">
                        <h1 class="text-lg font-bold text-emerald-50 leading-tight">STAI AL-FATIH</h1>
                        <p class="text-xs text-white/80 mt-1">Portal Mahasiswa</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="p-4 space-y-2">
                <a href="{{ route('mahasiswa.dashboard') }}"
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('mahasiswa.profile') }}"
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mahasiswa.profile.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Profile</span>
                </a>

                <a href="{{ route('mahasiswa.jadwal.index') }}"
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mahasiswa.jadwal.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Jadwal Kuliah</span>
                </a>

                <a href="{{ route('mahasiswa.nilai.index') }}"
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mahasiswa.nilai.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Nilai</span>
                </a>

                <a href="{{ route('mahasiswa.khs.index') }}"
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mahasiswa.khs.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    <span>KHS</span>
                </a>

                <a href="{{ route('mahasiswa.pembayaran.index') }}"
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mahasiswa.pembayaran.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span>Pembayaran</span>
                </a>

                <a href="{{ route('mahasiswa.notifications.index') }}"
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mahasiswa.notifications.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span>Pengumuman</span>
                    @if(($unreadCount ?? 0) > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </a>

                <a href="{{ route('mahasiswa.kurikulum') }}"
                   class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mahasiswa.kurikulum.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Kurikulum</span>
                </a>

                <!-- Divider -->
                <div class="mt-4 pt-4 border-t border-white/20">
                    <a href="{{ route('mahasiswa.docs') }}"
                       class="sidebar-item flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('mahasiswa.docs') ? 'active' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Dokumentasi</span>
                    </a>
                </div>
            </nav>

            <!-- Islamic Decoration at Bottom -->
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/20">
                <div class="text-center text-xs text-white/60">
                    <p>بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Top Navbar -->
            <header class="bg-white shadow-md sticky top-0 z-40">
                <div class="flex items-center justify-between px-4 py-4">
                    <!-- Mobile Menu Button -->
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden text-gray-600 hover:text-gray-900 focus:outline-none"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Islamic Greeting -->
                    <div class="hidden md:flex items-center space-x-3">
                        <div class="text-[#4A7C59] text-lg font-semibold">السلام عليكم</div>
                        <div class="h-6 w-px bg-gray-300"></div>
                        <div class="text-gray-600 text-sm">Assalamualaikum Warahmatullahi Wabarakatuh</div>
                    </div>

                    <!-- Right Side: Notifications & Profile -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <div x-data="{ open: false }" class="relative">
                            <button
                                @click="open = !open"
                                class="relative p-2 text-gray-600 hover:text-[#4A7C59] hover:bg-gray-100 rounded-full transition"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                @if(($unreadCount ?? 0) > 0)
                                    <span class="notification-badge absolute top-1 right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-semibold">
                                        {{ ($unreadCount ?? 0) > 9 ? '9+' : ($unreadCount ?? 0) }}
                                    </span>
                                @endif
                            </button>

                            <!-- Notification Dropdown -->
                            <div
                                x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden"
                                style="display: none;"
                            >
                                @include('mahasiswa.notifications.dropdown')
                            </div>
                        </div>

                        <!-- User Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button
                                @click="open = !open"
                                class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition"
                            >
                                @if($mahasiswa && $mahasiswa->foto)
                                    <img src="{{ Storage::url($mahasiswa->foto) }}"
                                         alt="Profile"
                                         class="w-10 h-10 rounded-full ring-2 ring-[#D4AF37] object-cover">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ $mahasiswa->nama_lengkap ?? 'Mahasiswa' }}&background=4A7C59&color=fff"
                                         alt="Profile"
                                         class="w-10 h-10 rounded-full ring-2 ring-[#D4AF37]">
                                @endif
                                <div class="hidden md:block text-left">
                                    <div class="text-sm font-semibold text-gray-800">{{ $mahasiswa->nama_lengkap ?? 'Mahasiswa' }}</div>
                                    <div class="text-xs text-gray-500">{{ $mahasiswa->nim ?? 'NIM: -' }}</div>
                                </div>
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden"
                                style="display: none;"
                            >
                                <a href="{{ route('mahasiswa.profile') }}" class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>Profile Saya</span>
                                    </div>
                                </a>
                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            <span>Logout</span>
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-4">
                <div class="px-6 text-center text-sm text-gray-600">
                    <p>&copy; {{ date('Y') }} STAI AL-FATIH. All rights reserved. | بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
                </div>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
