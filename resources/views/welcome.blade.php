<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="D8MnsoKB8CGE--qTmPL2wOC87jUVwS7O6lZl9VaZwM8" />

    <title>SIAKAD STAI AL FATIH</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, .serif { font-family: 'Playfair Display', serif; }
        
        .hero-bg {
            background-image: linear-gradient(rgba(18, 28, 24, 0.8), rgba(27, 77, 62, 0.7)), url('https://images.unsplash.com/photo-1519817650390-64a93db51149?q=80&w=2000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .text-gold { color: #D4AF37; }
        .bg-gold { background-color: #D4AF37; }
        .border-gold { border-color: #D4AF37; }
        .hover-text-gold:hover { color: #D4AF37; }
        
        .nav-link {
            position: relative;
            font-weight: 500;
            color: #4B5563;
            transition: color 0.3s;
        }
        .nav-link:hover { color: #1B4D3E; }
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #D4AF37;
            transition: width 0.3s;
        }
        .nav-link:hover::after { width: 100%; }

        .prodi-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f3f4f6;
        }
        .prodi-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #D4AF37;
        }
    </style>
</head>
<body class="antialiased text-gray-800 bg-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-xl shadow-sm border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex justify-between items-center h-24">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-4 group">
                    <img class="h-12 w-auto transition-transform group-hover:scale-105" src="{{ asset('images/logo-alfatih.png') }}" alt="STAI Al-Fatih">
                    <div class="hidden md:block">
                        <div class="text-xl font-bold text-gray-900 leading-none font-serif tracking-tight group-hover:text-[#1B4D3E] transition-colors">SIAKAD</div>
                        <div class="text-xs text-gold uppercase tracking-[0.2em] font-medium mt-1">STAI Al-Fatih</div>
                    </div>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-10">
                    <a href="#" class="nav-link text-[#1B4D3E]">Beranda</a>
                    <a href="#tentang" class="nav-link">Tentang</a>
                    <a href="#prodi" class="nav-link">Program Studi</a>
                    <a href="#fasilitas" class="nav-link">Fasilitas</a>
                </div>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center gap-4">
                    <a href="{{ route('public.spmb.index') }}" class="px-6 py-3 text-sm font-bold text-[#1B4D3E] border-2 border-[#1B4D3E] rounded-full hover:bg-[#1B4D3E] hover:text-white transition-all transform hover:-translate-y-0.5">
                        SPMB Online
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3 text-sm font-bold text-white bg-[#1B4D3E] rounded-full hover:bg-[#163C31] shadow-lg hover:shadow-green-900/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fas fa-lock text-xs"></i> Login Portal
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg min-h-screen flex items-center justify-center text-center px-4 relative pt-24">
        <div class="max-w-5xl mx-auto space-y-8 relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-gold text-sm font-medium tracking-wider uppercase animate-fade-in-up">
                <i class="fas fa-star text-xs"></i> Platform Akademik Digital
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold text-white tracking-tight leading-[1.1] font-serif">
                Membangun Generasi <br>
                <span class="text-gold italic">Unggul & Berkarakter</span>
            </h1>
            
            <p class="text-xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed">
                Sistem Informasi Akademik Terintegrasi STAI Al-Fatih Tangerang. Mengelola aktivitas akademik dengan teknologi terkini.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 justify-center items-center pt-8">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-4 bg-[#D4AF37] hover:bg-[#c49f28] text-white font-bold rounded-full shadow-xl hover:shadow-[#D4AF37]/40 transition-all transform hover:-translate-y-1">
                    Masuk SIAKAD
                </a>
                <a href="{{ route('public.spmb.index') }}" class="w-full sm:w-auto px-10 py-4 bg-white/5 hover:bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold rounded-full transition-all group">
                    Daftar Mahasiswa Baru <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <a href="#stats" class="absolute bottom-10 left-1/2 -translate-x-1/2 text-white/40 hover:text-white transition-colors animate-bounce">
            <i class="fas fa-chevron-down text-2xl"></i>
        </a>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="bg-[#1B4D3E] py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('{{ asset('images/logo-alfatih.png') }}'); background-repeat: repeat; background-size: 200px;"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-6 border-r border-white/10 last:border-0">
                    <div class="text-5xl font-bold text-gold font-serif mb-2">{{ $programStudis->count() }}</div>
                    <div class="text-xs text-white/80 uppercase tracking-widest font-medium">Program Studi</div>
                </div>
                <div class="p-6 border-r border-white/10 last:border-0">
                    <div class="text-5xl font-bold text-gold font-serif mb-2">A</div>
                    <div class="text-xs text-white/80 uppercase tracking-widest font-medium">Akreditasi</div>
                </div>
                <div class="p-6 border-r border-white/10 last:border-0">
                    <div class="text-5xl font-bold text-gold font-serif mb-2">24/7</div>
                    <div class="text-xs text-white/80 uppercase tracking-widest font-medium">Akses Online</div>
                </div>
                <div class="p-6">
                    <div class="text-5xl font-bold text-gold font-serif mb-2">100%</div>
                    <div class="text-xs text-white/80 uppercase tracking-widest font-medium">Digital</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-28 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative group">
                    <div class="absolute inset-0 bg-[#1B4D3E] rounded-3xl transform rotate-3 transition-transform group-hover:rotate-6"></div>
                    <div class="absolute inset-0 bg-gold rounded-3xl transform -rotate-3 transition-transform group-hover:-rotate-6 opacity-30"></div>
                    <img src="https://images.unsplash.com/photo-1590076215667-875d4ef2d7fe?q=80&w=800&auto=format&fit=crop" alt="Arsitektur Islam" class="relative rounded-3xl shadow-2xl w-full h-[500px] object-cover">
                </div>
                
                <div class="space-y-8">
                    <div>
                        <span class="text-gold font-bold uppercase tracking-widest text-sm">Tentang Kampus</span>
                        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mt-4 leading-tight font-serif">
                            Transformasi Digital <br>
                            <span class="text-[#1B4D3E] italic">Pendidikan Islam</span>
                        </h2>
                    </div>
                    
                    <p class="text-lg text-gray-600 leading-relaxed">
                        SIAKAD STAI Al-Fatih hadir sebagai solusi modern untuk mendukung proses pembelajaran yang efektif dan efisien. Kami berkomitmen mencetak sarjana muslim yang tidak hanya unggul secara akademis, tetapi juga berakhlak mulia dan melek teknologi.
                    </p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 hover:bg-[#1B4D3E]/5 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-[#1B4D3E] flex flex-shrink-0 items-center justify-center text-white shadow-lg">
                                <i class="fas fa-laptop-code"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Digital System</h4>
                                <p class="text-sm text-gray-500 mt-1">Terintegrasi penuh</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 rounded-xl bg-gray-50 hover:bg-[#1B4D3E]/5 transition-colors">
                            <div class="w-10 h-10 rounded-full bg-gold flex flex-shrink-0 items-center justify-center text-white shadow-lg">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Data Secure</h4>
                                <p class="text-sm text-gray-500 mt-1">Keamanan terjamin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Studi -->
    <section id="prodi" class="py-28 bg-[#f8fafc]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="text-[#1B4D3E] font-bold uppercase tracking-widest text-sm bg-[#1B4D3E]/10 px-4 py-2 rounded-full">Fakultas Agama Islam</span>
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mt-6 mb-6 font-serif">Program Studi Pilihan</h2>
                <p class="text-gray-500 text-lg">Mempersiapkan sarjana yang kompeten dengan kurikulum berbasis nilai keislaman.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($programStudis as $prodi)
                <div class="prodi-card bg-white rounded-2xl p-8 group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-[#1B4D3E]/5 rounded-bl-[100px] transition-all group-hover:bg-[#1B4D3E]/10"></div>
                    
                    <div class="relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-white border-2 border-gray-100 shadow-sm flex items-center justify-center text-3xl mb-8 group-hover:border-gold group-hover:scale-110 transition-all text-[#1B4D3E]">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        
                        <div class="flex items-center gap-2 mb-4">
                            <span class="px-3 py-1 rounded-full bg-[#1B4D3E] text-white text-xs font-bold tracking-wide">
                                {{ $prodi->jenjang }}
                            </span>
                            <span class="text-xs font-mono text-gray-400 border border-gray-200 px-2 py-1 rounded-full">{{ $prodi->kode_prodi }}</span>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-[#1B4D3E] transition-colors leading-tight">
                            {{ $prodi->nama_prodi }}
                        </h3>
                        
                        <div class="pt-8 mt-4 border-t border-gray-100 flex items-center justify-between">
                            <span class="text-sm text-gray-500 font-medium">Status: Aktif</span>
                            <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-[#1B4D3E] group-hover:text-white transition-all">
                                <i class="fas fa-arrow-right text-sm"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20 bg-white rounded-3xl border border-dashed border-gray-300">
                    <div class="inline-block p-6 rounded-full bg-gray-50 text-gray-400 mb-4">
                        <i class="fas fa-folder-open text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Belum Ada Data</h3>
                    <p class="text-gray-500 mt-2">Data program studi sedang diperbarui.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Facilities -->
    <section id="fasilitas" class="py-28 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div>
                    <span class="text-gold font-bold uppercase tracking-widest text-sm">Layanan Digital</span>
                    <h2 class="text-4xl font-bold text-gray-900 mt-4 font-serif">Fitur & Fasilitas</h2>
                </div>
                <a href="{{ route('public.spmb.index') }}" class="text-[#1B4D3E] font-bold hover:text-gold transition-colors flex items-center gap-2 border-b-2 border-[#1B4D3E] hover:border-gold pb-1">
                    Lihat Semua <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <!-- Card 1 -->
                <div class="group p-8 rounded-3xl bg-gray-50 hover:bg-[#1B4D3E] transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-[#1B4D3E] text-2xl mb-8 shadow-sm group-hover:mb-12 transition-all">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-white font-serif">Portal Mahasiswa</h3>
                    <p class="text-gray-600 group-hover:text-white/80 leading-relaxed">
                        Dashboard terintegrasi untuk KRS, KHS, Jadwal Kuliah, dan monitoring perkembangan studi secara real-time.
                    </p>
                </div>
                 <!-- Card 2 -->
                 <div class="group p-8 rounded-3xl bg-gray-50 hover:bg-[#1B4D3E] transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-gold text-2xl mb-8 shadow-sm group-hover:mb-12 transition-all">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-white font-serif">Portal Dosen</h3>
                    <p class="text-gray-600 group-hover:text-white/80 leading-relaxed">
                        Kemudahan input nilai, validasi KRS, dan bimbingan akademik mahasiswa dalam satu platform.
                    </p>
                </div>
                 <!-- Card 3 -->
                 <div class="group p-8 rounded-3xl bg-gray-50 hover:bg-[#1B4D3E] transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-[#1B4D3E] text-2xl mb-8 shadow-sm group-hover:mb-12 transition-all">
                        <i class="fas fa-money-check-alt"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-white font-serif">E-Finance</h3>
                    <p class="text-gray-600 group-hover:text-white/80 leading-relaxed">
                        Sistem pembayaran kuliah yang transparan dengan riwayat tagihan dan verifikasi otomatis.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#102A23] text-white pt-24 pb-10 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-16 mb-20">
                <div class="space-y-8">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo" class="h-14 w-auto brightness-200 grayscale opacity-90">
                        <div>
                            <span class="block font-bold text-xl tracking-wide font-serif">SIAKAD</span>
                            <span class="block text-xs uppercase tracking-widest opacity-60">STAI Al-Fatih</span>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-8">
                        Jalan KH. Hasyim Ashari No. 123,<br>
                        Kota Tangerang, Banten, Indonesia.<br>
                        Membangun Peradaban dengan Ilmu.
                    </p>
                </div>

                <div>
                    <h4 class="text-gold font-bold mb-8 text-sm uppercase tracking-widest">Akademik</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Program Studi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kalender Akademik</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Fasilitas</a></li>
                        <li><a href="{{ route('public.spmb.index') }}" class="hover:text-white transition-colors">Pendaftaran (SPMB)</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-gold font-bold mb-8 text-sm uppercase tracking-widest">Akses Cepat</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Portal Mahasiswa</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Portal Dosen</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">E-Jurnal</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Perpustakaan Digital</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-gold font-bold mb-8 text-sm uppercase tracking-widest">Kontak</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-xs">
                                <i class="fas fa-phone"></i>
                            </div>
                            <span>{{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-xs">
                                <i class="fab fa-whatsapp"></i>
                            </div>
                            <span>{{ \App\Models\SystemSetting::get('spmb_whatsapp', '08123456789') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-xs">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <span>{{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} STAI Al-Fatih Tangerang. All rights reserved.</p>
                <div class="flex gap-8">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/{{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}" target="_blank" class="fixed bottom-8 right-8 z-[60] bg-[#25D366] text-white p-4 rounded-full shadow-2xl hover:bg-[#128C7E] transition-all transform hover:scale-110 flex items-center justify-center group" aria-label="WhatsApp Admin">
        <i class="fab fa-whatsapp text-3xl"></i>
        <div class="absolute right-full mr-4 bg-white text-gray-800 px-4 py-2 rounded-xl shadow-xl text-sm font-bold opacity-0 group-hover:opacity-100 transition-all invisible group-hover:visible whitespace-nowrap -translate-x-2 group-hover:translate-x-0">
            Hubungi Admin
            <div class="absolute right-[-6px] top-1/2 -translate-y-1/2 w-3 h-3 bg-white transform rotate-45"></div>
        </div>
    </a>

</body>
</html>
