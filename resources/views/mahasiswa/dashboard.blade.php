@extends('layouts.mahasiswa')

@section('title', 'Dashboard Mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Page Title with Islamic Decoration -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#D4AF37]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                </svg>
                <span>Dashboard Mahasiswa</span>
            </h1>
            <p class="text-gray-600 mt-1">Selamat datang di portal akademik STAI AL-FATIH</p>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            <p class="text-xs text-gray-500">Semester Genap 2024/2025</p>
        </div>
    </div>

    <div class="islamic-divider"></div>

    <!-- Welcome Card -->
    <div class="card-islamic p-6 islamic-pattern">
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
            <!-- Profile Photo -->
            <div class="relative">
                <div class="w-24 h-24 rounded-full islamic-border overflow-hidden bg-white p-1">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Mahasiswa' }}&size=200&background=4A7C59&color=fff"
                         alt="Profile Photo"
                         class="w-full h-full rounded-full object-cover">
                </div>
                <div class="absolute -bottom-2 -right-2 bg-[#D4AF37] text-white text-xs px-2 py-1 rounded-full font-semibold">
                    Aktif
                </div>
            </div>

            <!-- Student Info -->
            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold text-[#4A7C59] mb-2">
                    Assalamualaikum, {{ auth()->user()->name ?? 'Mahasiswa' }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mt-4">
                    <div class="bg-white/80 px-4 py-2 rounded-lg">
                        <p class="text-xs text-gray-500">NIM</p>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->nim ?? '202301010001' }}</p>
                    </div>
                    <div class="bg-white/80 px-4 py-2 rounded-lg">
                        <p class="text-xs text-gray-500">Program Studi</p>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->prodi ?? 'Pendidikan Agama Islam' }}</p>
                    </div>
                    <div class="bg-white/80 px-4 py-2 rounded-lg">
                        <p class="text-xs text-gray-500">Semester Aktif</p>
                        <p class="font-semibold text-gray-800">Semester {{ auth()->user()->semester ?? '5' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- IP Semester -->
        <div class="card-islamic p-6 gold-accent text-white transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">IP Semester</p>
                    <p class="text-4xl font-bold">3.75</p>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                </svg>
            </div>
            <div class="mt-3 text-xs opacity-90">
                <span>Semester 5 - 2024/2025</span>
            </div>
        </div>

        <!-- IPK -->
        <div class="card-islamic p-6 green-accent text-white transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">IPK</p>
                    <p class="text-4xl font-bold">3.68</p>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div class="mt-3 text-xs opacity-90">
                <span>Indeks Prestasi Kumulatif</span>
            </div>
        </div>

        <!-- Total SKS -->
        <div class="card-islamic p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total SKS Tempuh</p>
                    <p class="text-4xl font-bold text-gray-800">98</p>
                </div>
                <svg class="w-12 h-12 text-[#4A7C59] opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                <span>Dari 144 SKS total</span>
            </div>
        </div>

        <!-- Status Akademik -->
        <div class="card-islamic p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Status Akademik</p>
                    <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full font-bold text-lg">
                        Aktif
                    </span>
                </div>
                <svg class="w-12 h-12 text-[#D4AF37] opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="mt-3 text-xs text-gray-500">
                <span>Terdaftar Semester 5</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Schedule -->
        <div class="card-islamic p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                    <svg class="w-6 h-6 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Jadwal Hari Ini</span>
                </h3>
                <a href="{{ route('mahasiswa.jadwal.index') }}" class="text-sm text-[#4A7C59] hover:text-[#D4AF37] font-medium">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-3">
                <!-- Sample Schedule Items -->
                <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="bg-[#4A7C59] text-white px-3 py-2 rounded-lg text-center min-w-[70px]">
                        <p class="text-xs">Pukul</p>
                        <p class="font-bold">08:00</p>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">Ulumul Qur'an</h4>
                        <p class="text-sm text-gray-600">Ruang A-201 | Dr. H. Ahmad Fauzi, M.Ag</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="bg-[#4A7C59] text-white px-3 py-2 rounded-lg text-center min-w-[70px]">
                        <p class="text-xs">Pukul</p>
                        <p class="font-bold">10:00</p>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">Fiqih Muamalah</h4>
                        <p class="text-sm text-gray-600">Ruang A-103 | Dr. Hj. Siti Aminah, M.Pd.I</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="bg-[#4A7C59] text-white px-3 py-2 rounded-lg text-center min-w-[70px]">
                        <p class="text-xs">Pukul</p>
                        <p class="font-bold">13:00</p>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">Metodologi Penelitian</h4>
                        <p class="text-sm text-gray-600">Ruang B-105 | Prof. Dr. Muhammad Yasir, M.A</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="card-islamic p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                    <span>Pengumuman Terbaru</span>
                </h3>
                <a href="{{ route('mahasiswa.notifications.index') }}" class="text-sm text-[#4A7C59] hover:text-[#D4AF37] font-medium">
                    Lihat Semua
                </a>
            </div>
            <div class="space-y-3">
                <!-- Sample Announcements -->
                <div class="flex items-start space-x-3 p-3 bg-blue-50 border-l-4 border-blue-500 rounded-r-lg hover:bg-blue-100 transition cursor-pointer">
                    <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm">Pembayaran UTS Semester Genap</h4>
                        <p class="text-xs text-gray-600 mt-1">Batas pembayaran UTS 30 Oktober 2025</p>
                        <p class="text-xs text-gray-500 mt-1">3 hari yang lalu</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg hover:bg-yellow-100 transition cursor-pointer">
                    <svg class="w-5 h-5 text-yellow-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm">Perubahan Jadwal Kuliah</h4>
                        <p class="text-xs text-gray-600 mt-1">Mata kuliah Fiqih Muamalah dipindah ke Ruang C-204</p>
                        <p class="text-xs text-gray-500 mt-1">5 hari yang lalu</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 bg-green-50 border-l-4 border-green-500 rounded-r-lg hover:bg-green-100 transition cursor-pointer">
                    <svg class="w-5 h-5 text-green-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm">Nilai UAS Telah Keluar</h4>
                        <p class="text-xs text-gray-600 mt-1">Nilai UAS Semester Ganjil sudah dapat dilihat</p>
                        <p class="text-xs text-gray-500 mt-1">1 minggu yang lalu</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 bg-purple-50 border-l-4 border-purple-500 rounded-r-lg hover:bg-purple-100 transition cursor-pointer">
                    <svg class="w-5 h-5 text-purple-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800 text-sm">Seminar Nasional Pendidikan Islam</h4>
                        <p class="text-xs text-gray-600 mt-1">Pendaftaran dibuka hingga 15 November 2025</p>
                        <p class="text-xs text-gray-500 mt-1">2 minggu yang lalu</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Payments Reminder -->
    <div class="card-islamic p-6 bg-gradient-to-r from-yellow-50 to-orange-50 border-l-4 border-orange-500">
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-4">
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Pembayaran Mendatang</h3>
                    <p class="text-sm text-gray-600 mb-3">Anda memiliki tagihan yang perlu diselesaikan:</p>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-3 bg-white p-3 rounded-lg">
                            <span class="inline-block w-2 h-2 bg-red-500 rounded-full"></span>
                            <span class="font-semibold text-gray-800">UTS Semester Genap</span>
                            <span class="text-sm text-gray-600">- Rp 1.500.000</span>
                            <span class="text-xs text-red-600 font-semibold bg-red-100 px-2 py-1 rounded">Jatuh tempo: 30 Okt 2025</span>
                        </div>
                        <div class="flex items-center space-x-3 bg-white p-3 rounded-lg">
                            <span class="inline-block w-2 h-2 bg-yellow-500 rounded-full"></span>
                            <span class="font-semibold text-gray-800">SPP Bulan November</span>
                            <span class="text-sm text-gray-600">- Rp 500.000</span>
                            <span class="text-xs text-yellow-600 font-semibold bg-yellow-100 px-2 py-1 rounded">Jatuh tempo: 10 Nov 2025</span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('mahasiswa.pembayaran.index') }}"
               class="bg-[#4A7C59] hover:bg-[#3d6849] text-white px-6 py-3 rounded-lg font-semibold transition transform hover:scale-105">
                Bayar Sekarang
            </a>
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-12 h-12 text-[#D4AF37] mx-auto mb-4" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-xl text-[#4A7C59] font-semibold mb-2">
            "طَلَبُ الْعِلْمِ فَرِيْضَةٌ عَلَى كُلِّ مُسْلِمٍ"
        </p>
        <p class="text-gray-600 italic">
            "Menuntut ilmu itu wajib atas setiap muslim"
        </p>
        <p class="text-sm text-gray-500 mt-2">(HR. Ibnu Majah)</p>
    </div>
</div>
@endsection
