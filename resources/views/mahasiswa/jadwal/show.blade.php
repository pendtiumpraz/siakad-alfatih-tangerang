@extends('layouts.mahasiswa')

@section('title', 'Detail Mata Kuliah')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Ulumul Qur'an</h1>
            <p class="text-gray-600 mt-1">Detail informasi mata kuliah</p>
        </div>
        <a href="{{ route('mahasiswa.jadwal.index') }}"
           class="text-gray-600 hover:text-gray-800 px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2 border-2 border-gray-300 hover:border-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="islamic-divider"></div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Course Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Course Details Card -->
            <div class="card-islamic p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Informasi Mata Kuliah</span>
                </h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Kode Mata Kuliah</label>
                        <p class="text-gray-800 font-semibold mt-1">PAI-501</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">SKS</label>
                        <p class="text-gray-800 font-semibold mt-1">3 SKS</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Semester</label>
                        <p class="text-gray-800 font-semibold mt-1">Semester 5</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 uppercase tracking-wide">Jenis</label>
                        <p class="text-gray-800 font-semibold mt-1">
                            <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                Wajib
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Lecturer Information -->
            <div class="card-islamic p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Dosen Pengampu</span>
                </h3>
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-[#4A7C59]">
                        <img src="https://ui-avatars.com/api/?name=Ahmad+Fauzi&size=200&background=4A7C59&color=fff"
                             alt="Dosen"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-gray-800 text-lg">Dr. H. Ahmad Fauzi, M.Ag</p>
                        <p class="text-sm text-gray-600">NIP: 0123456789</p>
                        <p class="text-sm text-gray-500 mt-1">Email: ahmad.fauzi@staialfatih.ac.id</p>
                    </div>
                </div>
            </div>

            <!-- Course Description -->
            <div class="card-islamic p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Deskripsi Mata Kuliah</span>
                </h3>
                <p class="text-gray-700 leading-relaxed">
                    Mata kuliah ini membahas tentang ilmu-ilmu Al-Qur'an yang mencakup sejarah turunnya Al-Qur'an,
                    proses kodifikasi, qira'at, asbabun nuzul, makki dan madani, nasikh mansukh, muhkam mutasyabih,
                    amtsal, qasas, dan berbagai metodologi memahami Al-Qur'an. Mahasiswa diharapkan mampu memahami
                    kedudukan Al-Qur'an sebagai sumber utama ajaran Islam dan dapat mengaplikasikan ilmu-ilmu Al-Qur'an
                    dalam kehidupan sehari-hari serta dalam pembelajaran Pendidikan Agama Islam.
                </p>
            </div>

            <!-- Learning Outcomes -->
            <div class="card-islamic p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span>Capaian Pembelajaran</span>
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <div class="bg-[#4A7C59] text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-xs font-bold">1</span>
                        </div>
                        <p class="text-gray-700">Mahasiswa mampu menjelaskan konsep dasar Ulumul Qur'an dan sejarah turunnya Al-Qur'an</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-[#4A7C59] text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-xs font-bold">2</span>
                        </div>
                        <p class="text-gray-700">Mahasiswa mampu mengidentifikasi dan menganalisis berbagai ilmu yang berkaitan dengan Al-Qur'an</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-[#4A7C59] text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-xs font-bold">3</span>
                        </div>
                        <p class="text-gray-700">Mahasiswa mampu mengaplikasikan ilmu-ilmu Al-Qur'an dalam pembelajaran PAI</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <div class="bg-[#4A7C59] text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 mt-1">
                            <span class="text-xs font-bold">4</span>
                        </div>
                        <p class="text-gray-700">Mahasiswa memiliki sikap ta'zhim (mengagungkan) Al-Qur'an dan berkomitmen untuk mengamalkannya</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar: Schedule & Additional Info -->
        <div class="space-y-6">
            <!-- Schedule Card -->
            <div class="card-islamic p-6 sticky top-24">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Jadwal Kuliah</span>
                </h3>
                <div class="space-y-4">
                    <div class="bg-gradient-to-br from-[#4A7C59] to-[#5a9c6f] text-white p-4 rounded-lg text-center">
                        <p class="text-sm opacity-90 mb-1">Hari</p>
                        <p class="text-2xl font-bold">Senin</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-blue-50 p-3 rounded-lg text-center">
                            <p class="text-xs text-gray-600 mb-1">Waktu Mulai</p>
                            <p class="font-bold text-gray-800">08:00</p>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-lg text-center">
                            <p class="text-xs text-gray-600 mb-1">Waktu Selesai</p>
                            <p class="font-bold text-gray-800">09:40</p>
                        </div>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                        <p class="text-xs text-gray-600 mb-1">Ruangan</p>
                        <p class="font-bold text-gray-800 text-lg">A-201</p>
                        <p class="text-xs text-gray-500 mt-1">Gedung A Lantai 2</p>
                    </div>
                </div>
            </div>

            <!-- Assessment Info -->
            <div class="card-islamic p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span>Komponen Penilaian</span>
                </h3>
                <div class="space-y-2">
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-sm text-gray-600">Kehadiran</span>
                        <span class="font-semibold text-gray-800">10%</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-sm text-gray-600">Tugas</span>
                        <span class="font-semibold text-gray-800">20%</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-sm text-gray-600">UTS</span>
                        <span class="font-semibold text-gray-800">30%</span>
                    </div>
                    <div class="flex justify-between items-center pb-2 border-b">
                        <span class="text-sm text-gray-600">UAS</span>
                        <span class="font-semibold text-gray-800">40%</span>
                    </div>
                </div>
            </div>

            <!-- Reference Books -->
            <div class="card-islamic p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Buku Referensi</span>
                </h3>
                <div class="space-y-2 text-sm">
                    <p class="text-gray-700">1. Al-Qattan, Manna Khalil. Mabahits fi Ulumil Qur'an.</p>
                    <p class="text-gray-700">2. Az-Zarqani. Manahilul Irfan fi Ulumil Qur'an.</p>
                    <p class="text-gray-700">3. Quraish Shihab. Membumikan Al-Qur'an.</p>
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
            خَيْرُكُمْ مَنْ تَعَلَّمَ الْقُرْآنَ وَعَلَّمَهُ
        </p>
        <p class="text-gray-600 italic text-sm">
            Sebaik-baik kalian adalah yang mempelajari Al-Qur'an dan mengajarkannya
        </p>
        <p class="text-xs text-gray-500 mt-1">(HR. Bukhari)</p>
    </div>
</div>
@endsection
