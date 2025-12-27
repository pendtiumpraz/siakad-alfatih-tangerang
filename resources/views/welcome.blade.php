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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">

    <style>
        :root {
            --primary: #1B4D3E;
            --gold: #C5A028;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        h1, h2, h3, h4, .serif { font-family: 'Playfair Display', serif; }
        
        .bg-gradient-primary { background: linear-gradient(135deg, #163C31 0%, #1B4D3E 100%); }
        .text-gold { color: var(--gold); }
        .bg-gold { background-color: var(--gold); }
        .border-gold { border-color: var(--gold); }
        
        .hero-pattern {
            background-color: #163C31;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23C5A028' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .hover-card { transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), box-shadow 0.4s ease; }
        .hover-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px -15px rgba(27, 77, 62, 0.15); }

        /* Smooth reveal animation classes */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 1s ease; }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="text-gray-800 antialiased overflow-x-hidden selection:bg-[#C5A028] selection:text-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 transition-all duration-300 glass-nav py-4">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <a href="/" class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-alfatih.png') }}" class="h-10 w-auto" alt="Logo">
                    <div class="flex flex-col">
                        <span class="text-lg font-bold text-gray-900 tracking-tight leading-none">SIAKAD</span>
                        <span class="text-xs font-medium text-gray-500 tracking-widest uppercase">STAI AL-FATIH</span>
                    </div>
                </a>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="#" class="text-sm font-medium text-[#1B4D3E] font-bold transition-colors">Beranda</a>
                    <a href="#tentang" class="text-sm font-medium text-gray-600 hover:text-[#1B4D3E] transition-colors">Tentang</a>
                    <a href="#prodi" class="text-sm font-medium text-gray-600 hover:text-[#1B4D3E] transition-colors">Program Studi</a>
                    <a href="{{ route('public.spmb.index') }}" class="text-sm font-medium text-gray-600 hover:text-gold transition-colors relative group">
                        SPMB
                        <span class="absolute -top-3 -right-3 px-1.5 py-0.5 bg-red-500 text-white text-[9px] font-bold rounded-full">NEW</span>
                    </a>
                    <a href="#layanan" class="text-sm font-medium text-gray-600 hover:text-[#1B4D3E] transition-colors">Layanan</a>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-semibold text-white transition-all bg-[#1B4D3E] rounded-full hover:bg-[#163C31] hover:shadow-lg hover:shadow-green-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1B4D3E]">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-[90vh] flex items-center pt-20 overflow-hidden hero-pattern">
        <!-- Abstract BG Overlay -->
        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-[#163C31]/90 z-0"></div>
        
        <div class="absolute inset-0 z-0 opacity-20">
            <img src="https://images.unsplash.com/photo-1541963463532-d68292c34b19?q=80&w=2000&auto=format&fit=crop" class="w-full h-full object-cover" alt="Islamic Architecture">
        </div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 w-full py-20">
            <div class="grid lg:grid-cols-12 gap-16 items-center">
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-gold text-sm font-medium tracking-wide">
                        <span class="w-2 h-2 rounded-full bg-gold animate-pulse"></span>
                        Sistem Informasi Akademik Terpadu
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-bold text-white leading-[1.1] tracking-tight">
                        Pusat Layanan <br>
                        <span class="serif italic font-light text-gold">Akademik Digital</span>
                    </h1>
                    
                    <p class="text-xl text-gray-300 leading-relaxed max-w-2xl mx-auto lg:mx-0 font-light">
                        Mengintegrasikan seluruh proses akademik STAI Al-Fatih Tangerang dalam satu platform yang aman, efisien, dan dapat diakses dari mana saja.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 text-base font-semibold text-[#1B4D3E] bg-white rounded-full hover:bg-gray-50 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1">
                            Akses Portal Akademik
                        </a>
                        <a href="#tentang" class="inline-flex items-center justify-center gap-3 px-8 py-4 text-base font-semibold text-white border border-white/30 rounded-full hover:bg-white/10 backdrop-blur-sm transition-all">
                            Pelajari Selengkapnya
                        </a>
                    </div>
                </div>

                <!-- Hero Image/Card -->
                <div class="lg:col-span-5 hidden lg:block relative">
                    <div class="absolute -inset-4 bg-gold/20 rounded-[2rem] blur-2xl transform rotate-6"></div>
                    <div class="relative rounded-[2rem] overflow-hidden shadow-2xl border border-white/10">
                        <img src="https://images.unsplash.com/photo-1505664194779-8beaceb93744?q=80&w=800&auto=format&fit=crop" class="w-full h-[500px] object-cover hover:scale-105 transition-transform duration-700" alt="Study Vibes">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-8">
                            <p class="text-white font-serif italic text-lg opacity-90">"Menuntut ilmu adalah kewajiban bagi setiap muslim."</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Strip -->
    <div class="relative z-20 -mt-16 mb-20 max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 bg-white/5 backdrop-blur-sm border border-white/20 rounded-2xl p-8 shadow-2xl gap-8 text-center text-white">
            <div class="space-y-1">
                <div class="text-4xl font-bold serif text-gold">{{ $programStudis->count() }}</div>
                <div class="text-sm opacity-70 uppercase tracking-widest font-medium">Program Studi</div>
            </div>
            <div class="space-y-1">
                <div class="text-4xl font-bold serif text-gold">4.0</div>
                <div class="text-sm opacity-70 uppercase tracking-widest font-medium">Era Digital</div>
            </div>
            <div class="space-y-1">
                <div class="text-4xl font-bold serif text-gold">24/7</div>
                <div class="text-sm opacity-70 uppercase tracking-widest font-medium">Akses</div>
            </div>
            <div class="space-y-1">
                <div class="text-4xl font-bold serif text-gold">100%</div>
                <div class="text-sm opacity-70 uppercase tracking-widest font-medium">Paperless</div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <section id="tentang" class="py-24 lg:py-32 overflow-hidden bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="relative order-2 lg:order-1">
                    <div class="aspect-[4/3] rounded-[2rem] overflow-hidden bg-gray-100 relative z-10">
                        <img src="https://images.unsplash.com/photo-1557090495-fc9312e77b28?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover" alt="Islamic Interior">
                    </div>
                    <!-- Decorative elements -->
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-[#163C31] rounded-full opacity-5 blur-2xl z-0"></div>
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-gold rounded-full opacity-10 blur-2xl z-0"></div>
                    <div class="absolute -bottom-8 -right-8 bg-white p-6 rounded-2xl shadow-xl z-20 max-w-xs border border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-green-50 rounded-full text-[#1B4D3E]">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">Keamanan Data</p>
                                <p class="text-xs text-gray-500">Enkripsi Berstandar Tinggi</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-1 lg:order-2 space-y-8">
                    <div>
                        <span class="text-gold font-semibold uppercase tracking-widest text-sm">Tentang SIAKAD</span>
                        <h2 class="text-4xl lg:text-5xl font-bold mt-3 mb-6 text-gray-900">Transformasi Digital <br> <span class="serif italic text-[#1B4D3E]">Pendidikan Islam</span></h2>
                        <div class="w-20 h-1 bg-gold"></div>
                    </div>
                    
                    <p class="text-lg text-gray-600 leading-relaxed font-light">
                        SIAKAD STAI Al-Fatih merupakan wujud komitmen kami dalam menghadirkan tata kelola akademik yang transparan, akuntabel, dan modern. Sistem ini dirancang untuk memudahkan seluruh sivitas akademika dalam menjalankan aktivitas perkuliahan.
                    </p>
                    
                    <ul class="space-y-4 pt-4">
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-[#1B4D3E] mt-1"></i>
                            <span class="text-gray-700">Integrasi data mahasiswa dan kurikulum Real-time</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-[#1B4D3E] mt-1"></i>
                            <span class="text-gray-700">Kemudahan monitoring progres studi (KHS/Transkrip)</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-[#1B4D3E] mt-1"></i>
                            <span class="text-gray-700">Efisiensi pengisian Kartu Rencana Studi (KRS)</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Studi Section -->
    <section id="prodi" class="py-24 bg-gray-50 relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                <span class="text-gold font-semibold uppercase tracking-widest text-sm">Program Studi</span>
                <h2 class="text-4xl font-bold text-gray-900">Pilihan Program Studi <br><span class="serif italic text-[#1B4D3E]">Berkualitas</span></h2>
                <p class="text-gray-500 text-lg">Mempersiapkan generasi unggul yang berintegritas dan berwawasan luas.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($programStudis as $prodi)
                <div class="group bg-white rounded-2xl p-8 hover-card border border-gray-100 hover:border-[#1B4D3E]/20 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gray-50 rounded-bl-full -mr-4 -mt-4 transition-colors group-hover:bg-[#1B4D3E]/5"></div>
                    
                    <div class="w-14 h-14 bg-[#1B4D3E] rounded-xl flex items-center justify-center text-white text-xl mb-6 shadow-lg shadow-green-900/20 group-hover:scale-110 transition-transform">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-[#1B4D3E] transition-colors">{{ $prodi->nama_prodi }}</h3>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="px-2 py-1 rounded-md bg-gold/10 text-gold text-xs font-bold">{{ $prodi->jenjang }}</span>
                        <span class="text-sm text-gray-400 font-mono">{{ $prodi->kode_prodi }}</span>
                    </div>
                    
                    @if($prodi->akreditasi)
                    <!-- Akreditasi not prominent as requested, but minimal info if needed, or remove completely -->
                    @endif

                    <div class="pt-6 mt-6 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-sm text-gray-500">Fakultas Agama Islam</span>
                        <i class="fas fa-arrow-right text-gray-300 group-hover:text-[#1B4D3E] group-hover:translate-x-1 transition-all"></i>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-500 italic">Data program studi sedang diperbarui.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section id="layanan" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-6">
                <div class="space-y-4">
                    <span class="text-gold font-semibold uppercase tracking-widest text-sm">Layanan Digital</span>
                    <h2 class="text-4xl font-bold text-gray-900">Fitur Unggulan <br><span class="serif italic text-[#1B4D3E]">Mahasiswa & Dosen</span></h2>
                </div>
                <!-- <a href="#" class="text-[#1B4D3E] font-semibold hover:text-gold transition-colors flex items-center gap-2">Lihat semua fitur <i class="fas fa-arrow-right"></i></a> -->
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                <!-- Group 1: Academic -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-1 bg-gold rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Akademik</h3>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-[#1B4D3E]">
                            <i class="fas fa-edit"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">KRS Online</h4>
                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">Pengisian rencana studi semester secara mandiri dan validasi dosen wali.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-[#1B4D3E]">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">KHS & Transkrip</h4>
                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">Monitoring hasil studi dan pencetakan transkrip nilai sementara.</p>
                        </div>
                    </div>
                </div>

                <!-- Group 2: Administrasi -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-1 bg-gold rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Administrasi</h3>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-gold">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">E-Payment</h4>
                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">Riwayat pembayaran SPP dan biaya akademik lainnya secara transparan.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-50 flex items-center justify-center text-gold">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Biodata Digital</h4>
                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">Pengelolaan data pribadi mahasiswa dan dosen yang terintegrasi.</p>
                        </div>
                    </div>
                </div>

                <!-- Group 3: Dosen -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-10 h-1 bg-gold rounded-full"></div>
                        <h3 class="text-xl font-bold text-gray-900">Dosen</h3>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-800">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Input Nilai</h4>
                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">Sistem penilaian mahasiswa yang terstandarisasi dan mudah digunakan.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-800">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Perwalian</h4>
                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">Monitoring dan persetujuan aktivitas akademik mahasiswa bimbingan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Visual Divider / Gallery Strip -->
    <div class="grid grid-cols-2 md:grid-cols-4 h-64 w-full">
        <div class="relative group overflow-hidden">
            <img src="https://images.unsplash.com/photo-1542816417-0983c9c9ad53?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0" alt="Detail 1">
            <div class="absolute inset-0 bg-[#1B4D3E]/20 group-hover:bg-transparent transition-colors"></div>
        </div>
        <div class="relative group overflow-hidden">
            <img src="https://images.unsplash.com/photo-1590076215667-875d4ef2d7fe?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0" alt="Detail 2">
            <div class="absolute inset-0 bg-[#1B4D3E]/20 group-hover:bg-transparent transition-colors"></div>
        </div>
        <div class="relative group overflow-hidden">
            <img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0" alt="Detail 3">
            <div class="absolute inset-0 bg-[#1B4D3E]/20 group-hover:bg-transparent transition-colors"></div>
        </div>
        <div class="relative group overflow-hidden">
            <img src="https://images.unsplash.com/photo-1507842217121-9e289f811565?q=80&w=800&auto=format&fit=crop" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 grayscale group-hover:grayscale-0" alt="Detail 4">
            <div class="absolute inset-0 bg-[#1B4D3E]/20 group-hover:bg-transparent transition-colors"></div>
        </div>
    </div>

    <!-- CTA Section -->
    <section class="py-24 bg-[#1B4D3E] relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: url('{{ asset('images/logo-alfatih.png') }}'); background-repeat: repeat; background-size: 100px;"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-[#163C31] to-transparent"></div>
        
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center space-y-8">
            <h2 class="text-4xl md:text-5xl font-bold text-white leading-tight">Mulai Perjalanan Akademik <br> <span class="serif italic text-gold">Anda Sekarang</span></h2>
            <p class="text-lg text-white/80 font-light max-w-2xl mx-auto">
                Bergabunglah dengan komunitas akademik STAI Al-Fatih dan manfaatkan kemudahan layanan digital kami.
            </p>
            <div class="pt-4">
                <a href="{{ route('public.spmb.index') }}" class="inline-flex items-center justify-center gap-3 px-10 py-4 text-lg font-bold text-[#1B4D3E] bg-white rounded-full hover:bg-gold hover:text-white transition-all shadow-2xl hover:scale-105">
                    Daftar Sebagai Mahasiswa Baru
                </a>
            </div>
            <p class="text-sm text-white/40 pt-4">Sudah punya akun? <a href="{{ route('login') }}" class="text-gold hover:underline underline-offset-4">Masuk di sini</a></p>
        </div>
    </section>

    <!-- Floating WhatsApp Button -->
    <a href="https://wa.me/{{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}" target="_blank" class="fixed bottom-8 right-8 z-50 flex items-center justify-center w-16 h-16 bg-green-500 rounded-full shadow-2xl hover:bg-green-600 transition-all hover:scale-110 group" aria-label="Chat WhatsApp">
        <span class="absolute inline-flex w-full h-full rounded-full opacity-75 animate-ping bg-green-400"></span>
        <i class="fab fa-whatsapp text-3xl text-white relative z-10"></i>
        <div class="absolute right-full mr-4 bg-white px-4 py-2 rounded-lg shadow-lg text-sm font-medium text-gray-800 whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity invisible group-hover:visible">
            Hubungi Admin
        </div>
    </a>

    <!-- Footer -->
    <footer class="bg-[#102A23] text-white pt-20 pb-10 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                <!-- Brand -->
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-alfatih.png') }}" class="h-12 w-auto grayscale brightness-200" alt="Logo">
                        <div>
                            <span class="block font-bold text-lg tracking-wide">SIAKAD</span>
                            <span class="block text-xs uppercase tracking-widest opacity-60">STAI Al-Fatih</span>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Jl. KH. Hasyim Ashari No. 123<br>
                        Tangerang, Banten<br>
                        Indonesia
                    </p>
                </div>

                <!-- Links 1 -->
                <div>
                    <h4 class="text-gold font-bold mb-6 text-sm uppercase tracking-widest">Akademik</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Panduan Akademik</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kalender Akademik</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Jadwal Kuliah</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Beasiswa</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div>
                    <h4 class="text-gold font-bold mb-6 text-sm uppercase tracking-widest">Aplikasi</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login Mahasiswa</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Login Dosen</a></li>
                        <li><a href="{{ route('public.spmb.index') }}" class="hover:text-white transition-colors">Pendaftaran Baru</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="text-gold font-bold mb-6 text-sm uppercase tracking-widest">Bantuan</h4>
                    <ul class="space-y-4 text-sm text-gray-400">
                        <li class="flex items-center gap-3">
                            <i class="fas fa-envelope text-white/30"></i>
                            <span>{{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="fas fa-phone text-white/30"></i>
                            <span>{{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
                <p>&copy; {{ date('Y') }} STAI Al-Fatih Tangerang. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="{{ route('privacy-policy') }}" class="hover:text-white transition-colors">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Reveal Animation Script -->
    <script>
        window.addEventListener('scroll', reveal);
        function reveal(){
            var reveals = document.querySelectorAll('.reveal');
            for(var i = 0; i < reveals.length; i++){
                var windowHeight = window.innerHeight;
                var revealTop = reveals[i].getBoundingClientRect().top;
                var revealPoint = 150;
                if(revealTop < windowHeight - revealPoint){
                    reveals[i].classList.add('active');
                }
            }
        }
    </script>
</body>
</html>
