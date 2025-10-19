@extends('layouts.dosen')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="{
    activeTab: 'overview',
    menuOpen: true
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-chalkboard-teacher mr-3"></i>
                        Panduan Dosen
                    </h1>
                    <p class="text-lg opacity-90">Tutorial lengkap untuk Dosen STAI AL-FATIH</p>
                </div>
                <div class="hidden lg:block">
                    <div class="bg-white/20 backdrop-blur rounded-xl p-6 text-center">
                        <i class="fas fa-graduation-cap text-5xl text-green-300"></i>
                        <p class="mt-2 font-semibold">Dosen</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md border-2 border-green-400 sticky top-4">
                    <button
                        @click="menuOpen = !menuOpen"
                        class="lg:hidden w-full px-4 py-3 bg-[#2D5F3F] text-white rounded-t-lg font-semibold flex items-center justify-between"
                    >
                        <span>Menu</span>
                        <i class="fas" :class="menuOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>

                    <nav class="p-4" x-show="menuOpen" x-transition>
                        <h3 class="font-bold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-green-400">Daftar Isi</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-home mr-2"></i>Overview
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'jadwal'" :class="activeTab === 'jadwal' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-calendar mr-2"></i>Jadwal
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'nilai'" :class="activeTab === 'nilai' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-edit mr-2"></i>Input Nilai
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'khs'" :class="activeTab === 'khs' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-file-alt mr-2"></i>Generate KHS
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'bimbingan'" :class="activeTab === 'bimbingan' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-user-friends mr-2"></i>Mahasiswa Bimbingan
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'tips'" :class="activeTab === 'tips' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-lightbulb mr-2"></i>Tips
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md border-2 border-green-400 p-8">

                    <!-- Overview -->
                    <div x-show="activeTab === 'overview'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-green-400">
                            <i class="fas fa-info-circle mr-3"></i>Overview - Dosen
                        </h2>

                        <div class="bg-gradient-to-r from-green-100 to-blue-100 rounded-lg p-6 mb-6">
                            <h3 class="text-2xl font-bold text-[#2D5F3F] mb-3">
                                Selamat Datang, Dosen! 🟢
                            </h3>
                            <p class="text-gray-700">
                                Sebagai <strong>Dosen</strong>, Anda fokus pada <strong>proses pembelajaran</strong> dan <strong>penilaian mahasiswa</strong>.
                                Anda dapat melihat jadwal, input nilai, generate KHS, dan membimbing mahasiswa wali.
                            </p>
                        </div>

                        <!-- Tanggung Jawab -->
                        <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                            <i class="fas fa-tasks mr-2"></i>Tanggung Jawab Utama
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                <h4 class="font-bold text-blue-700 mb-2">📅 Jadwal Mengajar</h4>
                                <p class="text-sm text-gray-600">Lihat jadwal kuliah yang diampu</p>
                            </div>
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                                <h4 class="font-bold text-green-700 mb-2">✍️ Input Nilai</h4>
                                <p class="text-sm text-gray-600">Input nilai mahasiswa (Tugas, UTS, UAS)</p>
                            </div>
                            <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                                <h4 class="font-bold text-purple-700 mb-2">📊 Generate KHS</h4>
                                <p class="text-sm text-gray-600">Generate KHS untuk mahasiswa</p>
                            </div>
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                                <h4 class="font-bold text-yellow-700 mb-2">👥 Mahasiswa Bimbingan</h4>
                                <p class="text-sm text-gray-600">Monitor mahasiswa wali (jika ada)</p>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="font-bold text-gray-800 mb-4">Menu Penting:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                <a href="{{ route('dosen.jadwal.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-calendar mr-2"></i>Jadwal Mengajar
                                </a>
                                <a href="{{ route('dosen.nilai.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-edit mr-2"></i>Input Nilai
                                </a>
                                <a href="{{ route('dosen.khs.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-file-alt mr-2"></i>Generate KHS
                                </a>
                                <a href="{{ route('dosen.pengumuman.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-bullhorn mr-2"></i>Pengumuman
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal -->
                    <div x-show="activeTab === 'jadwal'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-green-400">
                            <i class="fas fa-calendar mr-3"></i>Jadwal Mengajar
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Lihat jadwal kuliah yang Anda ampu semester ini. Jadwal ini di-set oleh Admin.
                        </p>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <h3 class="font-bold text-gray-800 mb-4">Cara Melihat Jadwal:</h3>
                            <ol class="space-y-3 text-sm">
                                <li><strong>1.</strong> Sidebar → Jadwal</li>
                                <li><strong>2.</strong> Pilih view: <strong>Table</strong> atau <strong>Calendar</strong></li>
                                <li><strong>3.</strong> Filter by semester jika perlu</li>
                                <li><strong>4.</strong> Lihat detail: Hari, Jam, Mata Kuliah, Ruangan</li>
                                <li><strong>5.</strong> Export ke PDF jika diperlukan</li>
                            </ol>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                            <h4 class="font-bold text-gray-800 mb-2">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>Informasi
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-1 list-disc ml-4">
                                <li>Jadwal bersifat <strong>read-only</strong> (tidak bisa edit)</li>
                                <li>Jika ada kesalahan, hubungi Admin</li>
                                <li>Jadwal hari ini ditampilkan di Dashboard</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Input Nilai -->
                    <div x-show="activeTab === 'nilai'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-green-400">
                            <i class="fas fa-edit mr-3"></i>Input Nilai Mahasiswa
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Input nilai mahasiswa untuk mata kuliah yang Anda ampu. Sistem akan otomatis menghitung nilai akhir dan grade.
                        </p>

                        <!-- Flow Input Nilai -->
                        <h3 class="text-xl font-bold text-[#2D5F3F] mb-4">Langkah Input Nilai:</h3>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <ol class="space-y-4">
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">1</span>
                                    <div>
                                        <h5 class="font-bold">Pilih Mata Kuliah</h5>
                                        <p class="text-sm text-gray-600">Sidebar → Nilai → Pilih mata kuliah yang diampu</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">2</span>
                                    <div>
                                        <h5 class="font-bold">Lihat Daftar Mahasiswa</h5>
                                        <p class="text-sm text-gray-600">Sistem menampilkan daftar mahasiswa yang mengikuti mata kuliah tersebut</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">3</span>
                                    <div>
                                        <h5 class="font-bold">Input Komponen Nilai</h5>
                                        <div class="bg-gray-50 p-4 rounded mt-2">
                                            <table class="w-full text-sm">
                                                <tr>
                                                    <td class="py-1 font-semibold">Kehadiran:</td>
                                                    <td class="py-1">0-100%</td>
                                                </tr>
                                                <tr>
                                                    <td class="py-1 font-semibold">Tugas:</td>
                                                    <td class="py-1">0-100</td>
                                                </tr>
                                                <tr>
                                                    <td class="py-1 font-semibold">UTS:</td>
                                                    <td class="py-1">0-100</td>
                                                </tr>
                                                <tr>
                                                    <td class="py-1 font-semibold">UAS:</td>
                                                    <td class="py-1">0-100</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">4</span>
                                    <div>
                                        <h5 class="font-bold">Sistem Hitung Otomatis</h5>
                                        <p class="text-sm text-gray-600 mb-2">Formula:</p>
                                        <div class="bg-blue-50 p-3 rounded text-sm">
                                            <strong>Nilai Akhir</strong> = (Kehadiran × 10%) + (Tugas × 20%) + (UTS × 30%) + (UAS × 40%)
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">5</span>
                                    <div>
                                        <h5 class="font-bold">Grade Otomatis</h5>
                                        <div class="bg-gray-50 p-4 rounded mt-2">
                                            <table class="w-full text-xs">
                                                <tr><td class="py-1">A : 85-100</td><td class="py-1">B- : 65-69</td></tr>
                                                <tr><td class="py-1">A- : 80-84</td><td class="py-1">C+ : 60-64</td></tr>
                                                <tr><td class="py-1">B+ : 75-79</td><td class="py-1">C : 55-59</td></tr>
                                                <tr><td class="py-1">B : 70-74</td><td class="py-1">D : 50-54, E : <50</td></tr>
                                            </table>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">6</span>
                                    <div>
                                        <h5 class="font-bold">Simpan</h5>
                                        <p class="text-sm text-gray-600">Pilih:</p>
                                        <div class="flex space-x-2 mt-2">
                                            <button class="px-3 py-1 bg-yellow-500 text-white rounded text-xs">Simpan Draft</button>
                                            <button class="px-3 py-1 bg-green-500 text-white rounded text-xs">Submit Final</button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">* Draft bisa di-edit lagi, Final akan terkunci</p>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <!-- Warning -->
                        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
                            <h4 class="font-bold text-red-700 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Catatan Penting
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-1 list-disc ml-4">
                                <li>Nilai yang sudah di-<strong>Submit Final</strong> akan terkunci</li>
                                <li>Jika perlu edit nilai final, hubungi Admin untuk unlock</li>
                                <li>Pastikan nilai sudah benar sebelum submit final</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Generate KHS -->
                    <div x-show="activeTab === 'khs'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-green-400">
                            <i class="fas fa-file-alt mr-3"></i>Generate KHS
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Setelah input nilai selesai, generate KHS (Kartu Hasil Studi) untuk mahasiswa.
                            KHS menampilkan IP semester dan total SKS.
                        </p>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
                            <h4 class="font-bold text-gray-800 mb-2">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>Apa itu KHS?
                            </h4>
                            <p class="text-sm text-gray-700">
                                KHS (Kartu Hasil Studi) adalah dokumen yang berisi daftar nilai mahasiswa per semester,
                                lengkap dengan IP (Indeks Prestasi) dan total SKS semester tersebut.
                            </p>
                        </div>

                        <!-- 2 Cara Generate -->
                        <h3 class="text-xl font-bold text-[#2D5F3F] mb-4">2 Cara Generate KHS:</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Individual -->
                            <div class="bg-white border-2 border-purple-300 rounded-lg p-5">
                                <h4 class="font-bold text-purple-700 mb-3">
                                    <i class="fas fa-user mr-2"></i>Generate Per Mahasiswa
                                </h4>
                                <ol class="text-sm text-gray-700 space-y-2">
                                    <li>1. Menu KHS → Pilih semester</li>
                                    <li>2. Pilih mahasiswa</li>
                                    <li>3. Klik "Generate KHS"</li>
                                    <li>4. Selesai!</li>
                                </ol>
                                <div class="mt-3 p-3 bg-purple-50 rounded text-xs">
                                    <strong>Cocok untuk:</strong> Mahasiswa yang terlambat atau revisi nilai
                                </div>
                            </div>

                            <!-- Bulk -->
                            <div class="bg-white border-2 border-green-300 rounded-lg p-5">
                                <h4 class="font-bold text-green-700 mb-3">
                                    <i class="fas fa-users mr-2"></i>Generate Bulk (Semua)
                                </h4>
                                <ol class="text-sm text-gray-700 space-y-2">
                                    <li>1. Menu KHS → Pilih semester</li>
                                    <li>2. Klik "Generate All"</li>
                                    <li>3. Konfirmasi</li>
                                    <li>4. Selesai!</li>
                                </ol>
                                <div class="mt-3 p-3 bg-green-50 rounded text-xs">
                                    <strong>Cocok untuk:</strong> Generate KHS seluruh mahasiswa sekaligus (akhir semester)
                                </div>
                            </div>
                        </div>

                        <!-- Syarat Generate -->
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                            <h4 class="font-bold text-gray-800 mb-3">
                                <i class="fas fa-exclamation-circle text-yellow-600 mr-2"></i>Syarat Generate KHS
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-1 list-disc ml-4">
                                <li>Mahasiswa sudah ada nilai di semester tersebut</li>
                                <li>Nilai sudah di-submit final (bukan draft)</li>
                                <li>Jika belum ada nilai, KHS tidak bisa di-generate</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Mahasiswa Bimbingan -->
                    <div x-show="activeTab === 'bimbingan'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-green-400">
                            <i class="fas fa-user-friends mr-3"></i>Mahasiswa Bimbingan
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Jika Anda ditunjuk sebagai <strong>Dosen Wali</strong>, Anda dapat memonitor progress akademik mahasiswa bimbingan.
                        </p>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <h3 class="font-bold text-gray-800 mb-4">Fitur yang Tersedia:</h3>
                            <ul class="space-y-3 text-sm">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Lihat Biodata</strong>
                                        <p class="text-gray-600">Data pribadi mahasiswa lengkap</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Monitor KHS</strong>
                                        <p class="text-gray-600">Lihat KHS semua semester</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Lihat IPK</strong>
                                        <p class="text-gray-600">Monitor Indeks Prestasi Kumulatif</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-600 mr-3 mt-1"></i>
                                    <div>
                                        <strong>Catatan Bimbingan</strong>
                                        <p class="text-gray-600">Buat catatan untuk mahasiswa</p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
                            <h4 class="font-bold text-gray-800 mb-2">
                                <i class="fas fa-lightbulb text-green-600 mr-2"></i>Tips Dosen Wali
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-1 list-disc ml-4">
                                <li>Pantau mahasiswa dengan IP < 2.5 secara berkala</li>
                                <li>Beri motivasi dan bimbingan akademik</li>
                                <li>Catat setiap pertemuan bimbingan</li>
                                <li>Koordinasi dengan orang tua jika diperlukan</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div x-show="activeTab === 'tips'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-green-400">
                            <i class="fas fa-lightbulb mr-3"></i>Tips & Best Practices
                        </h2>

                        <div class="space-y-4">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded">
                                <h4 class="font-bold text-blue-800 mb-2">💡 Input Nilai Secara Bertahap</h4>
                                <p class="text-sm text-gray-700">Tidak perlu menunggu akhir semester. Input nilai tugas/UTS dulu, lalu UAS di akhir</p>
                            </div>

                            <div class="bg-green-50 border-l-4 border-green-500 p-5 rounded">
                                <h4 class="font-bold text-green-800 mb-2">💡 Gunakan Save Draft</h4>
                                <p class="text-sm text-gray-700">Simpan sebagai draft jika belum yakin, bisa di-edit lagi nanti</p>
                            </div>

                            <div class="bg-purple-50 border-l-4 border-purple-500 p-5 rounded">
                                <h4 class="font-bold text-purple-800 mb-2">💡 Generate KHS di Akhir Semester</h4>
                                <p class="text-sm text-gray-700">Setelah semua nilai final, langsung generate KHS agar mahasiswa bisa lihat</p>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-5 rounded">
                                <h4 class="font-bold text-yellow-800 mb-2">💡 Export Nilai untuk Backup</h4>
                                <p class="text-sm text-gray-700">Export nilai ke Excel sebagai arsip pribadi</p>
                            </div>

                            <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded">
                                <h4 class="font-bold text-red-800 mb-2">💡 Cek Ulang Sebelum Submit Final</h4>
                                <p class="text-sm text-gray-700">Nilai final akan terkunci, pastikan sudah benar sebelum submit</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-xl p-6 text-white text-center">
            <p class="text-sm">
                <i class="fas fa-question-circle mr-2"></i>
                Butuh bantuan? Hubungi <a href="mailto:support@stai-alfatih.ac.id" class="underline hover:text-green-300">support@stai-alfatih.ac.id</a>
            </p>
            <p class="text-xs mt-2 opacity-75">
                Dibuat dengan ❤️ menggunakan Laravel & Claude Code
            </p>
        </div>
    </div>
</div>
@endsection
