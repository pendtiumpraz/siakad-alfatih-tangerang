<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="google-site-verification" content="D8MnsoKB8CGE--qTmPL2wOC87jUVwS7O6lZl9VaZwM8" />

    <title>SIAKAD STAI AL FATIH - Sistem Informasi Akademik</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, .serif { font-family: 'Playfair Display', serif; }
        
        .hero-section {
            background-image: linear-gradient(rgba(18, 28, 24, 0.85), rgba(27, 77, 62, 0.8)), url('https://images.unsplash.com/photo-1519817650390-64a93db51149?q=80&w=2000&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* Hover Effects */
        .hover-lift { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .hover-lift:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="antialiased bg-white text-gray-800">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white shadow-md border-b border-gray-100 h-24 flex items-center">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full flex justify-between items-center">
            <!-- Logo -->
            <a href="/" class="flex items-center gap-4">
                <img class="h-12 w-auto" src="{{ asset('images/logo-alfatih.png') }}" alt="Logo">
                <div class="hidden md:block">
                    <div class="text-xl font-bold text-gray-900 leading-none font-serif tracking-tight">SIAKAD</div>
                    <div class="text-xs font-semibold uppercase tracking-widest mt-1" style="color: #D4AF37;">STAI Al-Fatih</div>
                </div>
            </a>

            <!-- Menu -->
            <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                <a href="#" class="font-bold" style="color: #1B4D3E;">Beranda</a>
                <a href="#tentang" class="text-gray-600 hover:text-black transition-colors">Tentang</a>
                <a href="#prodi" class="text-gray-600 hover:text-black transition-colors">Program Studi</a>
                <a href="#fasilitas" class="text-gray-600 hover:text-black transition-colors">Fasilitas</a>
            </div>

            <!-- Buttons -->
            <div class="hidden md:flex items-center gap-4">
                <a href="{{ route('public.spmb.index') }}" class="px-6 py-2.5 rounded-full border-2 text-sm font-bold transition-all hover:bg-gray-50" style="color: #1B4D3E; border-color: #1B4D3E;">
                    SPMB Online
                </a>
                <a href="{{ route('login') }}" class="px-6 py-2.5 rounded-full text-white text-sm font-bold transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5 flex items-center gap-2" style="background-color: #1B4D3E;">
                    <i class="fas fa-lock text-xs"></i> Login Portal
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section min-h-screen flex items-center justify-center text-center px-4 pt-24">
        <div class="max-w-5xl mx-auto space-y-8 animate-fade-in-up">
            <div class="inline-block px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                <span class="text-sm font-semibold tracking-wider uppercase text-white"><i class="fas fa-star text-xs mr-2" style="color: #D4AF37;"></i>Platform Akademik Digital</span>
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold text-white tracking-tight leading-[1.1] drop-shadow-lg">
                Generasi Unggul & <br>
                <span class="italic font-serif" style="color: #D4AF37;">Berakhlak Mulia</span>
            </h1>
            
            <p class="text-xl text-gray-200 max-w-2xl mx-auto font-light leading-relaxed">
                Sistem Informasi Akademik Terintegrasi STAI Al-Fatih Tangerang. Memudahkan akses layanan pendidikan bagi mahasiswa, dosen, dan staf secara real-time.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-5 justify-center items-center pt-8">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-10 py-4 text-white font-bold rounded-full shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-1" style="background-color: #D4AF37;">
                    Masuk SIAKAD
                </a>
                <a href="{{ route('public.spmb.index') }}" class="w-full sm:w-auto px-10 py-4 bg-transparent border border-white/50 text-white font-bold rounded-full hover:bg-white/10 transition-all backdrop-blur-sm">
                    Daftar Mahasiswa Baru
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20" style="background-color: #1B4D3E;">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-12 text-center text-white">
                <div>
                    <div class="text-5xl font-bold font-serif mb-2" style="color: #D4AF37;">{{ $programStudis->count() }}</div>
                    <div class="text-sm opacity-80 uppercase tracking-widest font-medium">Program Studi</div>
                </div>
                <div>
                    <div class="text-5xl font-bold font-serif mb-2" style="color: #D4AF37;">A</div>
                    <div class="text-sm opacity-80 uppercase tracking-widest font-medium">Akreditasi</div>
                </div>
                <div>
                    <div class="text-5xl font-bold font-serif mb-2" style="color: #D4AF37;">24/7</div>
                    <div class="text-sm opacity-80 uppercase tracking-widest font-medium">Akses Online</div>
                </div>
                <div>
                    <div class="text-5xl font-bold font-serif mb-2" style="color: #D4AF37;">100%</div>
                    <div class="text-sm opacity-80 uppercase tracking-widest font-medium">Digital</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <!-- Image -->
                <div class="relative rounded-3xl overflow-hidden shadow-2xl">
                    <img src="https://images.unsplash.com/photo-1590076215667-875d4ef2d7fe?q=80&w=800&auto=format&fit=crop" class="w-full h-[600px] object-cover hover:scale-105 transition-transform duration-700" alt="Islamic Architecture">
                    <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-black/80 to-transparent">
                        <p class="text-white italic font-serif text-lg">"Mencetak sarjana muslim yang kompeten dan berintegritas."</p>
                    </div>
                </div>
                
                <!-- Content -->
                <div class="space-y-8">
                    <div>
                        <span class="font-bold uppercase tracking-widest text-sm" style="color: #D4AF37;">Tentang Kampus</span>
                        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mt-4 leading-tight font-serif">
                            Transformasi Digital <br>
                            <span class="italic" style="color: #1B4D3E;">Pendidikan Islam</span>
                        </h2>
                    </div>
                    
                    <p class="text-lg text-gray-600 leading-relaxed">
                        SIAKAD STAI Al-Fatih hadir sebagai solusi modern untuk mendukung proses pembelajaran yang efektif dan efisien. Kami berkomitmen untuk menyediakan layanan akademik terbaik dengan dukungan teknologi informasi terkini.
                    </p>
                    
                    <div class="space-y-4 pt-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white flex-shrink-0" style="background-color: #1B4D3E;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Kurikulum Terintegrasi</h4>
                                <p class="text-gray-500">Standar pendidikan nasional dan nilai keislaman.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center text-white flex-shrink-0" style="background-color: #D4AF37;">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">Sistem Aman & Handal</h4>
                                <p class="text-gray-500">Keamanan data prioritas utama kami.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Prodi Section -->
    <section id="prodi" class="py-28 bg-gray-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest bg-green-100" style="color: #1B4D3E;">Fakultas Agama Islam</span>
                <h2 class="text-4xl font-bold text-gray-900 mt-6 mb-4 font-serif">Program Studi Pilihan</h2>
                <div class="w-24 h-1 mx-auto rounded-full" style="background-color: #D4AF37;"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($programStudis as $prodi)
                <div class="bg-white rounded-2xl p-8 hover-lift border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-24 h-24 rounded-bl-full transition-colors opacity-10 group-hover:opacity-20" style="background-color: #1B4D3E;"></div>
                    
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm border border-gray-100 group-hover:scale-110 transition-transform" style="color: #1B4D3E;">
                        <i class="fas fa-book-reader"></i>
                    </div>

                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 rounded-md text-white text-xs font-bold" style="background-color: #1B4D3E;">
                            {{ $prodi->jenjang }}
                        </span>
                        <span class="text-sm text-gray-500 font-mono">{{ $prodi->kode_prodi }}</span>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-green-800 transition-colors">
                        {{ $prodi->nama_prodi }}
                    </h3>
                    
                    <div class="pt-6 mt-6 border-t border-gray-100 flex justify-between items-center text-sm">
                        <span class="text-gray-500">Status: Aktif</span>
                        <span class="font-bold flex items-center gap-2" style="color: #D4AF37;">Detail <i class="fas fa-arrow-right"></i></span>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-16 text-center bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500">Belum ada data program studi.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Fasilitas Section -->
    <section id="fasilitas" class="py-28 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div>
                    <span class="font-bold uppercase tracking-widest text-sm" style="color: #D4AF37;">Fasilitas Digital</span>
                    <h2 class="text-4xl font-bold text-gray-900 mt-4 font-serif">Fitur Layanan Utama</h2>
                </div>
                <a href="{{ route('public.spmb.index') }}" class="font-bold flex items-center gap-2 hover:opacity-80 transition-opacity" style="color: #1B4D3E; border-bottom: 2px solid #1B4D3E;">
                    Lihat Selengkapnya <i class="fas fa-arrow-right"></i>
                </a>
            </div>

            <div class="grid md:grid-cols-3 gap-10">
                <!-- Card -->
                <div class="p-10 rounded-3xl bg-gray-50 hover:bg-[#1B4D3E] group transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-2xl mb-8 shadow-sm group-hover:mb-10 transition-all" style="color: #1B4D3E;">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-white">Portal Mahasiswa</h3>
                    <p class="text-gray-600 group-hover:text-gray-200 leading-relaxed">
                        Layanan akademik mandiri untuk KRS, KHS, Transkrip, dan Jadwal Kuliah dalam satu dashboard.
                    </p>
                </div>
                <!-- Card -->
                <div class="p-10 rounded-3xl bg-gray-50 hover:bg-[#1B4D3E] group transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-2xl mb-8 shadow-sm group-hover:mb-10 transition-all" style="color: #D4AF37;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-white">Portal Dosen</h3>
                    <p class="text-gray-600 group-hover:text-gray-200 leading-relaxed">
                        Manajemen perkuliahan, input nilai, dan validasi akademik yang mudah dan terintegrasi.
                    </p>
                </div>
                <!-- Card -->
                <div class="p-10 rounded-3xl bg-gray-50 hover:bg-[#1B4D3E] group transition-all duration-300">
                    <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center text-2xl mb-8 shadow-sm group-hover:mb-10 transition-all" style="color: #1B4D3E;">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4 group-hover:text-white">E-Finance</h3>
                    <p class="text-gray-600 group-hover:text-gray-200 leading-relaxed">
                        Sistem pembayaran kuliah yang transparan, aman, dan tercatat otomatis.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-white pt-24 pb-12" style="background-color: #102A23;">
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
                        Email: {{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}
                    </p>
                </div>

                <div>
                    <h4 class="font-bold mb-8 text-sm uppercase tracking-widest" style="color: #D4AF37;">Akademik</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="#prodi" class="hover:text-white transition-colors">Program Studi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kalender Akademik</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Fasilitas Kampus</a></li>
                        <li><a href="{{ route('public.spmb.index') }}" class="hover:text-white transition-colors">Pendaftaran Maba</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-8 text-sm uppercase tracking-widest" style="color: #D4AF37;">Akses Cepat</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Portal Mahasiswa</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Portal Dosen</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">E-Journal</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Perpustakaan</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold mb-8 text-sm uppercase tracking-widest" style="color: #D4AF37;">Hubungi Kami</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone text-gray-500"></i>
                            <span>{{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fab fa-whatsapp text-gray-500"></i>
                            <span>{{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} STAI Al-Fatih Tangerang. All rights reserved.</p>
                <div class="flex gap-8">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating WhatsApp -->
    <a href="https://wa.me/{{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}" target="_blank" class="fixed bottom-8 right-8 z-[60] text-white p-4 rounded-full shadow-2xl hover:scale-110 transition-all flex items-center justify-center group" style="background-color: #25D366;">
        <i class="fab fa-whatsapp text-3xl"></i>
        <div class="absolute right-full mr-4 bg-white text-gray-800 px-4 py-2 rounded-xl shadow-xl text-sm font-bold opacity-0 group-hover:opacity-100 transition-all invisible group-hover:visible whitespace-nowrap">
            Hubungi Admin
        </div>
    </a>

</body>
</html>
