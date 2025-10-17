@extends('layouts.mahasiswa')

@section('title', 'Kartu Hasil Studi (KHS)')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <span>Kartu Hasil Studi (KHS)</span>
            </h1>
            <p class="text-gray-600 mt-1">Transkrip nilai per semester</p>
        </div>
    </div>

    <div class="islamic-divider"></div>

    <!-- IPK Summary Card -->
    <div class="card-islamic p-6 islamic-pattern">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center space-x-6 mb-4 md:mb-0">
                <div class="w-24 h-24 rounded-full islamic-border overflow-hidden bg-white p-2">
                    <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Mahasiswa' }}&size=200&background=4A7C59&color=fff"
                         alt="Profile"
                         class="w-full h-full rounded-full object-cover">
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">{{ auth()->user()->name ?? 'Ahmad Fauzi Ramadhan' }}</h2>
                    <p class="text-gray-600">NIM: {{ auth()->user()->nim ?? '202301010001' }}</p>
                    <p class="text-gray-600">Program Studi: Pendidikan Agama Islam</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-gradient-to-br from-[#D4AF37] to-[#F4E5C3] rounded-lg">
                    <p class="text-sm text-gray-700 mb-1">IPK</p>
                    <p class="text-4xl font-bold text-white">3.68</p>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-[#4A7C59] to-[#5a9c6f] text-white rounded-lg">
                    <p class="text-sm opacity-90 mb-1">Total SKS</p>
                    <p class="text-4xl font-bold">92</p>
                </div>
            </div>
        </div>
    </div>

    <!-- KHS Cards per Semester -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Semester 5 -->
        <div class="card-islamic p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-[#F4E5C3]">
                <div>
                    <h3 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                        <span>Semester 5</span>
                    </h3>
                    <p class="text-sm text-gray-600">Genap 2024/2025</p>
                </div>
                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                    Aktif
                </span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Mata Kuliah</span>
                    <span class="font-bold text-gray-800">8 MK</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total SKS</span>
                    <span class="font-bold text-gray-800">20 SKS</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">IP Semester</span>
                    <span class="font-bold text-[#D4AF37] text-xl">3.75</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">IPK</span>
                    <span class="font-bold text-[#4A7C59] text-xl">3.68</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('mahasiswa.khs.show', 5) }}"
                   class="block w-full bg-[#4A7C59] hover:bg-[#3d6849] text-white text-center py-3 rounded-lg font-semibold transition">
                    Lihat Detail KHS
                </a>
            </div>
        </div>

        <!-- Semester 4 -->
        <div class="card-islamic p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-[#F4E5C3]">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Semester 4</h3>
                    <p class="text-sm text-gray-600">Ganjil 2024/2025</p>
                </div>
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                    Selesai
                </span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Mata Kuliah</span>
                    <span class="font-bold text-gray-800">8 MK</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total SKS</span>
                    <span class="font-bold text-gray-800">21 SKS</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">IP Semester</span>
                    <span class="font-bold text-[#D4AF37] text-xl">3.65</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">IPK</span>
                    <span class="font-bold text-[#4A7C59] text-xl">3.66</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('mahasiswa.khs.show', 4) }}"
                   class="block w-full bg-[#4A7C59] hover:bg-[#3d6849] text-white text-center py-3 rounded-lg font-semibold transition">
                    Lihat Detail KHS
                </a>
            </div>
        </div>

        <!-- Semester 3 -->
        <div class="card-islamic p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-[#F4E5C3]">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Semester 3</h3>
                    <p class="text-sm text-gray-600">Genap 2023/2024</p>
                </div>
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                    Selesai
                </span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Mata Kuliah</span>
                    <span class="font-bold text-gray-800">7 MK</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total SKS</span>
                    <span class="font-bold text-gray-800">19 SKS</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">IP Semester</span>
                    <span class="font-bold text-[#D4AF37] text-xl">3.70</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">IPK</span>
                    <span class="font-bold text-[#4A7C59] text-xl">3.67</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('mahasiswa.khs.show', 3) }}"
                   class="block w-full bg-[#4A7C59] hover:bg-[#3d6849] text-white text-center py-3 rounded-lg font-semibold transition">
                    Lihat Detail KHS
                </a>
            </div>
        </div>

        <!-- Semester 2 -->
        <div class="card-islamic p-6 hover:shadow-xl transition transform hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-[#F4E5C3]">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Semester 2</h3>
                    <p class="text-sm text-gray-600">Ganjil 2023/2024</p>
                </div>
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                    Selesai
                </span>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Mata Kuliah</span>
                    <span class="font-bold text-gray-800">8 MK</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total SKS</span>
                    <span class="font-bold text-gray-800">18 SKS</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">IP Semester</span>
                    <span class="font-bold text-[#D4AF37] text-xl">3.62</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">IPK</span>
                    <span class="font-bold text-[#4A7C59] text-xl">3.65</span>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('mahasiswa.khs.show', 2) }}"
                   class="block w-full bg-[#4A7C59] hover:bg-[#3d6849] text-white text-center py-3 rounded-lg font-semibold transition">
                    Lihat Detail KHS
                </a>
            </div>
        </div>

        <!-- Semester 1 -->
        <div class="card-islamic p-6 hover:shadow-xl transition transform hover:-translate-y-1 md:col-span-2">
            <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-[#F4E5C3]">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Semester 1</h3>
                    <p class="text-sm text-gray-600">Genap 2022/2023</p>
                </div>
                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                    Selesai
                </span>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">Total Mata Kuliah</p>
                    <p class="font-bold text-gray-800 text-2xl">6 MK</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">Total SKS</p>
                    <p class="font-bold text-gray-800 text-2xl">14 SKS</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">IP Semester</p>
                    <p class="font-bold text-[#D4AF37] text-2xl">3.70</p>
                </div>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-2">IPK</p>
                    <p class="font-bold text-[#4A7C59] text-2xl">3.70</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t">
                <a href="{{ route('mahasiswa.khs.show', 1) }}"
                   class="block w-full bg-[#4A7C59] hover:bg-[#3d6849] text-white text-center py-3 rounded-lg font-semibold transition">
                    Lihat Detail KHS
                </a>
            </div>
        </div>
    </div>

    <!-- Progress Chart -->
    <div class="card-islamic p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
            <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
            </svg>
            <span>Perkembangan IP Per Semester</span>
        </h3>
        <div class="grid grid-cols-5 gap-4">
            <div class="text-center">
                <div class="bg-blue-100 h-32 rounded-t-lg flex items-end justify-center" style="height: 148px;">
                    <div class="bg-[#4A7C59] w-full rounded-t-lg text-white font-bold py-2" style="height: 92.5%;">3.70</div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Sem 1</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 h-32 rounded-t-lg flex items-end justify-center" style="height: 148px;">
                    <div class="bg-[#4A7C59] w-full rounded-t-lg text-white font-bold py-2" style="height: 90.5%;">3.62</div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Sem 2</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 h-32 rounded-t-lg flex items-end justify-center" style="height: 148px;">
                    <div class="bg-[#4A7C59] w-full rounded-t-lg text-white font-bold py-2" style="height: 92.5%;">3.70</div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Sem 3</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 h-32 rounded-t-lg flex items-end justify-center" style="height: 148px;">
                    <div class="bg-[#4A7C59] w-full rounded-t-lg text-white font-bold py-2" style="height: 91.25%;">3.65</div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Sem 4</p>
            </div>
            <div class="text-center">
                <div class="bg-blue-100 h-32 rounded-t-lg flex items-end justify-center" style="height: 148px;">
                    <div class="bg-[#D4AF37] w-full rounded-t-lg text-white font-bold py-2" style="height: 93.75%;">3.75</div>
                </div>
                <p class="text-sm text-gray-600 mt-2">Sem 5</p>
            </div>
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            اِنَّ اللّٰهَ لَا يُضِيْعُ اَجْرَ الْمُحْسِنِيْنَ
        </p>
        <p class="text-gray-600 italic text-sm">
            Sesungguhnya Allah tidak menyia-nyiakan pahala orang-orang yang berbuat baik
        </p>
        <p class="text-xs text-gray-500 mt-1">(QS. At-Taubah: 120)</p>
    </div>
</div>
@endsection
