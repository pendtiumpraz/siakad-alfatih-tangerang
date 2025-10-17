@extends('layouts.mahasiswa')

@section('title', 'Detail Nilai Semester')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Nilai Semester 5</h1>
            <p class="text-gray-600 mt-1">Semester Genap 2024/2025</p>
        </div>
        <a href="{{ route('mahasiswa.nilai.index') }}"
           class="text-gray-600 hover:text-gray-800 px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2 border-2 border-gray-300 hover:border-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="islamic-divider"></div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="card-islamic p-6 bg-gradient-to-br from-[#D4AF37] to-[#F4E5C3] text-center">
            <p class="text-sm text-gray-700 mb-1">IP Semester</p>
            <p class="text-5xl font-bold text-white mb-2">3.75</p>
            <p class="text-xs text-gray-700">Semester 5</p>
        </div>
        <div class="card-islamic p-6 text-center">
            <p class="text-sm text-gray-600 mb-1">Nilai Tertinggi</p>
            <p class="text-4xl font-bold text-green-700 mb-2">90</p>
            <p class="text-xs text-gray-600">Praktikum Microteaching</p>
        </div>
        <div class="card-islamic p-6 text-center">
            <p class="text-sm text-gray-600 mb-1">Nilai Terendah</p>
            <p class="text-4xl font-bold text-orange-600 mb-2">78</p>
            <p class="text-xs text-gray-600">Tafsir Tarbawi</p>
        </div>
        <div class="card-islamic p-6 text-center">
            <p class="text-sm text-gray-600 mb-1">Rata-rata Nilai</p>
            <p class="text-4xl font-bold text-blue-700 mb-2">83</p>
            <p class="text-xs text-gray-600">Dari 8 Mata Kuliah</p>
        </div>
    </div>

    <!-- Detailed Grades Table -->
    <div class="card-islamic p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
            <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Rincian Nilai Per Mata Kuliah</span>
        </h3>

        <div class="space-y-4">
            <!-- Course 1 -->
            <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-[#4A7C59] transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-bold text-gray-800 text-lg">Ulumul Qur'an</h4>
                        <p class="text-sm text-gray-600">PAI-501 | 3 SKS | Dr. H. Ahmad Fauzi, M.Ag</p>
                    </div>
                    <span class="inline-block px-4 py-2 bg-green-700 text-white rounded-full font-bold text-xl">A</span>
                </div>
                <div class="grid grid-cols-5 gap-3">
                    <div class="bg-blue-50 p-3 rounded text-center">
                        <p class="text-xs text-gray-600 mb-1">Kehadiran</p>
                        <p class="font-bold text-gray-800">95%</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded text-center">
                        <p class="text-xs text-gray-600 mb-1">Tugas</p>
                        <p class="font-bold text-gray-800">85</p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded text-center">
                        <p class="text-xs text-gray-600 mb-1">UTS</p>
                        <p class="font-bold text-gray-800">88</p>
                    </div>
                    <div class="bg-orange-50 p-3 rounded text-center">
                        <p class="text-xs text-gray-600 mb-1">UAS</p>
                        <p class="font-bold text-gray-800">90</p>
                    </div>
                    <div class="bg-purple-50 p-3 rounded text-center border-2 border-purple-300">
                        <p class="text-xs text-gray-600 mb-1">Nilai Akhir</p>
                        <p class="font-bold text-purple-800 text-lg">88</p>
                    </div>
                </div>
            </div>

            <!-- Course 2 -->
            <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-[#4A7C59] transition">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h4 class="font-bold text-gray-800 text-lg">Fiqih Muamalah</h4>
                        <p class="text-sm text-gray-600">PAI-502 | 3 SKS | Dr. Hj. Siti Aminah, M.Pd.I</p>
                    </div>
                    <span class="inline-block px-4 py-2 bg-green-600 text-white rounded-full font-bold text-xl">AB</span>
                </div>
                <div class="grid grid-cols-5 gap-3">
                    <div class="bg-blue-50 p-3 rounded text-center">
                        <p class="text-xs text-gray-600 mb-1">Kehadiran</p>
                        <p class="font-bold text-gray-800">92%</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded text-center">
                        <p class="text-xs text-gray-600 mb-1">Tugas</p>
                        <p class="font-bold text-gray-800">82</p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded text-center">
                        <p class="text-xs text-gray-600 mb-1">UTS</p>
                        <p class="font-bold text-gray-800">80</p>
                    </div>
                    <div class="bg-orange-50 p-3 rounded text-center">
                        <p class="text-xs text-gray-600 mb-1">UAS</p>
                        <p class="font-bold text-gray-800">85</p>
                    </div>
                    <div class="bg-purple-50 p-3 rounded text-center border-2 border-purple-300">
                        <p class="text-xs text-gray-600 mb-1">Nilai Akhir</p>
                        <p class="font-bold text-purple-800 text-lg">82</p>
                    </div>
                </div>
            </div>

            <!-- Additional courses would follow same pattern -->
            <div class="text-center py-4">
                <p class="text-sm text-gray-500">6 mata kuliah lainnya...</p>
            </div>
        </div>
    </div>

    <!-- Performance Chart Visualization -->
    <div class="card-islamic p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
            <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span>Distribusi Nilai</span>
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-3xl font-bold text-green-700 mb-2">3</div>
                <div class="text-sm text-gray-600">Grade A</div>
                <div class="text-xs text-gray-500 mt-1">37.5%</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-3xl font-bold text-green-600 mb-2">3</div>
                <div class="text-sm text-gray-600">Grade AB</div>
                <div class="text-xs text-gray-500 mt-1">37.5%</div>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <div class="text-3xl font-bold text-green-500 mb-2">2</div>
                <div class="text-sm text-gray-600">Grade B</div>
                <div class="text-xs text-gray-500 mt-1">25%</div>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <div class="text-3xl font-bold text-gray-400 mb-2">0</div>
                <div class="text-sm text-gray-600">Grade < B</div>
                <div class="text-xs text-gray-500 mt-1">0%</div>
            </div>
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            مَنْ سَلَكَ طَرِيْقًا يَلْتَمِسُ فِيْهِ عِلْمًا سَهَّلَ اللهُ لَهُ بِهِ طَرِيْقًا إِلَى الْجَنَّةِ
        </p>
        <p class="text-gray-600 italic text-sm">
            Barangsiapa menempuh jalan untuk mencari ilmu, maka Allah akan memudahkan baginya jalan menuju surga
        </p>
        <p class="text-xs text-gray-500 mt-1">(HR. Muslim)</p>
    </div>
</div>
@endsection
