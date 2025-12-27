<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="D8MnsoKB8CGE--qTmPL2wOC87jUVwS7O6lZl9VaZwM8" />
    <meta name="description" content="SIAKAD STAI AL FATIH - Sistem Informasi Akademik Terpadu.">

    <title>SIAKAD STAI AL FATIH</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">

    <style>
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, .serif { font-family: 'Playfair Display', serif; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #1B4D3E; border-radius: 4px; }
        
        .hero-bg {
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1519817650390-64a93db51149?q=80&w=2000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .gold-underline {
            position: relative;
            display: inline-block;
        }
        .gold-underline::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -5px;
            width: 100%;
            height: 3px;
            background-color: #D4AF37;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="antialiased text-gray-800 bg-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-md shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <img class="h-10 w-auto" src="{{ asset('images/logo-alfatih.png') }}" alt="STAI Al-Fatih">
                    <div class="hidden md:block">
                        <div class="text-lg font-bold text-gray-900 leading-none tracking-tight">SIAKAD</div>
                        <div class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">STAI Al-Fatih Tangerang</div>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-sm font-medium text-[#1B4D3E] hover:text-green-700">Beranda</a>
                    <a href="#tentang" class="text-sm font-medium text-gray-600 hover:text-[#1B4D3E] transition-colors">Tentang</a>
                    <a href="#prodi" class="text-sm font-medium text-gray-600 hover:text-[#1B4D3E] transition-colors">Program Studi</a>
                    <a href="#fitur" class="text-sm font-medium text-gray-600 hover:text-[#1B4D3E] transition-colors">Fasilitas</a>
                    
                    <div class="flex items-center gap-3 ml-4">
                        <a href="{{ route('public.spmb.index') }}" class="px-5 py-2.5 text-sm font-bold text-[#1B4D3E] border border-[#1B4D3E] rounded-full hover:bg-green-50 transition-all">
                            SPMB Online
                        </a>
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-[#1B4D3E] rounded-full hover:bg-[#163C31] shadow-lg hover:shadow-xl transition-all">
                            Login Portal
                        </a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button type="button" class="text-gray-500 hover:text-gray-900 focus:outline-none">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg h-screen min-h-[600px] flex items-center justify-center text-center px-4 relative">
        <div class="max-w-5xl mx-auto space-y-8 relative z-10 pt-16">
            <div class="inline-block animate-fade-in-up">
                <span class="px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white/90 text-sm font-medium tracking-wider uppercase">
                    Platform Akademik Digital
                </span>
            </div>
            
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white tracking-tight leading-tight">
                Membangun Generasi <br>
                <span class="text-[#D4AF37] serif italic">Unggul & Berkarakter</span>
            </h1>
            
            <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed">
                Sistem Informasi Akademik Terintegrasi STAI Al-Fatih Tangerang. Memudahkan akses layanan pendidikan bagi mahasiswa, dosen, dan staf.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-8">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-[#D4AF37] hover:bg-[#b89628] text-white font-bold rounded-lg shadow-lg hover:shadow-[#D4AF37]/50 transition-all transform hover:-translate-y-1">
                    Akses SIAKAD
                </a>
                <a href="{{ route('public.spmb.index') }}" class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 text-white font-bold rounded-lg transition-all">
                    Daftar Mahasiswa Baru
                </a>
            </div>
        </div>

        <!-- Scroll Down Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#stats" class="text-white/50 hover:text-white transition-colors">
                <i class="fas fa-chevron-down text-2xl"></i>
            </a>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="bg-[#1B4D3E] py-12 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center divide-x divide-white/10">
                <div class="p-4">
                    <div class="text-4xl font-bold text-[#D4AF37] serif mb-1">{{ $programStudis->count() }}</div>
                    <div class="text-xs text-white/70 uppercase tracking-widest">Program Studi</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-[#D4AF37] serif mb-1">A</div>
                    <div class="text-xs text-white/70 uppercase tracking-widest">Akreditasi Institusi</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-[#D4AF37] serif mb-1">24/7</div>
                    <div class="text-xs text-white/70 uppercase tracking-widest">Akses Online</div>
                </div>
                <div class="p-4">
                    <div class="text-4xl font-bold text-[#D4AF37] serif mb-1">100%</div>
                    <div class="text-xs text-white/70 uppercase tracking-widest">Digitalized</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="relative">
                    <div class="absolute inset-0 bg-[#D4AF37] transform translate-x-4 translate-y-4 rounded-xl"></div>
                    <img src="https://images.unsplash.com/photo-1596525737238-62dbca856525?q=80&w=1000&auto=format&fit=crop" alt="Kampus Islami" class="relative rounded-xl shadow-2xl w-full h-[500px] object-cover">
                </div>
                <div class="space-y-6">
                    <h2 class="text-4xl font-bold text-gray-900 serif leading-tight">
                        Transformasi Pendidikan <br>
                        <span class="gold-underline">Berbasis Teknologi</span>
                    </h2>
                    <p class="text-lg text-gray-600 leading-relaxed">
                        SIAKAD STAI Al-Fatih hadir sebagai solusi modern untuk mendukung proses pembelajaran yang efektif dan efisien. Kami berkomitmen mencetak sarjana muslim yang tidak hanya unggul secara akademis, tetapi juga berakhlak mulia dan melek teknologi.
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-[#1B4D3E]">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="font-medium text-gray-700">Kurikulum Terintegrasi</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-[#1B4D3E]">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="font-medium text-gray-700">Manajemen Transparan</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-[#1B4D3E]">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="font-medium text-gray-700">Monitoring Real-time</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-[#1B4D3E]">
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="font-medium text-gray-700">Layanan Prima</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Studi -->
    <section id="prodi" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <span class="text-[#D4AF37] font-bold uppercase tracking-widest text-sm">Akademik</span>
                <h2 class="text-4xl font-bold text-gray-900 mt-2 mb-4 serif">Program Studi Pilihan</h2>
                <div class="w-24 h-1 bg-[#1B4D3E] mx-auto rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($programStudis as $prodi)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 group">
                    <div class="flex items-start justify-between mb-6">
                        <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-[#1B4D3E] text-2xl group-hover:bg-[#1B4D3E] group-hover:text-white transition-colors">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <span class="px-3 py-1 bg-[#1B4D3E]/10 text-[#1B4D3E] text-xs font-bold rounded-full">
                            {{ $prodi->jenjang }}
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#1B4D3E] transition-colors">
                        {{ $prodi->nama_prodi }}
                    </h3>
                    <p class="text-gray-500 text-sm mb-6 line-clamp-2">
                        Program studi unggulan dengan kurikulum berbasis kompetensi dan nilai-nilai keislaman.
                    </p>
                    <div class="pt-6 border-t border-gray-50 flex items-center justify-between">
                        <span class="text-sm font-mono text-gray-400">{{ $prodi->kode_prodi }}</span>
                        <a href="#" class="text-[#1B4D3E] text-sm font-semibold flex items-center gap-2 hover:gap-3 transition-all">
                            Detail <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                        <i class="fas fa-folder-open text-2xl"></i>
                    </div>
                    <p class="text-gray-500">Data program studi belum tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Features / Facilities -->
    <section id="fitur" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4 serif">Fasilitas Digital</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Mendukung aktivitas akademik dengan fitur-fitur modern dan mudah digunakan.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="feature-card p-8 bg-white rounded-2xl border border-gray-200 transition-all">
                    <i class="fas fa-laptop-code text-4xl text-[#1B4D3E] mb-6"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Portal Mahasiswa</h3>
                    <p class="text-gray-600 leading-relaxed">Akses KRS, KHS, Transkrip Nilai, dan Jadwal Kuliah dalam satu dashboard terintegrasi.</p>
                </div>
                 <!-- Card 2 -->
                 <div class="feature-card p-8 bg-white rounded-2xl border border-gray-200 transition-all">
                    <i class="fas fa-chalkboard-teacher text-4xl text-[#D4AF37] mb-6"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Portal Dosen</h3>
                    <p class="text-gray-600 leading-relaxed">Input nilai, monitoring mahasiswa perwalian, dan jadwal mengajar yang up-to-date.</p>
                </div>
                 <!-- Card 3 -->
                 <div class="feature-card p-8 bg-white rounded-2xl border border-gray-200 transition-all">
                    <i class="fas fa-wallet text-4xl text-[#1B4D3E] mb-6"></i>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sistem Pembayaran</h3>
                    <p class="text-gray-600 leading-relaxed">Monitoring tagihan dan riwayat pembayaran SPP secara transparan dan akurat.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-[#1B4D3E] relative">
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-4xl font-bold text-white mb-6 serif">Siap Bergabung dengan Kami?</h2>
            <p class="text-xl text-white/80 mb-10 font-light">
                Daftarkan diri Anda sekarang dan jadilah bagian dari keluarga besar STAI Al-Fatih Tangerang.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.spmb.index') }}" class="px-10 py-4 bg-[#D4AF37] text-white font-bold rounded-lg shadow-xl hover:shadow-2xl hover:bg-[#c49f28] transition-all transform hover:-translate-y-1">
                    Daftar Sekarang
                </a>
                <a href="#kontak" class="px-10 py-4 bg-white/10 border border-white/30 text-white font-bold rounded-lg hover:bg-white/20 transition-all">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="kontak" class="bg-gray-900 text-gray-300 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
                <div class="space-y-6">
                    <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo" class="h-12 w-auto brightness-200 grayscale">
                    <p class="text-sm leading-relaxed text-gray-400">
                        Jalan KH. Hasyim Ashari, Tangerang, Banten.<br>
                        Mencetak Generasi Islami Berwawasan Global.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#1B4D3E] transition-colors"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#1B4D3E] transition-colors"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#1B4D3E] transition-colors"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6">Tautan Cepat</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-[#D4AF37] transition-colors">Profil Kampus</a></li>
                        <li><a href="#" class="hover:text-[#D4AF37] transition-colors">Visi & Misi</a></li>
                        <li><a href="#" class="hover:text-[#D4AF37] transition-colors">Struktur Organisasi</a></li>
                        <li><a href="#" class="hover:text-[#D4AF37] transition-colors">Akreditasi</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6">Akademik</h4>
                    <ul class="space-y-4 text-sm">
                        <li><a href="#" class="hover:text-[#D4AF37] transition-colors">Program Studi</a></li>
                        <li><a href="#" class="hover:text-[#D4AF37] transition-colors">Kalender Akademik</a></li>
                        <li><a href="#" class="hover:text-[#D4AF37] transition-colors">Beasiswa</a></li>
                        <li><a href="{{ route('public.spmb.index') }}" class="hover:text-[#D4AF37] transition-colors">Pendaftaran (SPMB)</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-white font-bold mb-6">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm">
                        <li class="flex items-start gap-4">
                            <i class="fas fa-map-marker-alt mt-1 text-[#D4AF37]"></i>
                            <span>{{ \App\Models\SystemSetting::get('institution_address', 'Tangerang, Banten') }}</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fas fa-phone text-[#D4AF37]"></i>
                            <span>{{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}</span>
                        </li>
                        <li class="flex items-center gap-4">
                            <i class="fas fa-envelope text-[#D4AF37]"></i>
                            <span>{{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} STAI Al-Fatih Tangerang. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/{{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}" target="_blank" class="fixed bottom-8 right-8 z-50 bg-[#25D366] text-white p-4 rounded-full shadow-2xl hover:bg-[#128C7E] transition-all transform hover:scale-110 flex items-center justify-center group">
        <i class="fab fa-whatsapp text-3xl"></i>
        <span class="absolute right-full mr-3 bg-white text-gray-800 px-3 py-1 rounded shadow-md text-sm font-bold opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
            Chat Admin
        </span>
    </a>

</body>
</html>
