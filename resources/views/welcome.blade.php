<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="D8MnsoKB8CGE--qTmPL2wOC87jUVwS7O6lZl9VaZwM8" />
    <meta name="description" content="SIAKAD STAI AL FATIH - Sistem Informasi Akademik untuk mengelola data mahasiswa, dosen, jadwal kuliah, KRS, KHS, dan pembayaran SPP secara online.">

    <title>SIAKAD STAI AL FATIH - Sistem Informasi Akademik</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">

    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-fadeInUp { animation: fadeInUp 0.8s ease-out forwards; }
        .animate-fadeInLeft { animation: fadeInLeft 0.8s ease-out forwards; }
        .animate-fadeInRight { animation: fadeInRight 0.8s ease-out forwards; }
        .animate-pulse-slow { animation: pulse 3s ease-in-out infinite; }

        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .glass-dark {
            background: rgba(45, 95, 63, 0.9);
            backdrop-filter: blur(20px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #2D5F3F 0%, #4A7C59 50%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .shimmer-btn {
            background: linear-gradient(90deg, #D4AF37 0%, #eab308 50%, #D4AF37 100%);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        .hover-lift {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .hover-lift:hover {
            transform: translateY(-12px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .feature-card {
            position: relative;
            overflow: hidden;
            transition: all 0.4s ease;
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.1), transparent);
            transition: left 0.5s ease;
        }
        .feature-card:hover::before {
            left: 100%;
        }
        .feature-card:hover {
            border-color: #D4AF37;
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(45, 95, 63, 0.15);
        }

        .prodi-card {
            position: relative;
            overflow: hidden;
        }
        .prodi-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2D5F3F, #D4AF37);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }
        .prodi-card:hover::after {
            transform: scaleX(1);
        }

        .scroll-indicator {
            animation: float 2s ease-in-out infinite;
        }

        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23D4AF37' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="font-sans antialiased bg-white overflow-x-hidden">

    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" style="background: linear-gradient(135deg, #2D5F3F 0%, #4A7C59 100%);">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-5">
            <div class="flex justify-between items-center">
                <a href="/" class="flex items-center gap-4 group">
                    <div class="relative">
                        <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo" class="h-14 w-14 object-contain transition-transform duration-300 group-hover:scale-110">
                        <div class="absolute inset-0 rounded-full bg-white/20 scale-0 group-hover:scale-150 transition-transform duration-500 opacity-0 group-hover:opacity-100"></div>
                    </div>
                    <div class="hidden sm:block">
                        <h1 class="text-xl font-bold text-white leading-tight">SIAKAD STAI AL FATIH</h1>
                        <p class="text-sm text-white/70">Sistem Informasi Akademik</p>
                    </div>
                </a>
                <nav class="flex items-center gap-3">
                    <a href="#tentang" class="hidden lg:inline-block text-white/80 hover:text-white text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-white/10 transition-all duration-300">Tentang</a>
                    <a href="#prodi" class="hidden lg:inline-block text-white/80 hover:text-white text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-white/10 transition-all duration-300">Program Studi</a>
                    <a href="#fitur" class="hidden lg:inline-block text-white/80 hover:text-white text-sm font-medium px-4 py-2.5 rounded-xl hover:bg-white/10 transition-all duration-300">Fitur</a>
                    <a href="{{ route('login') }}" class="shimmer-btn text-sm font-bold px-6 py-3 rounded-xl text-gray-900 transition-all duration-300 hover:shadow-lg hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center relative overflow-hidden bg-pattern" style="background: linear-gradient(135deg, #1a3d2a 0%, #2D5F3F 30%, #4A7C59 70%, #2D5F3F 100%);">
        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-20 h-20 rounded-full bg-white/5 animate-float"></div>
        <div class="absolute top-40 right-20 w-32 h-32 rounded-full bg-yellow-500/10 animate-float delay-200"></div>
        <div class="absolute bottom-40 left-1/4 w-24 h-24 rounded-full bg-white/5 animate-float delay-400"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-32 w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="text-center lg:text-left">
                    <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold mb-8 animate-fadeInUp glass-dark text-white border border-white/20">
                        <i class="fas fa-mosque"></i>Sekolah Tinggi Agama Islam
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white mb-8 leading-tight animate-fadeInUp delay-100">
                        Wujudkan<br>
                        <span class="relative">
                            <span style="color: #D4AF37;">Masa Depan Gemilang</span>
                            <svg class="absolute -bottom-2 left-0 w-full" height="8" viewBox="0 0 200 8" fill="none">
                                <path d="M0 4C50 0 150 8 200 4" stroke="#D4AF37" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </span>
                    </h1>
                    <p class="text-lg md:text-xl text-white/80 mb-12 leading-relaxed max-w-xl animate-fadeInUp delay-200">
                        SIAKAD STAI AL FATIH hadir sebagai solusi digital untuk mengelola aktivitas akademik dengan lebih efisien, transparan, dan modern.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-5 justify-center lg:justify-start animate-fadeInUp delay-300">
                        <a href="{{ route('login') }}" class="group inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl font-bold text-lg transition-all duration-300 hover:shadow-2xl hover:scale-105 shimmer-btn text-gray-900">
                            <i class="fas fa-sign-in-alt group-hover:rotate-12 transition-transform"></i>Masuk ke SIAKAD
                        </a>
                        <a href="{{ route('public.spmb.index') }}" class="group inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl font-bold text-lg text-white border-2 border-white/30 bg-white/5 hover:bg-white/15 hover:border-white/60 transition-all duration-300 backdrop-blur-sm">
                            <i class="fas fa-user-plus group-hover:scale-110 transition-transform"></i>Daftar Sekarang
                        </a>
                    </div>
                </div>
                <div class="hidden lg:flex justify-center animate-fadeInRight">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-r from-yellow-500/20 to-green-500/20 rounded-3xl blur-2xl animate-pulse-slow"></div>
                        <img src="https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=600&q=80" alt="Al-Quran dan Kitab" class="relative w-full max-w-lg rounded-3xl shadow-2xl">
                    </div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 scroll-indicator">
            <a href="#tentang" class="flex flex-col items-center text-white/60 hover:text-white transition-colors">
                <span class="text-xs mb-2">Scroll</span>
                <i class="fas fa-chevron-down"></i>
            </a>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 relative overflow-hidden" style="background: linear-gradient(135deg, #1a3d2a 0%, #234a30 100%);">
        <div class="max-w-6xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @php
                $stats = [
                    ['value' => $programStudis->count(), 'label' => 'Program Studi', 'icon' => 'fa-graduation-cap'],
                    ['value' => '8', 'label' => 'Semester', 'icon' => 'fa-book'],
                    ['value' => '24/7', 'label' => 'Akses Online', 'icon' => 'fa-clock'],
                    ['value' => '100%', 'label' => 'Data Aman', 'icon' => 'fa-shield-alt'],
                ];
                @endphp
                @foreach($stats as $index => $stat)
                <div class="text-center group animate-fadeInUp delay-{{ ($index + 1) * 100 }}">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl flex items-center justify-center text-2xl transition-all duration-300 group-hover:scale-110 group-hover:rotate-6" style="background: rgba(212, 175, 55, 0.2); color: #D4AF37;">
                        <i class="fas {{ $stat['icon'] }}"></i>
                    </div>
                    <h3 class="text-4xl md:text-5xl font-bold mb-2" style="color: #D4AF37;">{{ $stat['value'] }}</h3>
                    <p class="text-white/70 text-base">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-28 bg-white relative bg-pattern" id="tentang">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
                <div class="order-2 lg:order-1 animate-fadeInLeft">
                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-br from-green-500/20 to-yellow-500/20 rounded-3xl blur-2xl"></div>
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&q=80" alt="Masjid dan Arsitektur Islam" class="relative w-full rounded-3xl shadow-2xl">
                    </div>
                </div>
                <div class="order-1 lg:order-2 animate-fadeInRight">
                    <span class="inline-block text-sm font-bold uppercase tracking-widest mb-4 px-4 py-2 rounded-full" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37;">Tentang Kami</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold mb-8 leading-tight" style="color: #2D5F3F;">
                        Mengapa Memilih<br>STAI AL FATIH?
                    </h2>
                    <p class="text-gray-600 text-lg leading-relaxed mb-8">
                        SIAKAD (Sistem Informasi Akademik) adalah platform digital modern yang mengintegrasikan seluruh proses akademik. Dirancang untuk memudahkan mahasiswa dan dosen dalam menjalankan aktivitas akademik.
                    </p>
                    <div class="space-y-5">
                        @php
                        $benefits = [
                            ['icon' => 'fa-globe', 'title' => 'Akses 24/7', 'desc' => 'Kapan saja, di mana saja'],
                            ['icon' => 'fa-lock', 'title' => 'Keamanan Terjamin', 'desc' => 'Data terenkripsi modern'],
                            ['icon' => 'fa-bolt', 'title' => 'Proses Cepat', 'desc' => 'Efisien dan terintegrasi'],
                            ['icon' => 'fa-mobile-alt', 'title' => 'Mobile Friendly', 'desc' => 'Responsif di semua perangkat'],
                        ];
                        @endphp
                        @foreach($benefits as $benefit)
                        <div class="flex items-center gap-5 p-4 rounded-2xl transition-all duration-300 hover:bg-gray-50 hover:shadow-lg group cursor-pointer">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-xl transition-all duration-300 group-hover:scale-110" style="background: linear-gradient(135deg, #2D5F3F, #4A7C59); color: #D4AF37;">
                                <i class="fas {{ $benefit['icon'] }}"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">{{ $benefit['title'] }}</h4>
                                <p class="text-gray-500">{{ $benefit['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Studi Section -->
    <section class="py-28 bg-gray-50" id="prodi">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center mb-16 animate-fadeInUp">
                <span class="inline-block text-sm font-bold uppercase tracking-widest mb-4 px-4 py-2 rounded-full" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37;">Akademik</span>
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6" style="color: #2D5F3F;">Program Studi Kami</h2>
                <p class="text-gray-600 text-xl max-w-2xl mx-auto">Pilihan program studi berkualitas untuk masa depan cerah</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($programStudis as $index => $prodi)
                <div class="prodi-card glass-card p-8 rounded-3xl shadow-xl hover-lift animate-fadeInUp delay-{{ (($index % 3) + 1) * 100 }}">
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-3xl mb-6 transition-all duration-300" style="background: linear-gradient(135deg, #2D5F3F, #4A7C59); color: #FFFFFF;">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $prodi->nama_prodi }}</h3>
                    <p class="text-lg font-semibold" style="color: #D4AF37;">{{ $prodi->jenjang }} - {{ $prodi->kode_prodi }}</p>
                </div>
                @empty
                <div class="col-span-full glass-card p-12 rounded-3xl shadow-xl text-center">
                    <div class="w-24 h-24 mx-auto rounded-full flex items-center justify-center text-4xl mb-6" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Segera Hadir</h3>
                    <p class="text-gray-600 text-lg">Informasi program studi akan segera tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="rounded-3xl overflow-hidden h-80 shadow-xl hover-lift">
                    <img src="https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=500&q=80" alt="Al-Quran" class="w-full h-full object-cover">
                </div>
                <div class="rounded-3xl overflow-hidden h-80 shadow-xl hover-lift delay-100">
                    <img src="https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=500&q=80" alt="Buku dan Pena" class="w-full h-full object-cover">
                </div>
                <div class="rounded-3xl overflow-hidden h-80 shadow-xl hover-lift delay-200">
                    <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=500&q=80" alt="Laptop dan Teknologi" class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-28 bg-gray-50 bg-pattern" id="fitur">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="text-center mb-16 animate-fadeInUp">
                <span class="inline-block text-sm font-bold uppercase tracking-widest mb-4 px-4 py-2 rounded-full" style="background: rgba(212, 175, 55, 0.15); color: #D4AF37;">Layanan</span>
                <h2 class="text-4xl md:text-5xl font-extrabold mb-6" style="color: #2D5F3F;">Fitur Lengkap SIAKAD</h2>
                <p class="text-gray-600 text-xl max-w-2xl mx-auto">Semua yang Anda butuhkan dalam satu platform</p>
            </div>

            <!-- Mahasiswa Features -->
            <div class="mb-20">
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl" style="background: linear-gradient(135deg, #2D5F3F, #4A7C59); color: #D4AF37;">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="text-3xl font-bold" style="color: #2D5F3F;">Untuk Mahasiswa</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                    $mahasiswaFeatures = [
                        ['icon' => 'fa-book-open', 'bg' => 'bg-green-100', 'color' => 'text-green-700', 'title' => 'Pengisian KRS', 'desc' => 'Isi KRS secara online dengan mudah'],
                        ['icon' => 'fa-chart-line', 'bg' => 'bg-amber-100', 'color' => 'text-amber-700', 'title' => 'KHS & Transkrip', 'desc' => 'Akses nilai secara real-time'],
                        ['icon' => 'fa-calendar-alt', 'bg' => 'bg-blue-100', 'color' => 'text-blue-700', 'title' => 'Jadwal Kuliah', 'desc' => 'Lihat jadwal dan ruangan'],
                        ['icon' => 'fa-wallet', 'bg' => 'bg-green-100', 'color' => 'text-green-700', 'title' => 'Pembayaran', 'desc' => 'Kelola SPP dengan riwayat lengkap'],
                        ['icon' => 'fa-user-circle', 'bg' => 'bg-amber-100', 'color' => 'text-amber-700', 'title' => 'Profil', 'desc' => 'Kelola data pribadi'],
                        ['icon' => 'fa-bell', 'bg' => 'bg-blue-100', 'color' => 'text-blue-700', 'title' => 'Notifikasi', 'desc' => 'Terima pengumuman penting'],
                    ];
                    @endphp
                    @foreach($mahasiswaFeatures as $index => $feature)
                    <div class="feature-card glass-card p-7 rounded-2xl border border-gray-100 animate-fadeInUp delay-{{ (($index % 3) + 1) * 100 }}">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mb-5 {{ $feature['bg'] }} {{ $feature['color'] }}">
                            <i class="fas {{ $feature['icon'] }}"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h4>
                        <p class="text-gray-600 text-base">{{ $feature['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Dosen Features -->
            <div>
                <div class="flex items-center gap-5 mb-10">
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl" style="background: linear-gradient(135deg, #2D5F3F, #4A7C59); color: #D4AF37;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="text-3xl font-bold" style="color: #2D5F3F;">Untuk Dosen</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                    $dosenFeatures = [
                        ['icon' => 'fa-edit', 'bg' => 'bg-green-100', 'color' => 'text-green-700', 'title' => 'Input Nilai', 'desc' => 'Input nilai dengan mudah'],
                        ['icon' => 'fa-calendar-check', 'bg' => 'bg-amber-100', 'color' => 'text-amber-700', 'title' => 'Jadwal Mengajar', 'desc' => 'Akses jadwal dan ruangan'],
                        ['icon' => 'fa-users', 'bg' => 'bg-blue-100', 'color' => 'text-blue-700', 'title' => 'Daftar Mahasiswa', 'desc' => 'Lihat mahasiswa per matkul'],
                        ['icon' => 'fa-user-tie', 'bg' => 'bg-green-100', 'color' => 'text-green-700', 'title' => 'Perwalian', 'desc' => 'Kelola mahasiswa bimbingan'],
                        ['icon' => 'fa-file-pdf', 'bg' => 'bg-amber-100', 'color' => 'text-amber-700', 'title' => 'Cetak Laporan', 'desc' => 'Export laporan akademik'],
                        ['icon' => 'fa-id-badge', 'bg' => 'bg-blue-100', 'color' => 'text-blue-700', 'title' => 'Profil Dosen', 'desc' => 'Kelola data dan NIDN'],
                    ];
                    @endphp
                    @foreach($dosenFeatures as $index => $feature)
                    <div class="feature-card glass-card p-7 rounded-2xl border border-gray-100 animate-fadeInUp delay-{{ (($index % 3) + 1) * 100 }}">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-2xl mb-5 {{ $feature['bg'] }} {{ $feature['color'] }}">
                            <i class="fas {{ $feature['icon'] }}"></i>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900 mb-2">{{ $feature['title'] }}</h4>
                        <p class="text-gray-600 text-base">{{ $feature['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-28 relative overflow-hidden" style="background: linear-gradient(135deg, #1a3d2a 0%, #2D5F3F 50%, #4A7C59 100%);">
        <div class="absolute inset-0 bg-pattern opacity-30"></div>
        <div class="absolute top-10 right-10 w-40 h-40 rounded-full bg-yellow-500/10 animate-float"></div>
        <div class="absolute bottom-10 left-10 w-32 h-32 rounded-full bg-white/5 animate-float delay-300"></div>

        <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-8 animate-fadeInUp">Siap Memulai?</h2>
            <p class="text-xl text-white/80 mb-12 max-w-2xl mx-auto leading-relaxed animate-fadeInUp delay-100">
                Bergabunglah dengan STAI AL FATIH dan wujudkan cita-cita akademik Anda bersama kami.
            </p>
            <div class="flex flex-col sm:flex-row gap-5 justify-center animate-fadeInUp delay-200">
                <a href="{{ route('login') }}" class="group inline-flex items-center justify-center gap-3 px-10 py-5 rounded-2xl font-bold text-xl transition-all duration-300 hover:shadow-2xl hover:scale-105 shimmer-btn text-gray-900">
                    <i class="fas fa-sign-in-alt group-hover:rotate-12 transition-transform"></i>Masuk Sekarang
                </a>
                <a href="{{ route('public.spmb.index') }}" class="group inline-flex items-center justify-center gap-3 px-10 py-5 rounded-2xl font-bold text-xl text-white border-2 border-white/30 bg-white/10 hover:bg-white/20 hover:border-white/50 transition-all duration-300 backdrop-blur-sm">
                    <i class="fas fa-user-plus group-hover:scale-110 transition-transform"></i>Daftar Mahasiswa Baru
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo" class="h-12 w-12">
                        <span class="text-xl font-bold" style="color: #D4AF37;">SIAKAD</span>
                    </div>
                    <p class="text-gray-400 leading-relaxed">Sistem Informasi Akademik untuk pengelolaan data akademik yang modern dan terintegrasi.</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white mb-6">Kontak</h4>
                    <div class="space-y-4">
                        <a href="mailto:{{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}" class="text-gray-400 hover:text-white flex items-center gap-3 transition-colors">
                            <i class="fas fa-envelope" style="color: #D4AF37;"></i>{{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}
                        </a>
                        <a href="tel:{{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}" class="text-gray-400 hover:text-white flex items-center gap-3 transition-colors">
                            <i class="fas fa-phone" style="color: #D4AF37;"></i>{{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}
                        </a>
                        <a href="https://wa.me/{{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}" class="text-gray-400 hover:text-white flex items-center gap-3 transition-colors">
                            <i class="fab fa-whatsapp text-green-500"></i>WhatsApp
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white mb-6">Tautan</h4>
                    <div class="space-y-3">
                        <a href="{{ route('login') }}" class="text-gray-400 hover:text-white block transition-colors">Masuk</a>
                        <a href="{{ route('public.spmb.index') }}" class="text-gray-400 hover:text-white block transition-colors">Pendaftaran</a>
                        <a href="#prodi" class="text-gray-400 hover:text-white block transition-colors">Program Studi</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-bold text-white mb-6">Legal</h4>
                    <div class="space-y-3">
                        <a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-white block transition-colors">Kebijakan Privasi</a>
                        <a href="{{ route('terms') }}" class="text-gray-400 hover:text-white block transition-colors">Syarat & Ketentuan</a>
                    </div>
                </div>
            </div>
            <div class="pt-8 border-t border-gray-800 text-center">
                <p class="text-gray-500">&copy; {{ date('Y') }} STAI AL FATIH. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
