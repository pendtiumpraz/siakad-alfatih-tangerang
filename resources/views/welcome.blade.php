<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Site Verification --}}
    <meta name="google-site-verification" content="D8MnsoKB8CGE--qTmPL2wOC87jUVwS7O6lZl9VaZwM8" />

    {{-- SEO Meta Tags --}}
    <meta name="description" content="SIAKAD STAI AL FATIH - Sistem Informasi Akademik untuk mengelola data mahasiswa, dosen, jadwal kuliah, KRS, KHS, dan pembayaran SPP secara online.">
    <meta name="keywords" content="SIAKAD, STAI AL FATIH, sistem informasi akademik, perguruan tinggi islam, mahasiswa, dosen, KRS, KHS">
    <meta name="author" content="STAI AL FATIH">

    {{-- Open Graph --}}
    <meta property="og:title" content="SIAKAD STAI AL FATIH - Sistem Informasi Akademik">
    <meta property="og:description" content="Platform digital untuk mengelola seluruh aktivitas akademik mahasiswa dan dosen STAI AL FATIH.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">

    <title>SIAKAD STAI AL FATIH - Sistem Informasi Akademik</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ config('app.asset_version', '1.0') }}">
</head>
<body class="font-sans antialiased bg-white text-gray-900">
    <!-- Header -->
    <header class="fixed top-0 left-0 right-0 z-50 shadow-lg" style="background: linear-gradient(135deg, #2D5F3F 0%, #4A7C59 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <a href="/" class="flex items-center gap-3 text-white no-underline">
                    <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo STAI AL FATIH" class="h-12 w-12 object-contain">
                    <div>
                        <h1 class="text-lg font-bold">SIAKAD STAI AL FATIH</h1>
                        <p class="text-xs opacity-90">Sistem Informasi Akademik</p>
                    </div>
                </a>
                <nav class="flex flex-wrap items-center gap-2">
                    <a href="#tentang" class="text-white text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/20 transition">Tentang</a>
                    <a href="#prodi" class="text-white text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/20 transition">Program Studi</a>
                    <a href="#fitur" class="text-white text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/20 transition">Fitur</a>
                    <a href="{{ route('public.spmb.index') }}" class="text-white text-sm font-medium px-3 py-2 rounded-lg hover:bg-white/20 transition">Pendaftaran</a>
                    <a href="{{ route('login') }}" class="text-gray-900 text-sm font-semibold px-4 py-2 rounded-lg transition" style="background: #D4AF37;">
                        <i class="fas fa-sign-in-alt mr-1"></i> Masuk
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center pt-24 pb-16 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #2D5F3F 0%, #4A7C59 50%, #2D5F3F 100%);">
        <div class="absolute inset-0 bg-cover bg-center opacity-10" style="background-image: url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1920&q=80');"></div>
        <div class="max-w-7xl mx-auto w-full relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-center lg:text-left">
                    <div class="inline-block px-4 py-2 rounded-full text-sm font-medium mb-6 border" style="background: rgba(212, 175, 55, 0.2); color: #D4AF37; border-color: rgba(212, 175, 55, 0.3);">
                        <i class="fas fa-mosque mr-2"></i> Sekolah Tinggi Agama Islam
                    </div>
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                        Selamat Datang di <span style="color: #D4AF37;">SIAKAD STAI AL FATIH</span>
                    </h1>
                    <p class="text-white/90 text-lg mb-8 leading-relaxed">
                        Sistem Informasi Akademik terpadu untuk mengelola seluruh aktivitas akademik 
                        mahasiswa dan dosen STAI AL FATIH. Akses data akademik Anda kapan saja, di mana saja.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-gray-900 transition transform hover:-translate-y-1 hover:shadow-lg" style="background: #D4AF37;">
                            <i class="fas fa-sign-in-alt"></i> Masuk ke SIAKAD
                        </a>
                        <a href="{{ route('public.spmb.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-white border-2 border-white/30 bg-white/10 backdrop-blur-sm hover:bg-white/20 hover:border-white/50 transition">
                            <i class="fas fa-user-plus"></i> Daftar Mahasiswa Baru
                        </a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=600&q=80" alt="Mahasiswa Belajar" class="w-full max-w-md rounded-2xl shadow-2xl" loading="lazy">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 px-4" style="background: linear-gradient(135deg, #2D5F3F 0%, #4A7C59 100%);">
        <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div>
                <h3 class="text-4xl font-bold" style="color: #D4AF37;">{{ $programStudis->count() }}</h3>
                <p class="text-white/90">Program Studi</p>
            </div>
            <div>
                <h3 class="text-4xl font-bold" style="color: #D4AF37;">8</h3>
                <p class="text-white/90">Semester Studi</p>
            </div>
            <div>
                <h3 class="text-4xl font-bold" style="color: #D4AF37;">24/7</h3>
                <p class="text-white/90">Akses Online</p>
            </div>
            <div>
                <h3 class="text-4xl font-bold" style="color: #D4AF37;">100%</h3>
                <p class="text-white/90">Data Aman</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-20 px-4 bg-white" id="tentang">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <img src="https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?w=600&q=80" alt="Kampus" class="w-full rounded-2xl shadow-xl" loading="lazy">
                </div>
                <div>
                    <h2 class="text-3xl font-bold mb-2" style="color: #2D5F3F;">Tentang SIAKAD</h2>
                    <div class="w-20 h-1 rounded mb-6" style="background: linear-gradient(to right, #D4AF37, #eab308);"></div>
                    <h3 class="text-xl font-semibold mb-4" style="color: #2D5F3F;">Apa itu SIAKAD?</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        SIAKAD (Sistem Informasi Akademik) adalah platform digital yang mengintegrasikan 
                        seluruh proses akademik di STAI AL FATIH. Mulai dari pendaftaran mahasiswa baru, 
                        pengelolaan kurikulum, pengisian KRS, input nilai, hingga pencetakan transkrip.
                    </p>
                    <ul class="space-y-3 mt-6">
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle mt-1" style="color: #D4AF37;"></i>
                            <span>Akses data akademik kapan saja dan di mana saja</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle mt-1" style="color: #D4AF37;"></i>
                            <span>Proses akademik terintegrasi dan transparan</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle mt-1" style="color: #D4AF37;"></i>
                            <span>Keamanan data dengan enkripsi modern</span>
                        </li>
                        <li class="flex items-start gap-3 text-gray-700">
                            <i class="fas fa-check-circle mt-1" style="color: #D4AF37;"></i>
                            <span>Interface yang mudah digunakan</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Studi Section -->
    <section class="py-20 px-4 bg-gray-50" id="prodi">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-2" style="color: #2D5F3F;">Program Studi</h2>
                <div class="w-20 h-1 rounded mx-auto mb-4" style="background: linear-gradient(to right, #D4AF37, #eab308);"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Pilihan program studi yang tersedia di STAI AL FATIH untuk mencetak lulusan yang kompeten dan berakhlak mulia</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($programStudis as $prodi)
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 hover:shadow-xl hover:border-green-600 transition transform hover:-translate-y-1 flex items-start gap-4">
                    <div class="w-14 h-14 rounded-xl flex items-center justify-center text-white text-xl flex-shrink-0" style="background: linear-gradient(135deg, #2D5F3F, #4A7C59);">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $prodi->nama_prodi }}</h3>
                        <div class="text-sm font-semibold" style="color: #D4AF37;">{{ $prodi->jenjang }} - {{ $prodi->kode_prodi }}</div>
                        @if($prodi->akreditasi)
                        <div class="text-sm text-gray-600 mt-2 flex items-center gap-2">
                            <i class="fas fa-award" style="color: #D4AF37;"></i> 
                            Akreditasi: <strong>{{ $prodi->akreditasi }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 col-span-full text-center">
                    <i class="fas fa-info-circle text-4xl mb-4" style="color: #D4AF37;"></i>
                    <h3 class="text-lg font-semibold text-gray-900">Segera Hadir</h3>
                    <p class="text-gray-600">Informasi program studi akan segera tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-8 px-4 bg-gray-100">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="rounded-2xl overflow-hidden h-64">
                <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=400&q=80" alt="Kelas Perkuliahan" class="w-full h-full object-cover hover:scale-105 transition duration-300" loading="lazy">
            </div>
            <div class="rounded-2xl overflow-hidden h-64">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=400&q=80" alt="Diskusi Mahasiswa" class="w-full h-full object-cover hover:scale-105 transition duration-300" loading="lazy">
            </div>
            <div class="rounded-2xl overflow-hidden h-64">
                <img src="https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=400&q=80" alt="Perpustakaan" class="w-full h-full object-cover hover:scale-105 transition duration-300" loading="lazy">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4 bg-white" id="fitur">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-2" style="color: #2D5F3F;">Fitur SIAKAD</h2>
                <div class="w-20 h-1 rounded mx-auto mb-4" style="background: linear-gradient(to right, #D4AF37, #eab308);"></div>
                <p class="text-gray-600 max-w-2xl mx-auto">Berbagai fitur yang tersedia untuk mendukung aktivitas akademik mahasiswa dan dosen</p>
            </div>

            <!-- Fitur Mahasiswa -->
            <div class="mb-12">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #2D5F3F, #4A7C59); color: #D4AF37;">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <h3 class="text-2xl font-semibold" style="color: #2D5F3F;">Fitur untuk Mahasiswa</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #2D5F3F;">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Pengisian KRS Online</h4>
                        <p class="text-gray-600 text-sm">Isi Kartu Rencana Studi secara online sesuai kurikulum dan dosen pembimbing akademik.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309;">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Lihat KHS & Transkrip</h4>
                        <p class="text-gray-600 text-sm">Akses Kartu Hasil Studi dan transkrip nilai secara real-time setelah nilai diinput dosen.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8;">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Jadwal Perkuliahan</h4>
                        <p class="text-gray-600 text-sm">Lihat jadwal kuliah, ruangan, dan informasi dosen pengampu setiap mata kuliah.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #2D5F3F;">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Pembayaran SPP</h4>
                        <p class="text-gray-600 text-sm">Kelola pembayaran SPP dengan history yang tercatat dan upload bukti pembayaran.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309;">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Profil & Biodata</h4>
                        <p class="text-gray-600 text-sm">Kelola data pribadi, foto profil, dan informasi kontak yang up-to-date.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8;">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Pengumuman</h4>
                        <p class="text-gray-600 text-sm">Terima notifikasi dan pengumuman penting seputar kegiatan akademik.</p>
                    </div>
                </div>
            </div>

            <!-- Fitur Dosen -->
            <div>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl" style="background: linear-gradient(135deg, #2D5F3F, #4A7C59); color: #D4AF37;">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <h3 class="text-2xl font-semibold" style="color: #2D5F3F;">Fitur untuk Dosen</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #2D5F3F;">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Input Nilai</h4>
                        <p class="text-gray-600 text-sm">Input nilai mahasiswa per mata kuliah dengan mudah dan terintegrasi dengan sistem.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309;">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Lihat Jadwal Mengajar</h4>
                        <p class="text-gray-600 text-sm">Akses jadwal mengajar, ruangan, dan daftar mahasiswa yang mengambil mata kuliah.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8;">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Daftar Mahasiswa</h4>
                        <p class="text-gray-600 text-sm">Lihat daftar mahasiswa yang mengambil mata kuliah yang diampu.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #2D5F3F;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Perwalian Akademik</h4>
                        <p class="text-gray-600 text-sm">Kelola mahasiswa perwalian dan approve pengisian KRS mahasiswa bimbingan.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #b45309;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Cetak Laporan</h4>
                        <p class="text-gray-600 text-sm">Cetak berbagai laporan akademik seperti daftar nilai dan presensi.</p>
                    </div>
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 hover:shadow-lg hover:bg-white transition">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-lg mb-4" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1d4ed8;">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">Profil Dosen</h4>
                        <p class="text-gray-600 text-sm">Kelola data dosen, NIDN, dan informasi akademik lainnya.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 relative overflow-hidden" style="background: linear-gradient(135deg, #2D5F3F 0%, #4A7C59 100%);">
        <div class="absolute inset-0 bg-cover bg-center opacity-10" style="background-image: url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?w=1920&q=80');"></div>
        <div class="max-w-3xl mx-auto text-center relative z-10">
            <h2 class="text-3xl font-bold text-white mb-4">Siap Menggunakan SIAKAD?</h2>
            <p class="text-white/90 text-lg mb-8">Masuk ke akun Anda atau daftar sebagai mahasiswa baru untuk mulai mengakses layanan akademik STAI AL FATIH.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl font-semibold text-gray-900 transition transform hover:-translate-y-1 hover:shadow-lg" style="background: #D4AF37;">
                    <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                </a>
                <a href="{{ route('public.spmb.index') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl font-semibold text-white border-2 border-white/30 bg-white/10 backdrop-blur-sm hover:bg-white/20 hover:border-white/50 transition">
                    <i class="fas fa-user-plus"></i> Pendaftaran Mahasiswa Baru
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 px-4">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div>
                <h4 class="text-lg font-semibold mb-4" style="color: #D4AF37;">SIAKAD STAI AL FATIH</h4>
                <p class="text-white/70 mb-4 text-sm leading-relaxed">Sistem Informasi Akademik untuk mengelola data akademik mahasiswa dan dosen secara online, efisien, dan terintegrasi.</p>
                <p class="text-white/70 text-sm"><i class="fas fa-map-marker-alt mr-2" style="color: #D4AF37;"></i>{{ \App\Models\SystemSetting::get('institution_address', 'Tangerang, Banten') }}</p>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4" style="color: #D4AF37;">Kontak</h4>
                <a href="mailto:{{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}" class="text-white/70 text-sm block mb-2 hover:text-yellow-400 transition"><i class="fas fa-envelope mr-2" style="color: #D4AF37;"></i>{{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}</a>
                <a href="tel:{{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}" class="text-white/70 text-sm block mb-2 hover:text-yellow-400 transition"><i class="fas fa-phone mr-2" style="color: #D4AF37;"></i>{{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}</a>
                <a href="https://wa.me/{{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}" target="_blank" class="text-white/70 text-sm block mb-2 hover:text-green-400 transition"><i class="fab fa-whatsapp mr-2 text-green-500"></i>WhatsApp</a>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4" style="color: #D4AF37;">Tautan</h4>
                <a href="{{ route('login') }}" class="text-white/70 text-sm block mb-2 hover:text-yellow-400 transition"><i class="fas fa-sign-in-alt mr-2"></i>Masuk</a>
                <a href="{{ route('public.spmb.index') }}" class="text-white/70 text-sm block mb-2 hover:text-yellow-400 transition"><i class="fas fa-user-plus mr-2"></i>Pendaftaran</a>
                <a href="#prodi" class="text-white/70 text-sm block mb-2 hover:text-yellow-400 transition"><i class="fas fa-graduation-cap mr-2"></i>Program Studi</a>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4" style="color: #D4AF37;">Legal</h4>
                <a href="{{ route('privacy-policy') }}" class="text-white/70 text-sm block mb-2 hover:text-yellow-400 transition"><i class="fas fa-shield-alt mr-2"></i>Kebijakan Privasi</a>
                <a href="{{ route('terms') }}" class="text-white/70 text-sm block mb-2 hover:text-yellow-400 transition"><i class="fas fa-file-contract mr-2"></i>Syarat & Ketentuan</a>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-12 pt-8 border-t border-white/10 text-center text-white/50 text-sm">
            <p>
                &copy; {{ date('Y') }} STAI AL FATIH. All rights reserved.
                <a href="{{ route('privacy-policy') }}" class="hover:text-yellow-400 transition mx-2">Privacy Policy</a> |
                <a href="{{ route('terms') }}" class="hover:text-yellow-400 transition mx-2">Terms of Service</a>
            </p>
        </div>
    </footer>
</body>
</html>
