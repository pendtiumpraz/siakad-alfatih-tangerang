@extends('layouts.operator')

@section('content')
<div class="min-h-screen bg-gray-50 py-8" x-data="{
    activeTab: 'overview',
    searchTerm: '',
    menuOpen: true
}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-2xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold mb-2">
                        <i class="fas fa-book-open mr-3"></i>
                        Panduan Operator
                    </h1>
                    <p class="text-lg opacity-90">Tutorial lengkap menggunakan SIAKAD sebagai Operator</p>
                </div>
                <div class="hidden lg:block">
                    <div class="bg-white/20 backdrop-blur rounded-xl p-6 text-center">
                        <i class="fas fa-user-tie text-5xl text-yellow-300"></i>
                        <p class="mt-2 font-semibold">Operator Role</p>
                    </div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mt-6">
                <div class="relative">
                    <input
                        type="text"
                        x-model="searchTerm"
                        placeholder="Cari tutorial..."
                        class="w-full px-6 py-3 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                    >
                    <i class="fas fa-search absolute right-4 top-4 text-gray-400"></i>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar -->
            <div class="w-full lg:w-64 lg:min-w-64 lg:max-w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-md border-2 border-yellow-400 sticky top-4">
                    <button
                        @click="menuOpen = !menuOpen"
                        class="lg:hidden w-full px-4 py-3 bg-[#2D5F3F] text-white rounded-t-lg font-semibold flex items-center justify-between"
                    >
                        <span>Menu</span>
                        <i class="fas" :class="menuOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                    </button>

                    <nav class="p-4" x-show="menuOpen" x-transition>
                        <h3 class="font-bold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-yellow-400">
                            Daftar Isi
                        </h3>
                        <ul class="space-y-2">
                            <li>
                                <a @click="activeTab = 'overview'"
                                   :class="activeTab === 'overview' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'"
                                   class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-home mr-2"></i>Overview
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'spmb'"
                                   :class="activeTab === 'spmb' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'"
                                   class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-user-graduate mr-2"></i>SPMB
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'pembayaran'"
                                   :class="activeTab === 'pembayaran' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'"
                                   class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-money-bill mr-2"></i>Pembayaran
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'pengumuman'"
                                   :class="activeTab === 'pengumuman' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'"
                                   class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-bullhorn mr-2"></i>Pengumuman
                                </a>
                            </li>
                            <li>
                                <a @click="activeTab = 'tips'"
                                   :class="activeTab === 'tips' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'"
                                   class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                    <i class="fas fa-lightbulb mr-2"></i>Tips
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1">
                <div class="bg-white rounded-lg shadow-md border-2 border-yellow-400 p-8">

                    <!-- Overview -->
                    <div x-show="activeTab === 'overview'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-yellow-400">
                            <i class="fas fa-info-circle mr-3"></i>Overview - Operator
                        </h2>

                        <div class="bg-gradient-to-r from-yellow-100 to-green-100 rounded-lg p-6 mb-6">
                            <h3 class="text-2xl font-bold text-[#2D5F3F] mb-3">
                                Selamat Datang, Operator! 🟡
                            </h3>
                            <p class="text-gray-700">
                                Sebagai <strong>Operator</strong>, Anda fokus pada <strong>operasional keuangan</strong> dan <strong>verifikasi SPMB</strong>.
                                Anda dapat memverifikasi pembayaran, dokumen pendaftar, dan membuat pengumuman.
                            </p>
                        </div>

                        <!-- Responsibilities -->
                        <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                            <i class="fas fa-tasks mr-2"></i>Tanggung Jawab Utama
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                                <h4 class="font-bold text-green-700 mb-2">✅ Verifikasi Pembayaran SPMB</h4>
                                <p class="text-sm text-gray-600">Cek dan approve bukti transfer pendaftaran mahasiswa baru</p>
                            </div>
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                                <h4 class="font-bold text-blue-700 mb-2">✅ Verifikasi Dokumen</h4>
                                <p class="text-sm text-gray-600">Review dokumen pendaftar (KTP, Ijazah, dll)</p>
                            </div>
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                                <h4 class="font-bold text-yellow-700 mb-2">✅ Verifikasi Pembayaran SPP</h4>
                                <p class="text-sm text-gray-600">Approve pembayaran SPP mahasiswa</p>
                            </div>
                            <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded">
                                <h4 class="font-bold text-purple-700 mb-2">✅ Manage Pengumuman</h4>
                                <p class="text-sm text-gray-600">Buat pengumuman untuk mahasiswa/dosen</p>
                            </div>
                        </div>

                        <!-- Access Level -->
                        <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                            <i class="fas fa-lock mr-2"></i>Level Akses
                        </h3>

                        <div class="bg-gray-50 rounded-lg p-6">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b-2 border-gray-300">
                                        <th class="text-left py-2">Fitur</th>
                                        <th class="text-center py-2">Akses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-b">
                                        <td class="py-2">Verifikasi Pembayaran</td>
                                        <td class="text-center"><span class="text-green-600 font-bold">✅ Full</span></td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2">Verifikasi Dokumen SPMB</td>
                                        <td class="text-center"><span class="text-yellow-600 font-bold">⚠️ Approve Only</span></td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2">Seleksi Pendaftar</td>
                                        <td class="text-center"><span class="text-red-600 font-bold">❌ No Access</span></td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2">Aktivasi Mahasiswa</td>
                                        <td class="text-center"><span class="text-red-600 font-bold">❌ No Access</span></td>
                                    </tr>
                                    <tr class="border-b">
                                        <td class="py-2">Master Data</td>
                                        <td class="text-center"><span class="text-blue-600 font-bold">👁️ Read Only</span></td>
                                    </tr>
                                    <tr>
                                        <td class="py-2">Pengumuman</td>
                                        <td class="text-center"><span class="text-green-600 font-bold">✅ Full</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- SPMB -->
                    <div x-show="activeTab === 'spmb'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-yellow-400">
                            <i class="fas fa-user-graduate mr-3"></i>SPMB
                        </h2>

                        <!-- Verifikasi Pembayaran -->
                        <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                            <i class="fas fa-money-check mr-2"></i>Verifikasi Pembayaran Pendaftaran
                        </h3>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <ol class="space-y-4">
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">1</span>
                                    <div>
                                        <h5 class="font-bold">Akses SPMB</h5>
                                        <p class="text-sm text-gray-600">Sidebar → SPMB → Tab "Pembayaran Pending"</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">2</span>
                                    <div>
                                        <h5 class="font-bold">Lihat Bukti</h5>
                                        <p class="text-sm text-gray-600">Klik "Lihat Bukti" untuk melihat bukti transfer</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">3</span>
                                    <div>
                                        <h5 class="font-bold">Validasi</h5>
                                        <p class="text-sm text-gray-600 mb-2">Cek:</p>
                                        <ul class="text-xs text-gray-600 ml-4 list-disc">
                                            <li>Nominal sesuai (Rp 250.000)</li>
                                            <li>Tanggal transfer valid</li>
                                            <li>Rekening tujuan benar</li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">4</span>
                                    <div>
                                        <h5 class="font-bold">Verifikasi</h5>
                                        <div class="flex space-x-2 mt-2">
                                            <button class="px-3 py-1 bg-green-500 text-white rounded text-xs">✓ Verifikasi</button>
                                            <button class="px-3 py-1 bg-red-500 text-white rounded text-xs">✗ Tolak</button>
                                        </div>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <!-- Verifikasi Dokumen -->
                        <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                            <i class="fas fa-file-alt mr-2"></i>Verifikasi Dokumen
                        </h3>

                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-6">
                            <h4 class="font-bold text-yellow-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>Catatan Penting
                            </h4>
                            <p class="text-sm text-gray-700">
                                Sebagai Operator, Anda hanya bisa <strong>Approve</strong> dokumen yang sudah lengkap.
                                Untuk <strong>Reject</strong> atau <strong>Revision</strong>, hubungi Super Admin.
                            </p>
                        </div>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                            <ol class="space-y-3 text-sm">
                                <li><strong>1.</strong> SPMB → Pilih pendaftar status "paid"</li>
                                <li><strong>2.</strong> Klik "Verifikasi Dokumen"</li>
                                <li><strong>3.</strong> Review dokumen: Foto, KTP, Ijazah</li>
                                <li><strong>4.</strong> Jika lengkap → Klik "Approve"</li>
                                <li><strong>5.</strong> Jika tidak lengkap → Hubungi Admin</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Pembayaran -->
                    <div x-show="activeTab === 'pembayaran'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-yellow-400">
                            <i class="fas fa-money-bill mr-3"></i>Pembayaran SPP
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Anda bertanggung jawab memverifikasi pembayaran SPP mahasiswa. Pastikan bukti transfer valid sebelum approve.
                        </p>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <h3 class="font-bold text-gray-800 mb-4">Langkah Verifikasi SPP:</h3>
                            <ol class="space-y-4">
                                <li class="flex items-start">
                                    <span class="w-8 h-8 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3 flex-shrink-0">1</span>
                                    <div>
                                        <p><strong>Akses Pembayaran</strong></p>
                                        <p class="text-sm text-gray-600">Sidebar → Pembayaran → Filter "Pending"</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-8 h-8 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3 flex-shrink-0">2</span>
                                    <div>
                                        <p><strong>Lihat Detail</strong></p>
                                        <p class="text-sm text-gray-600">Klik mahasiswa → Lihat bukti transfer</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-8 h-8 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3 flex-shrink-0">3</span>
                                    <div>
                                        <p><strong>Validasi Pembayaran</strong></p>
                                        <ul class="text-sm text-gray-600 ml-4 list-disc mt-1">
                                            <li>Cek nominal (sesuai tagihan)</li>
                                            <li>Cek tanggal transfer</li>
                                            <li>Cek rekening tujuan</li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="w-8 h-8 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3 flex-shrink-0">4</span>
                                    <div>
                                        <p><strong>Approve atau Reject</strong></p>
                                        <div class="flex space-x-2 mt-2">
                                            <button class="px-4 py-2 bg-green-500 text-white rounded text-sm">✓ Verifikasi</button>
                                            <button class="px-4 py-2 bg-red-500 text-white rounded text-sm">✗ Tolak</button>
                                        </div>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <!-- Export Laporan -->
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
                            <h4 class="font-bold text-gray-800 mb-3">
                                <i class="fas fa-download text-blue-600 mr-2"></i>Export Laporan Keuangan
                            </h4>
                            <p class="text-sm text-gray-700 mb-3">
                                Anda dapat export laporan keuangan berdasarkan periode untuk keperluan administrasi.
                            </p>
                            <button class="px-4 py-2 bg-[#2D5F3F] text-white rounded hover:bg-[#4A7C59] transition">
                                <i class="fas fa-file-excel mr-2"></i>Export to Excel
                            </button>
                        </div>
                    </div>

                    <!-- Pengumuman -->
                    <div x-show="activeTab === 'pengumuman'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-yellow-400">
                            <i class="fas fa-bullhorn mr-3"></i>Pengumuman
                        </h2>

                        <p class="text-gray-700 mb-6">
                            Anda dapat membuat dan mengelola pengumuman untuk mahasiswa dan dosen.
                        </p>

                        <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                            <h3 class="font-bold text-gray-800 mb-4">Membuat Pengumuman:</h3>
                            <ol class="space-y-3">
                                <li class="flex items-start">
                                    <span class="text-[#2D5F3F] font-bold mr-2">1.</span>
                                    <div>Sidebar → Pengumuman → Tambah Pengumuman</div>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#2D5F3F] font-bold mr-2">2.</span>
                                    <div>Isi Judul dan Konten pengumuman</div>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#2D5F3F] font-bold mr-2">3.</span>
                                    <div>Pilih Target: Mahasiswa / Dosen / All</div>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#2D5F3F] font-bold mr-2">4.</span>
                                    <div>(Optional) Upload lampiran</div>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#2D5F3F] font-bold mr-2">5.</span>
                                    <div>Klik <strong>Publish</strong></div>
                                </li>
                            </ol>
                        </div>

                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                            <h4 class="font-bold text-gray-800 mb-2">
                                <i class="fas fa-info-circle text-yellow-600 mr-2"></i>Catatan
                            </h4>
                            <ul class="text-sm text-gray-700 space-y-1 list-disc ml-4">
                                <li>Anda hanya bisa edit/delete pengumuman yang Anda buat sendiri</li>
                                <li>Pengumuman dari Admin tidak bisa Anda edit</li>
                                <li>Fitur "Pin" hanya tersedia untuk Admin</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div x-show="activeTab === 'tips'" x-transition>
                        <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-yellow-400">
                            <i class="fas fa-lightbulb mr-3"></i>Tips & Best Practices
                        </h2>

                        <div class="space-y-4">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-5 rounded">
                                <h4 class="font-bold text-blue-800 mb-2">💡 Cek Pembayaran Pending Setiap Hari</h4>
                                <p class="text-sm text-gray-700">Verifikasi pembayaran dalam 1x24 jam agar mahasiswa tidak menunggu lama</p>
                            </div>

                            <div class="bg-green-50 border-l-4 border-green-500 p-5 rounded">
                                <h4 class="font-bold text-green-800 mb-2">💡 Jangan Ragu Berkonsultasi</h4>
                                <p class="text-sm text-gray-700">Jika ada keraguan tentang keabsahan bukti transfer, konsultasi dengan Admin</p>
                            </div>

                            <div class="bg-purple-50 border-l-4 border-purple-500 p-5 rounded">
                                <h4 class="font-bold text-purple-800 mb-2">💡 Beri Alasan Jelas saat Reject</h4>
                                <p class="text-sm text-gray-700">Saat menolak pembayaran, selalu beri alasan yang jelas agar mahasiswa tahu apa yang harus diperbaiki</p>
                            </div>

                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-5 rounded">
                                <h4 class="font-bold text-yellow-800 mb-2">💡 Gunakan Filter & Search</h4>
                                <p class="text-sm text-gray-700">Manfaatkan fitur filter dan search untuk menemukan data dengan cepat</p>
                            </div>

                            <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded">
                                <h4 class="font-bold text-red-800 mb-2">💡 Backup Data Pembayaran</h4>
                                <p class="text-sm text-gray-700">Export laporan pembayaran setiap bulan sebagai backup dan arsip</p>
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
                Butuh bantuan? Hubungi <a href="mailto:support@stai-alfatih.ac.id" class="underline hover:text-yellow-300">support@stai-alfatih.ac.id</a>
            </p>
            <p class="text-xs mt-2 opacity-75">
                Dibuat dengan ❤️ menggunakan Laravel & Claude Code
            </p>
        </div>
    </div>
</div>
@endsection
