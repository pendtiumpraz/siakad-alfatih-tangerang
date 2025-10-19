<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SPMB' }} - STAI AL-FATIH</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Islamic Theme Colors */
        :root {
            --islamic-green: #2D5F3F;
            --islamic-green-light: #4A7C59;
            --islamic-gold: #D4AF37;
            --islamic-cream: #F4E5C3;
        }

        .bg-islamic-green {
            background-color: var(--islamic-green);
        }

        .bg-islamic-green-light {
            background-color: var(--islamic-green-light);
        }

        .bg-islamic-gold {
            background-color: var(--islamic-gold);
        }

        .bg-islamic-cream {
            background-color: var(--islamic-cream);
        }

        .text-islamic-green {
            color: var(--islamic-green);
        }

        .text-islamic-gold {
            color: var(--islamic-gold);
        }

        .border-islamic-green {
            border-color: var(--islamic-green);
        }

        .border-islamic-gold {
            border-color: var(--islamic-gold);
        }

        /* Islamic Pattern Background */
        .islamic-pattern-bg {
            background: linear-gradient(135deg, #2D5F3F 0%, #1a3d28 100%);
            position: relative;
            overflow: hidden;
        }

        .islamic-pattern-bg::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(244, 229, 195, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Geometric Pattern */
        .geometric-pattern {
            position: absolute;
            width: 100px;
            height: 100px;
            opacity: 0.1;
        }

        /* Print Styles */
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .print-card {
                box-shadow: none !important;
                border: 1px solid #ccc;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen islamic-pattern-bg">
        <!-- Navigation -->
        <nav class="bg-islamic-green/90 backdrop-blur-sm shadow-lg border-b-4 border-islamic-gold no-print">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('public.spmb.index') }}" class="flex items-center">
                            <x-application-logo class="h-10 w-10 mr-3" />
                            <div>
                                <div class="text-white font-bold text-lg">STAI AL-FATIH</div>
                                <div class="text-islamic-gold text-xs">Sistem Pendaftaran Mahasiswa Baru</div>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex space-x-4">
                        <a href="{{ route('public.spmb.index') }}" class="text-white hover:text-islamic-gold transition px-3 py-2 rounded-md text-sm font-medium">
                            Beranda
                        </a>
                        <a href="{{ route('public.spmb.register') }}" class="text-white hover:text-islamic-gold transition px-3 py-2 rounded-md text-sm font-medium">
                            Daftar
                        </a>
                        <a href="{{ route('public.spmb.check') }}" class="text-white hover:text-islamic-gold transition px-3 py-2 rounded-md text-sm font-medium">
                            Cek Status
                        </a>
                        <a href="{{ route('login') }}" class="bg-islamic-gold text-islamic-green hover:bg-yellow-500 transition px-4 py-2 rounded-md text-sm font-medium">
                            Login
                        </a>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden" x-data="{ open: false }">
                        <button @click="open = !open" class="text-white hover:text-islamic-gold">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <!-- Mobile menu -->
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('public.spmb.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Beranda</a>
                            <a href="{{ route('public.spmb.register') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Daftar</a>
                            <a href="{{ route('public.spmb.check') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cek Status</a>
                            <a href="{{ route('login') }}" class="block px-4 py-2 text-sm text-islamic-green hover:bg-gray-100 font-medium">Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-8">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-islamic-green border-t-4 border-islamic-gold mt-12 no-print">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- About -->
                    <div>
                        <h3 class="text-islamic-gold font-semibold text-lg mb-3">STAI AL-FATIH</h3>
                        <p class="text-white/80 text-sm">
                            Sekolah Tinggi Agama Islam Al-Fatih berkomitmen menghasilkan lulusan yang berakhlak mulia dan berilmu.
                        </p>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-islamic-gold font-semibold text-lg mb-3">Quick Links</h3>
                        <ul class="space-y-2 text-sm">
                            <li><a href="{{ route('public.spmb.index') }}" class="text-white/80 hover:text-islamic-gold transition">Beranda</a></li>
                            <li><a href="{{ route('public.spmb.register') }}" class="text-white/80 hover:text-islamic-gold transition">Pendaftaran</a></li>
                            <li><a href="{{ route('public.spmb.check') }}" class="text-white/80 hover:text-islamic-gold transition">Cek Status</a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
                    <div>
                        <h3 class="text-islamic-gold font-semibold text-lg mb-3">Kontak</h3>
                        <ul class="space-y-2 text-sm text-white/80">
                            <li>Email: info@staialfatih.ac.id</li>
                            <li>Telp: (021) 1234-5678</li>
                            <li>WhatsApp: +62 812-3456-7890</li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-white/20 mt-8 pt-6 text-center">
                    <p class="text-white/60 text-sm">
                        &copy; {{ date('Y') }} STAI AL-FATIH. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
