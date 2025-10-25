@extends('layouts.mahasiswa')

@section('title', 'Profile Mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Profile Mahasiswa</span>
            </h1>
            <p class="text-gray-600 mt-1">Informasi lengkap data mahasiswa</p>
        </div>
        <a href="{{ route('mahasiswa.profile.edit') }}"
           class="bg-[#4A7C59] hover:bg-[#3d6849] text-white px-6 py-3 rounded-lg font-semibold transition transform hover:scale-105 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span>Edit Profile</span>
        </a>
    </div>

    <div class="islamic-divider"></div>

    <!-- Profile Photo Section -->
    <div class="card-islamic p-6 text-center">
        <div class="inline-block relative">
            <div class="w-32 h-32 rounded-full islamic-border overflow-hidden bg-white p-2 mx-auto">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Mahasiswa' }}&size=300&background=4A7C59&color=fff"
                     alt="Profile Photo"
                     class="w-full h-full rounded-full object-cover">
            </div>
            <div class="absolute bottom-2 right-2 bg-[#D4AF37] text-white p-2 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <h2 class="text-2xl font-bold text-[#4A7C59] mt-4">{{ auth()->user()->name ?? 'Nama Mahasiswa' }}</h2>
        <p class="text-gray-600">{{ auth()->user()->nim ?? 'NIM: 202301010001' }}</p>
        <div class="mt-3">
            <span class="inline-block px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold text-sm">
                Status: Aktif
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="card-islamic p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Informasi Personal</span>
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide">Nama Lengkap</label>
                    <p class="text-gray-800 font-semibold mt-1">{{ auth()->user()->name ?? 'Ahmad Fauzi Ramadhan' }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Tempat Lahir</label>
                        <p class="text-gray-800 font-semibold mt-1">Jakarta</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Tanggal Lahir</label>
                        <p class="text-gray-800 font-semibold mt-1">15 Januari 2005</p>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide">Jenis Kelamin</label>
                    <p class="text-gray-800 font-semibold mt-1">Laki-laki</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide">Email</label>
                    <p class="text-gray-800 font-semibold mt-1">{{ auth()->user()->email ?? 'ahmad.fauzi@student.staialfatih.ac.id' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide">No. Telepon</label>
                    <p class="text-gray-800 font-semibold mt-1">+62 812-3456-7890</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide">Alamat</label>
                    <p class="text-gray-800 font-semibold mt-1">Jl. Pendidikan No. 123, Kelurahan Cibubur, Kecamatan Ciracas, Jakarta Timur, DKI Jakarta 13720</p>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="card-islamic p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Informasi Akademik</span>
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide">NIM</label>
                    <p class="text-gray-800 font-semibold mt-1">{{ auth()->user()->nim ?? '202301010001' }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide">Program Studi</label>
                    <p class="text-gray-800 font-semibold mt-1">Pendidikan Agama Islam</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Angkatan</label>
                        <p class="text-gray-800 font-semibold mt-1">2023</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Semester Aktif</label>
                        <p class="text-gray-800 font-semibold mt-1">Semester 5</p>
                    </div>
                </div>
                <div>
                    <label class="text-xs text-gray-500 uppercase tracking-wide">Status Mahasiswa</label>
                    <p class="text-gray-800 font-semibold mt-1">
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                            Aktif
                        </span>
                    </p>
                </div>
                <div class="islamic-divider"></div>
                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="bg-gradient-to-br from-[#D4AF37] to-[#F4E5C3] p-4 rounded-lg text-center">
                        <p class="text-xs text-gray-700 uppercase tracking-wide mb-1">IP Semester</p>
                        <p class="text-3xl font-bold text-white">3.75</p>
                    </div>
                    <div class="bg-gradient-to-br from-[#4A7C59] to-[#5a9c6f] p-4 rounded-lg text-center">
                        <p class="text-xs text-white/90 uppercase tracking-wide mb-1">IPK</p>
                        <p class="text-3xl font-bold text-white">3.68</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Total SKS Tempuh</label>
                        <p class="text-gray-800 font-semibold mt-1">98 SKS</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">SKS Lulus</label>
                        <p class="text-gray-800 font-semibold mt-1">92 SKS</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-islamic p-6 hover:shadow-lg transition cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Email Kampus</p>
                    <p class="text-sm font-semibold text-gray-800 break-all">ahmad.fauzi@student.staialfatih.ac.id</p>
                </div>
            </div>
        </div>

        <div class="card-islamic p-6 hover:shadow-lg transition cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Dosen Wali</p>
                    <p class="text-sm font-semibold text-gray-800">Dr. H. Abdullah, M.Pd.I</p>
                </div>
            </div>
        </div>

        <div class="card-islamic p-6 hover:shadow-lg transition cursor-pointer">
            <div class="flex items-center space-x-4">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Tahun Masuk</p>
                    <p class="text-sm font-semibold text-gray-800">2023 / 2024</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            بَارَكَ اللّٰهُ لَكَ فِي الْعِلْمِ وَالْعَمَلِ
        </p>
        <p class="text-gray-600 italic text-sm">
            Semoga Allah memberkahi ilmu dan amal Anda
        </p>
    </div>
</div>
@endsection
