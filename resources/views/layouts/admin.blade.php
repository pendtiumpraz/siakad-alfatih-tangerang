<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'STAI AL-FATIH') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Islamic Geometric Pattern */
        .islamic-pattern {
            background-image:
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(212, 175, 55, 0.1) 10px, rgba(212, 175, 55, 0.1) 20px),
                repeating-linear-gradient(-45deg, transparent, transparent 10px, rgba(212, 175, 55, 0.1) 10px, rgba(212, 175, 55, 0.1) 20px);
        }

        /* Islamic Border */
        .islamic-border {
            position: relative;
            border: 2px solid #D4AF37;
        }

        .islamic-border::before {
            content: '';
            position: absolute;
            top: -4px;
            left: -4px;
            right: -4px;
            bottom: -4px;
            border: 1px solid rgba(212, 175, 55, 0.3);
            pointer-events: none;
        }

        /* Islamic Divider */
        .islamic-divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #D4AF37, transparent);
            position: relative;
        }

        .islamic-divider::before,
        .islamic-divider::after {
            content: '';
            position: absolute;
            width: 8px;
            height: 8px;
            background: #D4AF37;
            transform: rotate(45deg);
            top: -3px;
        }

        .islamic-divider::before {
            left: 50%;
            margin-left: -4px;
        }

        /* Sidebar Hover Effect */
        .sidebar-link {
            transition: all 0.3s ease;
        }

        .sidebar-link:hover {
            background-color: rgba(107, 158, 120, 0.2);
            border-left: 4px solid #D4AF37;
        }

        .sidebar-link.active {
            background-color: #D4AF37;
            color: #2D5F3F;
            border-left: 4px solid #2D5F3F;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #D4AF37;
            border-radius: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #b8941f;
        }

        /* Dropdown Animation */
        .dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .dropdown-menu.open {
            max-height: 500px;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false, masterDataOpen: false }">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-[#2D5F3F] to-[#4A7C59] transform transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 custom-scrollbar overflow-y-auto"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <!-- Logo Section -->
            <div class="flex items-center justify-center h-20 bg-[#2D5F3F] border-b-2 border-[#D4AF37] islamic-pattern">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-white">STAI AL-FATIH</h1>
                    <p class="text-xs text-[#F4E5C3]">Sistem Akademik</p>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="mt-6 px-3">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 text-white rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-th-large w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>

                <!-- Manajemen User -->
                <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 text-white rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5"></i>
                    <span class="ml-3">Manajemen User</span>
                </a>

                <!-- Role & Permission -->
                <a href="{{ route('admin.permissions.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 text-white rounded-lg {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt w-5"></i>
                    <span class="ml-3">Role & Permission</span>
                </a>

                <!-- Master Data (Expandable) -->
                <div class="mb-2">
                    <button @click="masterDataOpen = !masterDataOpen" class="sidebar-link flex items-center justify-between w-full px-4 py-3 text-white rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-database w-5"></i>
                            <span class="ml-3">Master Data</span>
                        </div>
                        <i class="fas fa-chevron-down transition-transform" :class="masterDataOpen ? 'rotate-180' : ''"></i>
                    </button>
                    <div class="dropdown-menu ml-4 mt-2" :class="masterDataOpen ? 'open' : ''">
                        <a href="{{ route('admin.program-studi.index') }}" class="sidebar-link flex items-center px-4 py-2 mb-1 text-white rounded-lg text-sm {{ request()->routeIs('admin.program-studi.*') ? 'active' : '' }}">
                            <i class="fas fa-graduation-cap w-4"></i>
                            <span class="ml-3">Program Studi</span>
                        </a>
                        <a href="{{ route('admin.kurikulum.index') }}" class="sidebar-link flex items-center px-4 py-2 mb-1 text-white rounded-lg text-sm {{ request()->routeIs('admin.kurikulum.*') ? 'active' : '' }}">
                            <i class="fas fa-book w-4"></i>
                            <span class="ml-3">Kurikulum</span>
                        </a>
                        <a href="{{ route('admin.mata-kuliah.index') }}" class="sidebar-link flex items-center px-4 py-2 mb-1 text-white rounded-lg text-sm {{ request()->routeIs('admin.mata-kuliah.*') ? 'active' : '' }}">
                            <i class="fas fa-book-open w-4"></i>
                            <span class="ml-3">Mata Kuliah</span>
                        </a>
                        <a href="{{ route('admin.ruangan.index') }}" class="sidebar-link flex items-center px-4 py-2 mb-1 text-white rounded-lg text-sm {{ request()->routeIs('admin.ruangan.*') ? 'active' : '' }}">
                            <i class="fas fa-door-open w-4"></i>
                            <span class="ml-3">Ruangan</span>
                        </a>
                        <a href="{{ route('admin.semester.index') }}" class="sidebar-link flex items-center px-4 py-2 mb-1 text-white rounded-lg text-sm {{ request()->routeIs('admin.semester.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt w-4"></i>
                            <span class="ml-3">Semester</span>
                        </a>
                    </div>
                </div>

                <!-- Pembayaran -->
                <a href="{{ route('admin.pembayaran.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 text-white rounded-lg {{ request()->routeIs('admin.pembayaran.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave w-5"></i>
                    <span class="ml-3">Pembayaran</span>
                </a>
            </nav>

            <!-- Islamic Decoration at Bottom -->
            <div class="absolute bottom-0 left-0 right-0 p-4 text-center">
                <div class="islamic-divider mb-4"></div>
                <p class="text-[#F4E5C3] text-xs italic">"Tuntutlah ilmu dari buaian hingga liang lahat"</p>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navbar -->
            <header class="bg-white shadow-md z-40">
                <div class="flex items-center justify-between h-16 px-6">
                    <!-- Mobile Menu Button -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Islamic Greeting -->
                    <div class="hidden lg:block">
                        <span class="text-[#2D5F3F] font-semibold text-lg">السلام عليكم</span>
                        <span class="text-gray-600 ml-2">- Selamat Datang</span>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center space-x-4">
                        <!-- Notification Bell -->
                        <x-admin.notification-bell :count="3" />

                        <!-- User Dropdown -->
                        <div x-data="{ userDropdownOpen: false }" class="relative">
                            <button @click="userDropdownOpen = !userDropdownOpen" class="flex items-center space-x-2 text-gray-700 hover:text-[#2D5F3F] transition">
                                <div class="w-10 h-10 bg-gradient-to-br from-[#2D5F3F] to-[#D4AF37] rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                                </div>
                                <div class="hidden md:block text-left">
                                    <p class="text-sm font-semibold">{{ auth()->user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->role ?? 'Super Admin' }}</p>
                                </div>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                x-show="userDropdownOpen"
                                @click.away="userDropdownOpen = false"
                                x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2"
                                style="display: none;"
                            >
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i> Pengaturan
                                </a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 custom-scrollbar">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <p class="text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                            <p class="text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
        style="display: none;"
    ></div>
</body>
</html>
