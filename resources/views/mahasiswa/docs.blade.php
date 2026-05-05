@extends('layouts.mahasiswa')

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
                        <i class="fas fa-book-reader mr-3"></i>
                        Panduan Mahasiswa
                    </h1>
                    <p class="text-lg opacity-90">Tutorial lengkap untuk Mahasiswa STAI AL-FATIH</p>
                </div>
                <div class="hidden lg:block">
                    <div class="bg-white/20 backdrop-blur rounded-xl p-6 text-center">
                        <i class="fas fa-user-graduate text-5xl text-blue-300"></i>
                        <p class="mt-2 font-semibold">Mahasiswa</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                <div class="bg-white rounded-lg shadow-md border-2 border-blue-400 sticky top-4">
                    <button
                        @click="menuOpen = !menuOpen"
                        class="lg:hidden w-full px-4 py-3 bg-[#2D5F3F] text-white rounded-t-lg font-semibold flex items-center justify-between"
                    >
                        <span>Menu</span>
                        <i class="fas" :class="menuOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>

                    <nav class="p-4" x-show="menuOpen" x-transition>
                        <h3 class="font-bold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-blue-400">Daftar Isi</h3>
                        <ul class="space-y-2 text-sm">
                            <li>
                                <a @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-home mr-2"></i>Overview
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'jadwal'" :class="activeTab === 'jadwal' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-calendar mr-2"></i>Jadwal
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'khs'" :class="activeTab === 'khs' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-file-alt mr-2"></i>KHS & Nilai
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'pembayaran'" :class="activeTab === 'pembayaran' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'" class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-money-bill mr-2"></i>Pembayaran
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
            <div class="lg:w-3/4">
                <div class="bg-white rounded-lg shadow-md border-2 border-blue-400 p-8">

                    <!-- Overview -->
                    <div x-show="activeTab === 'overview'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-blue-400">
                            <i class="fas fa-info-circle mr-3"></i>Overview - Mahasiswa
                        </h2>

                        <div class="bg-gradient-to-r from-blue-100 to-purple-100 rounded-lg p-6 mb-6">
                            <h3 class="text-2xl font-bold text-[#2D5F3F] mb-3">
                                Selamat Datang, Mahasiswa! 🔵
                            </h3>
                            <p class="text-gray-700">
                                Sebagai <strong>Mahasiswa</strong>, Anda dapat melihat <strong>informasi akademik pribadi</strong> seperti
                                jadwal kuliah, nilai, KHS, pembayaran, dan kurikulum.
                            </p>
                        </div>

                        <!-- Fitur yang Tersedia -->
                        <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                            <i class="fas fa-th-list mr-2"></i>Fitur yang Tersedia
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                <h4 class="font-bold text-blue-700 mb-2">👤 Profile & Biodata</h4>
                                <p class="text-sm text-gray-600">Lihat dan edit profile Anda (email, phone, password)</p>
                            </div>
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                                <h4 class="font-bold text-green-700 mb-2">📅 Jadwal Kuliah</h4>
                                <p class="text-sm text-gray-600">Lihat jadwal kuliah semester aktif</p>
                            </div>
                            <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                                <h4 class="font-bold text-purple-700 mb-2">📊 KHS & Nilai</h4>
                                <p class="text-sm text-gray-600">Lihat nilai dan KHS per semester, IPK</p>
                            </div>
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                                <h4 class="font-bold text-yellow-700 mb-2">💰 Pembayaran</h4>
                                <p class="text-sm text-gray-600">Upload bukti bayar SPP dan lihat riwayat</p>
                            </div>
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                                <h4 class="font-bold text-red-700 mb-2">📚 Kurikulum</h4>
                                <p class="text-sm text-gray-600">Lihat mata kuliah per semester di prodi Anda</p>
                            </div>
                            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded">
                                <h4 class="font-bold text-indigo-700 mb-2">📢 Pengumuman</h4>
                                <p class="text-sm text-gray-600">Baca pengumuman dari kampus</p>
                            </div>
                        </div>

                        <!-- Quick Access -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h4 class="font-bold text-gray-800 mb-4">Akses Cepat:</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-sm">
                                <a href="{{ route('mahasiswa.jadwal.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-calendar mr-2"></i>Jadwal Kuliah
                                </a>
                                <a href="{{ route('mahasiswa.khs.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-file-alt mr-2"></i>KHS
                                </a>
                                <a href="{{ route('mahasiswa.nilai.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-chart-bar mr-2"></i>Nilai
                                </a>
                                <a href="{{ route('mahasiswa.pembayaran.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-money-bill mr-2"></i>Pembayaran
                                </a>
                                <a href="{{ route('mahasiswa.kurikulum') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-book mr-2"></i>Kurikulum
                                </a>
                                <a href="{{ route('mahasiswa.notifications.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                    <i class="fas fa-bell mr-2"></i>Pengumuman
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile -->
                    <div x-show="activeTab === 'profile'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-blue-400">
                            <i class="fas fa-user mr-3"></i>Profile & Biodata
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Lihat biodata lengkap Anda dan edit beberapa field yang diizinkan.
                        </p>

                        <!-- Yang Bisa Dilihat -->
                        <h3 class="text-xl font-bold text-[#2D5F3F] mb-4">Informasi yang Ditampilkan:</h3>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">Data Pribadi:</h4>
                                    <ul class="space-y-1 text-gray-600">
                                        <li>• NIM</li>
                                        <li>• Nama Lengkap</li>
                                        <li>• Tempat/Tanggal Lahir</li>
                                        <li>• Jenis Kelamin</li>
                                        <li>• Agama</li>
                                        <li>• Alamat</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 mb-2">Data Akademik:</h4>
                                    <ul class="space-y-1 text-gray-600">
                                        <li>• Program Studi</li>
                                        <li>• Angkatan</li>
                                        <li>• Semester Aktif</li>
                                        <li>• Status (Aktif/Cuti/Lulus)</li>
                                        <li>• IPK</li>
                                        <li>• Dosen Wali</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Yang Bisa Diedit -->
                        <h3 class="text-xl font-bold text-[#2D5F3F] mb-4">Yang Bisa Anda Edit:</h3>

                        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg mb-6">
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li>✅ <strong>Email</strong> - Email pribadi Anda</li>
                                <li>✅ <strong>Nomor HP</strong> - Nomor telepon yang bisa dihubungi</li>
                                <li>✅ <strong>Password</strong> - Ganti password login</li>
                                <li>✅ <strong>Foto</strong> - Upload foto profil</li>
                            </ul>
                        </div>

                        <!-- Yang Locked -->
                        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
                            <h4 class="font-bold text-red-700 mb-2">
                                <i class="fas fa-lock mr-2"></i>Field yang Locked (Tidak Bisa Diubah)
                            </h4>
                            <p class="text-sm text-gray-700 mb-2">
                                Field berikut hanya bisa diubah oleh Admin:
                            </p>
                            <ul class="text-sm text-gray-700 ml-4 list-disc">
                                <li>NIM, Nama Lengkap, Tempat/Tanggal Lahir</li>
                                <li>Program Studi, Angkatan</li>
                                <li>Status, Dosen Wali</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Jadwal -->
                    <div x-show="activeTab === 'jadwal'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-blue-400">
                            <i class="fas fa-calendar mr-3"></i>Jadwal Kuliah
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Lihat jadwal kuliah Anda semester ini. Jadwal ditampilkan per hari atau dalam format calendar.
                        </p>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <h3 class="font-bold text-gray-800 mb-4">Cara Melihat Jadwal:</h3>
                            <ol class="space-y-3 text-sm">
                                <li><strong>1.</strong> Sidebar → Jadwal</li>
                                <li><strong>2.</strong> Pilih view: <strong>Table</strong> (list) atau <strong>Calendar</strong></li>
                                <li><strong>3.</strong> Filter by hari jika perlu</li>
                                <li><strong>4.</strong> Lihat detail: Mata Kuliah, Dosen, Jam, Ruangan</li>
                                <li><strong>5.</strong> Export ke PDF atau tambahkan ke Google Calendar</li>
                            </ol>
                        </div>

                        <!-- Informasi Jadwal -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                            <h4 class="font-bold text-gray-800 mb-3">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>Informasi
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-1 list-disc ml-4">
                                <li>Jadwal ditampilkan untuk semester aktif</li>
                                <li>Jadwal hari ini juga muncul di Dashboard</li>
                                <li>Jika ada perubahan jadwal, akan ada pengumuman</li>
                                <li>Jadwal bersifat read-only (tidak bisa edit)</li>
                            </ul>
                        </div>
                    </div>

                    <!-- KHS & Nilai -->
                    <div x-show="activeTab === 'khs'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-blue-400">
                            <i class="fas fa-file-alt mr-3"></i>KHS & Nilai
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Lihat nilai mata kuliah dan KHS (Kartu Hasil Studi) Anda per semester.
                        </p>

                        <!-- Perbedaan KHS dan Nilai -->
                        <h3 class="text-xl font-bold text-[#2D5F3F] mb-4">Apa Bedanya?</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <!-- Nilai -->
                            <div class="bg-purple-50 border-2 border-purple-300 rounded-lg p-5">
                                <h4 class="font-bold text-purple-700 mb-3">
                                    <i class="fas fa-chart-bar mr-2"></i>Nilai
                                </h4>
                                <p class="text-sm text-gray-700 mb-2">Menampilkan detail nilai per mata kuliah:</p>
                                <ul class="text-xs text-gray-600 space-y-1 list-disc ml-4">
                                    <li>Kehadiran (15%)</li>
                                    <li>Tugas / Presentasi (15%)</li>
                                    <li>UTS (30%)</li>
                                    <li>UAS (40%)</li>
                                    <li>Nilai Akhir</li>
                                    <li>Grade (A, B, C, D, E)</li>
                                </ul>
                            </div>

                            <!-- KHS -->
                            <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-5">
                                <h4 class="font-bold text-blue-700 mb-3">
                                    <i class="fas fa-file-alt mr-2"></i>KHS
                                </h4>
                                <p class="text-sm text-gray-700 mb-2">Ringkasan per semester berisi:</p>
                                <ul class="text-xs text-gray-600 space-y-1 list-disc ml-4">
                                    <li>Daftar mata kuliah</li>
                                    <li>Nilai akhir & grade</li>
                                    <li>SKS per mata kuliah</li>
                                    <li><strong>IP Semester</strong></li>
                                    <li><strong>IPK</strong> (kumulatif)</li>
                                    <li>Total SKS lulus</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Sistem Penilaian / Grade -->
                        <h3 class="text-xl font-bold text-[#2D5F3F] mb-4">Sistem Penilaian (Grade)</h3>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <p class="text-sm text-gray-700 mb-4">
                                Berikut adalah sistem grade yang digunakan berdasarkan nilai akhir Anda:
                            </p>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <p class="text-sm font-semibold text-blue-800 mb-1">Komposisi Nilai Akhir</p>
                                <p class="text-xs text-blue-700">
                                    Kehadiran 15% + Tugas Individu/Kelompok/Presentasi 15% + UTS 30% + UAS 40%
                                </p>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-sm border border-gray-200 rounded">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-2 text-left font-semibold">Rentang Nilai</th>
                                            <th class="px-3 py-2 text-center font-semibold">Grade</th>
                                            <th class="px-3 py-2 text-center font-semibold">Bobot</th>
                                            <th class="px-3 py-2 text-left font-semibold">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr style="background-color: #DCFCE7;">
                                            <td class="px-3 py-2 font-semibold">80 - 100</td>
                                            <td class="px-3 py-2 text-center"><span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-white" style="background-color: #15803d;">A</span></td>
                                            <td class="px-3 py-2 text-center font-semibold">4.00</td>
                                            <td class="px-3 py-2">Sangat Baik</td>
                                        </tr>
                                        <tr style="background-color: #D1FAE5;">
                                            <td class="px-3 py-2 font-semibold">70 - 79</td>
                                            <td class="px-3 py-2 text-center"><span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-white" style="background-color: #22c55e;">B</span></td>
                                            <td class="px-3 py-2 text-center font-semibold">3.00</td>
                                            <td class="px-3 py-2">Baik</td>
                                        </tr>
                                        <tr style="background-color: #FEF3C7;">
                                            <td class="px-3 py-2 font-semibold">60 - 69</td>
                                            <td class="px-3 py-2 text-center"><span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-white" style="background-color: #eab308;">C</span></td>
                                            <td class="px-3 py-2 text-center font-semibold">2.00</td>
                                            <td class="px-3 py-2">Cukup</td>
                                        </tr>
                                        <tr style="background-color: #FED7AA;">
                                            <td class="px-3 py-2 font-semibold">50 - 59</td>
                                            <td class="px-3 py-2 text-center"><span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-white" style="background-color: #f97316;">D</span></td>
                                            <td class="px-3 py-2 text-center font-semibold">1.00</td>
                                            <td class="px-3 py-2 text-red-700">Tidak Lulus</td>
                                        </tr>
                                        <tr style="background-color: #FEE2E2;">
                                            <td class="px-3 py-2 font-semibold">0 - 49</td>
                                            <td class="px-3 py-2 text-center"><span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-bold text-white" style="background-color: #ef4444;">E</span></td>
                                            <td class="px-3 py-2 text-center font-semibold">0.00</td>
                                            <td class="px-3 py-2 text-red-700">Tidak Lulus</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                                <p class="text-xs text-gray-600">
                                    <strong>Catatan:</strong> Grade D dan E dihitung tidak lulus. Mahasiswa harus mengulang mata kuliah dengan grade tersebut.
                                </p>
                            </div>
                        </div>

                        <!-- Kapan Muncul? -->
                        <h3 class="text-xl font-bold text-[#2D5F3F] mb-4">Kapan KHS Muncul?</h3>

                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-6">
                            <p class="text-sm text-gray-700 mb-3">
                                KHS hanya muncul <strong>setelah dosen meng-generate</strong> KHS untuk semester tersebut.
                            </p>
                            <div class="bg-white p-4 rounded">
                                <p class="text-sm font-semibold mb-2">Alur:</p>
                                <ol class="text-xs text-gray-700 space-y-1">
                                    <li>1. Dosen input nilai → Selesai UAS</li>
                                    <li>2. Dosen submit nilai final</li>
                                    <li>3. Dosen generate KHS</li>
                                    <li>4. KHS muncul di portal mahasiswa ✅</li>
                                </ol>
                            </div>
                        </div>

                        <!-- Download -->
                        <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
                            <h4 class="font-bold text-gray-800 mb-3">
                                <i class="fas fa-download text-green-600 mr-2"></i>Download KHS
                            </h4>
                            <p class="text-sm text-gray-700 mb-3">
                                Anda bisa download KHS per semester atau Transkrip Nilai (semua semester) dalam format PDF.
                            </p>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm">
                                    <i class="fas fa-file-pdf mr-2"></i>Download KHS
                                </button>
                                <button class="px-4 py-2 bg-red-600 text-white rounded text-sm">
                                    <i class="fas fa-file-pdf mr-2"></i>Download Transkrip
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran -->
                    <div x-show="activeTab === 'pembayaran'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-blue-400">
                            <i class="fas fa-money-bill mr-3"></i>Pembayaran SPP
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Upload bukti pembayaran SPP dan lihat riwayat pembayaran Anda.
                        </p>

                        <!-- Cara Upload Bukti -->
                        <h3 class="text-xl font-bold text-[#2D5F3F] mb-4">Cara Upload Bukti Pembayaran:</h3>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <ol class="space-y-4">
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">1</span>
                                    <div>
                                        <h5 class="font-bold">Lihat Tagihan</h5>
                                        <p class="text-sm text-gray-600">Sidebar → Pembayaran → Lihat tagihan yang belum dibayar</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">2</span>
                                    <div>
                                        <h5 class="font-bold">Transfer Sesuai Nominal</h5>
                                        <div class="bg-gray-50 p-3 rounded mt-2 text-sm">
                                            <p class="font-semibold">Rekening Tujuan:</p>
                                            <p>Bank BNI Syariah</p>
                                            <p>No Rek: 1234567890</p>
                                            <p>A/n: STAI AL-FATIH TANGERANG</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">3</span>
                                    <div>
                                        <h5 class="font-bold">Upload Bukti</h5>
                                        <p class="text-sm text-gray-600 mb-2">Klik "Upload Bukti" pada tagihan, lalu:</p>
                                        <ul class="text-sm text-gray-600 ml-4 list-disc">
                                            <li>Pilih file bukti transfer (JPG/PNG/PDF, max 2MB)</li>
                                            <li>Input tanggal bayar</li>
                                            <li>Submit</li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">4</span>
                                    <div>
                                        <h5 class="font-bold">Tunggu Verifikasi</h5>
                                        <p class="text-sm text-gray-600">Status akan berubah dari "Pending" menjadi "Verified" setelah operator verifikasi (1x24 jam)</p>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <!-- Status Pembayaran -->
                        <h3 class="text-xl font-bold text-[#2D5F3F] mb-4">Status Pembayaran:</h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-yellow-50 border-2 border-yellow-300 rounded p-4 text-center">
                                <i class="fas fa-clock text-3xl text-yellow-600 mb-2"></i>
                                <p class="font-bold text-yellow-700">Pending</p>
                                <p class="text-xs text-gray-600">Menunggu verifikasi</p>
                            </div>
                            <div class="bg-green-50 border-2 border-green-300 rounded p-4 text-center">
                                <i class="fas fa-check-circle text-3xl text-green-600 mb-2"></i>
                                <p class="font-bold text-green-700">Verified</p>
                                <p class="text-xs text-gray-600">Pembayaran diterima</p>
                            </div>
                            <div class="bg-red-50 border-2 border-red-300 rounded p-4 text-center">
                                <i class="fas fa-times-circle text-3xl text-red-600 mb-2"></i>
                                <p class="font-bold text-red-700">Rejected</p>
                                <p class="text-xs text-gray-600">Upload ulang</p>
                            </div>
                        </div>

                        <!-- Catatan -->
                        <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
                            <h4 class="font-bold text-red-700 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Penting!
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-1 list-disc ml-4">
                                <li>Pastikan nominal transfer <strong>sesuai tagihan</strong></li>
                                <li>Bukti transfer harus <strong>jelas dan asli</strong></li>
                                <li>Upload dalam <strong>24 jam setelah transfer</strong></li>
                                <li>Jika ditolak, upload ulang dengan bukti yang benar</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div x-show="activeTab === 'tips'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-blue-400">
                            <i class="fas fa-lightbulb mr-3"></i>Tips untuk Mahasiswa
                        </h2>

                        <div class="space-y-4">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded">
                                <h4 class="font-bold text-blue-800 mb-2">💡 Cek Dashboard Setiap Hari</h4>
                                <p class="text-sm text-gray-700">Dashboard menampilkan jadwal hari ini, pengumuman terbaru, dan tagihan pending</p>
                            </div>

                            <div class="bg-green-50 border-l-4 border-green-500 p-5 rounded">
                                <h4 class="font-bold text-green-800 mb-2">💡 Bayar SPP Tepat Waktu</h4>
                                <p class="text-sm text-gray-700">Bayar SPP sebelum jatuh tempo untuk menghindari denda dan akses KHS tidak terblokir</p>
                            </div>

                            <div class="bg-purple-50 border-l-4 border-purple-500 p-5 rounded">
                                <h4 class="font-bold text-purple-800 mb-2">💡 Download KHS untuk Arsip</h4>
                                <p class="text-sm text-gray-700">Download KHS setiap semester sebagai arsip pribadi</p>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-5 rounded">
                                <h4 class="font-bold text-yellow-800 mb-2">💡 Baca Pengumuman</h4>
                                <p class="text-sm text-gray-700">Cek pengumuman secara berkala agar tidak ketinggalan info penting dari kampus</p>
                            </div>

                            <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded">
                                <h4 class="font-bold text-red-800 mb-2">💡 Hubungi Dosen Wali</h4>
                                <p class="text-sm text-gray-700">Jika ada masalah akademik, jangan ragu berkonsultasi dengan Dosen Wali Anda</p>
                            </div>

                            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-5 rounded">
                                <h4 class="font-bold text-indigo-800 mb-2">💡 Ganti Password Default</h4>
                                <p class="text-sm text-gray-700">Jika baru pertama login, segera ganti password default untuk keamanan</p>
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
                Butuh bantuan? Hubungi <a href="mailto:support@stai-alfatih.ac.id" class="underline hover:text-blue-300">support@stai-alfatih.ac.id</a>
            </p>
            <p class="text-xs mt-2 opacity-75">
                Dibuat dengan ❤️ menggunakan Laravel & Claude Code
            </p>
        </div>
    </div>
</div>
@endsection
