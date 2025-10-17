@extends('layouts.mahasiswa')

@section('title', 'Pengumuman')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                <span>Pengumuman</span>
            </h1>
            <p class="text-gray-600 mt-1">Informasi dan pengumuman penting</p>
        </div>
        <button class="bg-[#4A7C59] hover:bg-[#3d6849] text-white px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Tandai Semua Sudah Dibaca</span>
        </button>
    </div>

    <div class="islamic-divider"></div>

    <!-- Filter Tabs -->
    <div class="card-islamic p-2" x-data="{ filter: 'all' }">
        <div class="flex space-x-2">
            <button
                @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-[#4A7C59] text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-6 py-3 rounded-lg font-semibold transition"
            >
                Semua
            </button>
            <button
                @click="filter = 'unread'"
                :class="filter === 'unread' ? 'bg-[#4A7C59] text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2"
            >
                <span>Belum Dibaca</span>
                <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
            </button>
            <button
                @click="filter = 'read'"
                :class="filter === 'read' ? 'bg-[#4A7C59] text-white' : 'text-gray-600 hover:bg-gray-100'"
                class="px-6 py-3 rounded-lg font-semibold transition"
            >
                Sudah Dibaca
            </button>
        </div>
    </div>

    <!-- Notification Cards -->
    <div class="space-y-4">
        <!-- Unread Notification 1 -->
        <div class="card-islamic p-6 hover:shadow-xl transition border-l-4 border-blue-500 bg-blue-50">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4 flex-1">
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-800">Pembayaran UTS Semester Genap</h3>
                            <span class="inline-block px-3 py-1 bg-blue-600 text-white rounded-full text-xs font-bold">
                                Baru
                            </span>
                        </div>
                        <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                            Kepada seluruh mahasiswa semester 5, dimohon untuk segera melakukan pembayaran UTS Semester Genap paling lambat tanggal 30 Oktober 2025. Pembayaran dapat dilakukan melalui transfer ke rekening kampus yang telah disediakan. Untuk informasi lebih lanjut, silakan hubungi bagian keuangan.
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>14 Oktober 2025</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Bagian Keuangan</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="text-[#4A7C59] hover:text-[#D4AF37] font-semibold text-sm flex items-center space-x-1">
                                <span>Tandai sudah dibaca</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unread Notification 2 -->
        <div class="card-islamic p-6 hover:shadow-xl transition border-l-4 border-yellow-500 bg-yellow-50">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4 flex-1">
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-800">Perubahan Jadwal Kuliah</h3>
                            <span class="inline-block px-3 py-1 bg-yellow-600 text-white rounded-full text-xs font-bold">
                                Baru
                            </span>
                        </div>
                        <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                            Informasi perubahan jadwal kuliah Mata Kuliah Fiqih Muamalah yang semula di Ruang A-103 dipindah ke Ruang C-204 mulai hari Selasa, 15 Oktober 2025. Perubahan ini dilakukan karena ada kegiatan di Ruang A-103. Mohon perhatiannya.
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>12 Oktober 2025</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Bagian Akademik</span>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="text-[#4A7C59] hover:text-[#D4AF37] font-semibold text-sm flex items-center space-x-1">
                                <span>Tandai sudah dibaca</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Read Notification -->
        <div class="card-islamic p-6 hover:shadow-xl transition border-l-4 border-green-500 opacity-75">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4 flex-1">
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-800">Nilai UAS Telah Keluar</h3>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                            Kepada seluruh mahasiswa, nilai UAS Semester Ganjil Tahun Akademik 2024/2025 sudah dapat dilihat melalui portal akademik. Silakan cek nilai Anda di menu Nilai. Jika ada keberatan terhadap nilai, dapat mengajukan komplain ke dosen pengampu maksimal 7 hari setelah pengumuman ini.
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>10 Oktober 2025</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Bagian Akademik</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Read Notification 2 -->
        <div class="card-islamic p-6 hover:shadow-xl transition border-l-4 border-purple-500 opacity-75">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4 flex-1">
                    <div class="bg-purple-100 p-3 rounded-full">
                        <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-2">
                            <h3 class="text-lg font-bold text-gray-800">Seminar Nasional Pendidikan Islam</h3>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-700 mb-3 leading-relaxed">
                            STAI AL-FATIH akan menyelenggarakan Seminar Nasional Pendidikan Islam dengan tema "Inovasi Pembelajaran PAI di Era Digital" pada tanggal 20 November 2025. Pendaftaran dibuka mulai sekarang hingga 15 November 2025. Mahasiswa yang berminat dapat mendaftar di sekretariat kampus. Sertifikat akan diberikan kepada peserta.
                        </p>
                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>3 Oktober 2025</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Himpunan Mahasiswa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            وَذَكِّرْ فَإِنَّ الذِّكْرَىٰ تَنفَعُ الْمُؤْمِنِينَ
        </p>
        <p class="text-gray-600 italic text-sm">
            Dan tetaplah memberi peringatan, karena sesungguhnya peringatan itu bermanfaat bagi orang-orang yang beriman
        </p>
        <p class="text-xs text-gray-500 mt-1">(QS. Adz-Dzariyat: 55)</p>
    </div>
</div>
@endsection
