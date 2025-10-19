@extends('layouts.admin')

@section('content')
<div class="space-y-6" x-data="{
    activeTab: 'overview',
    searchTerm: '',
    menuOpen: true
}">
    <!-- Page Header -->
    <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-xl p-8 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-2 flex items-center">
                    <i class="fas fa-book mr-4"></i>
                    Dokumentasi Super Admin
                </h1>
                <p class="text-lg text-gray-100">
                    Panduan lengkap menggunakan SIAKAD STAI AL-FATIH sebagai Super Admin
                </p>
            </div>
            <div class="hidden lg:block">
                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4 text-center">
                    <i class="fas fa-crown text-5xl text-[#D4AF37]"></i>
                    <p class="mt-2 font-semibold">Full Access</p>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mt-6">
            <div class="relative">
                <input
                    type="text"
                    x-model="searchTerm"
                    placeholder="Cari tutorial, fitur, atau panduan..."
                    class="w-full px-6 py-3 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#D4AF37]"
                >
                <i class="fas fa-search absolute right-4 top-4 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] sticky top-4">
                <!-- Toggle Button for Mobile -->
                <button
                    @click="menuOpen = !menuOpen"
                    class="lg:hidden w-full px-4 py-3 bg-[#2D5F3F] text-white rounded-t-lg font-semibold flex items-center justify-between"
                >
                    <span>Menu Dokumentasi</span>
                    <i class="fas" :class="menuOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>

                <nav class="p-4" x-show="menuOpen" x-transition>
                    <h3 class="font-bold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-list mr-2"></i>
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
                            <a @click="activeTab = 'users'"
                               :class="activeTab === 'users' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'"
                               class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                <i class="fas fa-users mr-2"></i>User Management
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
                            <a @click="activeTab = 'pengurus'"
                               :class="activeTab === 'pengurus' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'"
                               class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                <i class="fas fa-user-tie mr-2"></i>Pengurus
                            </a>
                        </li>
                        <li>
                            <a @click="activeTab = 'master'"
                               :class="activeTab === 'master' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'"
                               class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                <i class="fas fa-database mr-2"></i>Master Data
                            </a>
                        </li>
                        <li>
                            <a @click="activeTab = 'jadwal'"
                               :class="activeTab === 'jadwal' ? 'bg-[#2D5F3F] text-white' : 'text-gray-700 hover:bg-gray-100'"
                               class="block px-4 py-2 rounded-lg cursor-pointer transition">
                                <i class="fas fa-calendar mr-2"></i>Jadwal Kuliah
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
                                <i class="fas fa-lightbulb mr-2"></i>Tips & Tricks
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] p-8">

                <!-- Overview Section -->
                <div x-show="activeTab === 'overview'" x-transition class="prose max-w-none">
                    <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-home mr-3"></i>Overview - Super Admin
                    </h2>

                    <!-- Welcome Message -->
                    <div class="bg-gradient-to-r from-[#2D5F3F]/10 to-[#4A7C59]/10 rounded-lg p-6 mb-8">
                        <h3 class="text-2xl font-bold text-[#2D5F3F] mb-3">
                            Selamat Datang, Super Admin! 👑
                        </h3>
                        <p class="text-gray-700 leading-relaxed">
                            Sebagai <strong>Super Admin</strong>, Anda memiliki <strong class="text-[#2D5F3F]">akses penuh</strong> ke seluruh sistem SIAKAD STAI AL-FATIH.
                            Anda dapat mengelola user, master data, SPMB, pembayaran, dan semua aspek akademik lainnya.
                        </p>
                    </div>

                    <!-- Key Responsibilities -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-tasks mr-2"></i>Tanggung Jawab Utama
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <div class="bg-green-50 border-l-4 border-[#2D5F3F] p-4 rounded">
                            <h4 class="font-bold text-[#2D5F3F] mb-2">✅ User Management</h4>
                            <p class="text-sm text-gray-600">Mengelola seluruh pengguna sistem (Admin, Operator, Dosen, Mahasiswa)</p>
                        </div>
                        <div class="bg-green-50 border-l-4 border-[#2D5F3F] p-4 rounded">
                            <h4 class="font-bold text-[#2D5F3F] mb-2">✅ SPMB Management</h4>
                            <p class="text-sm text-gray-600">Mengelola seluruh proses penerimaan mahasiswa baru</p>
                        </div>
                        <div class="bg-green-50 border-l-4 border-[#2D5F3F] p-4 rounded">
                            <h4 class="font-bold text-[#2D5F3F] mb-2">✅ Master Data</h4>
                            <p class="text-sm text-gray-600">Mengelola Program Studi, Mata Kuliah, Kurikulum, Semester</p>
                        </div>
                        <div class="bg-green-50 border-l-4 border-[#2D5F3F] p-4 rounded">
                            <h4 class="font-bold text-[#2D5F3F] mb-2">✅ Pengurus</h4>
                            <p class="text-sm text-gray-600">Assign Ketua Prodi dan Dosen Wali</p>
                        </div>
                        <div class="bg-green-50 border-l-4 border-[#2D5F3F] p-4 rounded">
                            <h4 class="font-bold text-[#2D5F3F] mb-2">✅ Jadwal & Nilai</h4>
                            <p class="text-sm text-gray-600">Monitor dan kelola jadwal kuliah serta nilai mahasiswa</p>
                        </div>
                        <div class="bg-green-50 border-l-4 border-[#2D5F3F] p-4 rounded">
                            <h4 class="font-bold text-[#2D5F3F] mb-2">✅ Pembayaran</h4>
                            <p class="text-sm text-gray-600">Verifikasi dan kelola semua pembayaran mahasiswa</p>
                        </div>
                    </div>

                    <!-- Quick Start Guide -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-rocket mr-2"></i>Quick Start Guide
                    </h3>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h4 class="font-bold text-gray-800 mb-3">Langkah Awal Menggunakan Sistem:</h4>
                        <ol class="space-y-3">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3">1</span>
                                <div>
                                    <strong>Setup Master Data</strong>
                                    <p class="text-sm text-gray-600">Mulai dengan membuat Program Studi, Mata Kuliah, dan Kurikulum</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3">2</span>
                                <div>
                                    <strong>Create Users</strong>
                                    <p class="text-sm text-gray-600">Buat akun untuk Operator, Dosen, dan Mahasiswa</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3">3</span>
                                <div>
                                    <strong>Assign Pengurus</strong>
                                    <p class="text-sm text-gray-600">Tentukan Ketua Prodi dan assign Dosen Wali untuk mahasiswa</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3">4</span>
                                <div>
                                    <strong>Setup SPMB</strong>
                                    <p class="text-sm text-gray-600">Konfigurasi sistem penerimaan mahasiswa baru (NIM Range, biaya, dll)</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-8 h-8 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-3">5</span>
                                <div>
                                    <strong>Monitor & Maintain</strong>
                                    <p class="text-sm text-gray-600">Pantau aktivitas sistem dan lakukan maintenance rutin</p>
                                </div>
                            </li>
                        </ol>
                    </div>

                    <!-- Important Links -->
                    <div class="bg-yellow-50 border-l-4 border-[#D4AF37] p-6 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-star text-[#D4AF37] mr-2"></i>
                            Menu Penting
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                <i class="fas fa-users mr-2"></i>User Management
                            </a>
                            <a href="{{ route('admin.spmb.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                <i class="fas fa-user-graduate mr-2"></i>SPMB
                            </a>
                            <a href="{{ route('admin.pengurus.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                <i class="fas fa-user-tie mr-2"></i>Pengurus
                            </a>
                            <a href="{{ route('admin.program-studi.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                <i class="fas fa-university mr-2"></i>Program Studi
                            </a>
                            <a href="{{ route('admin.pembayaran.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                <i class="fas fa-money-bill mr-2"></i>Pembayaran
                            </a>
                            <a href="{{ route('admin.pengumuman.index') }}" class="flex items-center text-[#2D5F3F] hover:underline">
                                <i class="fas fa-bullhorn mr-2"></i>Pengumuman
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Management Section -->
                <div x-show="activeTab === 'users'" x-transition class="prose max-w-none">
                    <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-users mr-3"></i>User Management
                    </h2>

                    <!-- Introduction -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
                        <h3 class="font-bold text-gray-800 mb-2">
                            <i class="fas fa-info-circle mr-2"></i>Tentang User Management
                        </h3>
                        <p class="text-gray-700">
                            Di sini Anda dapat mengelola seluruh pengguna sistem termasuk <strong>Super Admin</strong>, <strong>Operator</strong>, <strong>Dosen</strong>, dan <strong>Mahasiswa</strong>.
                            Setiap role memiliki hak akses yang berbeda.
                        </p>
                    </div>

                    <!-- Create User Flow -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-plus-circle mr-2"></i>Cara Membuat User Baru
                    </h3>

                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                        <h4 class="font-bold text-gray-800 mb-4">Step-by-Step:</h4>

                        <div class="space-y-4">
                            <!-- Step 1 -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">1</div>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Akses Menu Users</h5>
                                    <p class="text-sm text-gray-600 mb-2">Klik menu <strong>Users</strong> di sidebar → Klik tombol <strong class="text-[#2D5F3F]">+ Tambah User</strong></p>
                                    <div class="bg-gray-100 p-3 rounded text-sm">
                                        <code>Sidebar → Users → Tambah User</code>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">2</div>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Isi Data Dasar</h5>
                                    <p class="text-sm text-gray-600 mb-2">Lengkapi form berikut:</p>
                                    <ul class="text-sm text-gray-600 space-y-1 ml-4 list-disc">
                                        <li><strong>Username</strong>: Username untuk login (unik)</li>
                                        <li><strong>Nama Lengkap</strong>: Nama lengkap user</li>
                                        <li><strong>Email</strong>: Alamat email (unik)</li>
                                        <li><strong>Nomor HP</strong>: Nomor handphone</li>
                                        <li><strong>Password</strong>: Password default untuk login</li>
                                        <li><strong>Role</strong>: Pilih role (Super Admin, Operator, Dosen, Mahasiswa)</li>
                                        <li><strong>Status</strong>: Toggle Active/Inactive</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">3</div>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Isi Data Role-Specific</h5>
                                    <p class="text-sm text-gray-600 mb-2">Bergantung pada role yang dipilih, form tambahan akan muncul:</p>

                                    <div class="space-y-3 mt-3">
                                        <div class="bg-green-50 p-3 rounded">
                                            <p class="font-semibold text-[#2D5F3F] text-sm">👨‍🎓 Mahasiswa:</p>
                                            <ul class="text-xs text-gray-600 ml-4 list-disc mt-1">
                                                <li>NIM</li>
                                                <li>Program Studi</li>
                                                <li>Angkatan</li>
                                            </ul>
                                        </div>

                                        <div class="bg-blue-50 p-3 rounded">
                                            <p class="font-semibold text-blue-700 text-sm">👨‍🏫 Dosen:</p>
                                            <ul class="text-xs text-gray-600 ml-4 list-disc mt-1">
                                                <li>NIDN</li>
                                                <li>Gelar (S.Pd.I, M.Pd, dll)</li>
                                            </ul>
                                        </div>

                                        <div class="bg-yellow-50 p-3 rounded">
                                            <p class="font-semibold text-yellow-700 text-sm">👔 Operator:</p>
                                            <ul class="text-xs text-gray-600 ml-4 list-disc mt-1">
                                                <li>Employee ID</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">4</div>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Simpan</h5>
                                    <p class="text-sm text-gray-600 mb-2">Klik tombol <strong class="text-[#2D5F3F]">Simpan</strong> untuk membuat user baru</p>
                                    <div class="bg-green-100 border-l-4 border-green-500 p-3 rounded text-sm">
                                        <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                        User berhasil dibuat! User dapat login dengan username dan password yang telah ditentukan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Edit User -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-edit mr-2"></i>Edit User
                    </h3>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <ol class="space-y-3">
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">1.</span>
                                <div>Akses menu <strong>Users</strong> → Cari user yang ingin di-edit</div>
                            </li>
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">2.</span>
                                <div>Klik tombol <strong class="text-blue-600">Edit</strong> di kolom Actions</div>
                            </li>
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">3.</span>
                                <div>Ubah data yang diperlukan (termasuk role jika perlu)</div>
                            </li>
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">4.</span>
                                <div><strong>Password</strong>: Kosongkan jika tidak ingin mengubah password</div>
                            </li>
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">5.</span>
                                <div>Klik <strong class="text-[#2D5F3F]">Update</strong> untuk menyimpan perubahan</div>
                            </li>
                        </ol>
                    </div>

                    <!-- Important Notes -->
                    <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded-lg">
                        <h4 class="font-bold text-red-700 mb-3 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Catatan Penting
                        </h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>⚠️ Username dan Email harus <strong>unik</strong>, tidak boleh sama dengan user lain</li>
                            <li>⚠️ Password default sebaiknya diganti oleh user setelah login pertama kali</li>
                            <li>⚠️ Jika mengubah role, pastikan data role-specific sudah benar</li>
                            <li>⚠️ User yang di-delete akan di-soft delete (masih bisa di-restore)</li>
                        </ul>
                    </div>
                </div>

                <!-- SPMB Section -->
                <div x-show="activeTab === 'spmb'" x-transition class="prose max-w-none">
                    <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-user-graduate mr-3"></i>SPMB (Seleksi Penerimaan Mahasiswa Baru)
                    </h2>

                    <!-- Flow Diagram -->
                    <div class="bg-gradient-to-r from-blue-50 to-green-50 rounded-lg p-6 mb-6">
                        <h3 class="font-bold text-gray-800 mb-4 text-center text-xl">
                            <i class="fas fa-project-diagram mr-2"></i>Alur SPMB Lengkap
                        </h3>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-48 bg-white rounded-lg p-3 shadow text-center border-2 border-blue-300">
                                    <p class="text-sm font-semibold text-blue-700">Pendaftaran Online</p>
                                    <p class="text-xs text-gray-500">Status: pending</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-4"></i>
                                <div class="flex-shrink-0 w-48 bg-white rounded-lg p-3 shadow text-center border-2 border-yellow-300">
                                    <p class="text-sm font-semibold text-yellow-700">Upload Bukti Bayar</p>
                                    <p class="text-xs text-gray-500">Status: pending</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-48 bg-white rounded-lg p-3 shadow text-center border-2 border-orange-300">
                                    <p class="text-sm font-semibold text-orange-700">Verifikasi Bayar</p>
                                    <p class="text-xs text-gray-500">Status: paid</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-4"></i>
                                <div class="flex-shrink-0 w-48 bg-white rounded-lg p-3 shadow text-center border-2 border-purple-300">
                                    <p class="text-sm font-semibold text-purple-700">Verifikasi Dokumen</p>
                                    <p class="text-xs text-gray-500">Status: verified</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-48 bg-white rounded-lg p-3 shadow text-center border-2 border-green-300">
                                    <p class="text-sm font-semibold text-green-700">Seleksi</p>
                                    <p class="text-xs text-gray-500">Status: accepted/rejected</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-4"></i>
                                <div class="flex-shrink-0 w-48 bg-white rounded-lg p-3 shadow text-center border-2 border-teal-300">
                                    <p class="text-sm font-semibold text-teal-700">Daftar Ulang</p>
                                    <p class="text-xs text-gray-500">Upload bukti bayar</p>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-48 bg-white rounded-lg p-3 shadow text-center border-2 border-indigo-300">
                                    <p class="text-sm font-semibold text-indigo-700">Verifikasi Daftar Ulang</p>
                                    <p class="text-xs text-gray-500">Approve daftar ulang</p>
                                </div>
                                <i class="fas fa-arrow-right text-gray-400 mx-4"></i>
                                <div class="flex-shrink-0 w-48 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white rounded-lg p-3 shadow text-center">
                                    <p class="text-sm font-semibold">Aktivasi Mahasiswa</p>
                                    <p class="text-xs">Status: completed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tutorial Verifikasi -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-check-double mr-2"></i>Tutorial Verifikasi Pembayaran
                    </h3>

                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                        <ol class="space-y-4">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">1</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Akses SPMB</h5>
                                    <p class="text-sm text-gray-600">Sidebar → SPMB → Tab "Pembayaran Pending"</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">2</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Lihat Bukti Transfer</h5>
                                    <p class="text-sm text-gray-600">Klik tombol "Lihat Bukti" untuk melihat bukti transfer yang diupload pendaftar</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">3</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Cek Keabsahan</h5>
                                    <p class="text-sm text-gray-600">Pastikan bukti transfer valid (nominal, tanggal, rekening tujuan sesuai)</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">4</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Verifikasi</h5>
                                    <p class="text-sm text-gray-600 mb-2">Klik tombol:</p>
                                    <div class="flex space-x-2">
                                        <button class="px-3 py-1 bg-green-500 text-white rounded text-xs">✓ Verifikasi</button>
                                        <span class="text-gray-600">atau</span>
                                        <button class="px-3 py-1 bg-red-500 text-white rounded text-xs">✗ Tolak</button>
                                    </div>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">5</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Status Otomatis Update</h5>
                                    <p class="text-sm text-gray-600">Status pembayaran berubah menjadi "verified", status pendaftaran menjadi "paid"</p>
                                </div>
                            </li>
                        </ol>
                    </div>

                    <!-- Tutorial Aktivasi Mahasiswa -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-user-check mr-2"></i>Tutorial Aktivasi Mahasiswa
                    </h3>

                    <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-6 mb-6">
                        <h4 class="font-bold text-gray-800 mb-3">
                            <i class="fas fa-magic mr-2"></i>Proses Otomatis Saat Aktivasi:
                        </h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>✅ Generate NIM otomatis (format: YYYYPPSSSS)</li>
                            <li>✅ Create User account (role: mahasiswa, username: NIM)</li>
                            <li>✅ Create record Mahasiswa</li>
                            <li>✅ Set password default: "password"</li>
                            <li>✅ Status pendaftaran: "completed"</li>
                            <li>✅ Email notifikasi terkirim ke mahasiswa</li>
                        </ul>

                        <div class="mt-4 p-4 bg-white rounded border-l-4 border-green-500">
                            <p class="text-sm"><strong>Langkah:</strong></p>
                            <ol class="text-sm text-gray-600 mt-2 space-y-1">
                                <li>1. SPMB → Pilih pendaftar dengan status daftar ulang "verified"</li>
                                <li>2. Klik tombol <strong class="text-[#2D5F3F]">"Aktivasi sebagai Mahasiswa"</strong></li>
                                <li>3. Konfirmasi → Sistem otomatis proses semua</li>
                                <li>4. Selesai! Mahasiswa bisa login dengan NIM</li>
                            </ol>
                        </div>
                    </div>

                    <!-- NIM Range Configuration -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-cog mr-2"></i>Konfigurasi NIM Range
                    </h3>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3">Format NIM: YYYYPPSSSS</h4>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li><strong>YYYY</strong>: Tahun angkatan (contoh: 2024)</li>
                            <li><strong>PP</strong>: Kode program studi (01, 02, 03)</li>
                            <li><strong>SSSS</strong>: Nomor urut (0001, 0002, ...)</li>
                        </ul>

                        <div class="mt-4 p-4 bg-white rounded">
                            <p class="text-sm font-semibold">Contoh:</p>
                            <code class="text-lg text-[#2D5F3F] font-bold">202401010001</code>
                            <p class="text-xs text-gray-500 mt-1">
                                → Tahun 2024, Program Studi 01 (PAI), Nomor urut 0001
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Pengurus Section -->
                <div x-show="activeTab === 'pengurus'" x-transition class="prose max-w-none">
                    <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-user-tie mr-3"></i>Pengurus (Ketua Prodi & Dosen Wali)
                    </h2>

                    <p class="text-gray-700 mb-6">
                        Kelola struktur pengurus akademik termasuk penunjukan Ketua Program Studi dan assignment Dosen Wali untuk mahasiswa.
                    </p>

                    <!-- Ketua Prodi -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-crown mr-2"></i>Assign Ketua Program Studi
                    </h3>

                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                        <h4 class="font-bold text-gray-800 mb-4">Langkah-langkah:</h4>
                        <ol class="space-y-3">
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">1.</span>
                                <div>Akses menu <strong>Pengurus</strong> → Tab <strong>"Ketua Prodi"</strong></div>
                            </li>
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">2.</span>
                                <div>Lihat daftar Program Studi (ditampilkan dalam cards)</div>
                            </li>
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">3.</span>
                                <div>Pada prodi yang ingin di-assign, klik tombol <strong class="text-green-600">"Assign Ketua Prodi"</strong></div>
                            </li>
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">4.</span>
                                <div>Pilih dosen dari dropdown</div>
                            </li>
                            <li class="flex items-start">
                                <span class="text-[#2D5F3F] font-bold mr-2">5.</span>
                                <div>Klik <strong class="text-[#2D5F3F]">"Simpan"</strong></div>
                            </li>
                        </ol>

                        <div class="mt-4 bg-blue-50 p-4 rounded">
                            <p class="text-sm"><strong>Catatan:</strong></p>
                            <ul class="text-sm text-gray-600 mt-2 space-y-1 list-disc ml-4">
                                <li>Satu prodi hanya bisa memiliki 1 ketua prodi</li>
                                <li>Satu dosen bisa menjadi ketua prodi di beberapa prodi</li>
                                <li>Bisa remove dan ganti ketua prodi kapan saja</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Dosen Wali -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-user-friends mr-2"></i>Assign Dosen Wali
                    </h3>

                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-lg p-6 mb-6">
                        <h4 class="font-bold text-gray-800 mb-3">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>2 Cara Assignment:
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Bulk Assignment -->
                            <div class="bg-white p-4 rounded-lg border-2 border-purple-300">
                                <h5 class="font-bold text-purple-700 mb-2">
                                    <i class="fas fa-users mr-2"></i>Bulk Assignment
                                </h5>
                                <p class="text-sm text-gray-600 mb-3">Assign untuk seluruh mahasiswa di 1 prodi sekaligus</p>
                                <ol class="text-xs text-gray-700 space-y-1">
                                    <li>1. Menu Pengurus → Dosen Wali</li>
                                    <li>2. Form "Bulk Assignment"</li>
                                    <li>3. Pilih Program Studi</li>
                                    <li>4. Pilih Dosen Wali</li>
                                    <li>5. Submit</li>
                                </ol>
                            </div>

                            <!-- Individual Assignment -->
                            <div class="bg-white p-4 rounded-lg border-2 border-pink-300">
                                <h5 class="font-bold text-pink-700 mb-2">
                                    <i class="fas fa-user mr-2"></i>Individual Assignment
                                </h5>
                                <p class="text-sm text-gray-600 mb-3">Assign per mahasiswa satu-satu</p>
                                <ol class="text-xs text-gray-700 space-y-1">
                                    <li>1. Menu Pengurus → Dosen Wali</li>
                                    <li>2. Cari mahasiswa di table</li>
                                    <li>3. Klik "Assign Wali"</li>
                                    <li>4. Pilih dosen</li>
                                    <li>5. Simpan</li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    <!-- Best Practices -->
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-star text-green-600 mr-2"></i>
                            Best Practices
                        </h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>✅ Assign dosen wali di awal semester untuk mahasiswa baru</li>
                            <li>✅ Gunakan bulk assignment untuk efisiensi</li>
                            <li>✅ Pastikan beban dosen wali seimbang (tidak terlalu banyak mahasiswa di satu dosen)</li>
                            <li>✅ Update jika ada dosen yang resign atau cuti</li>
                        </ul>
                    </div>
                </div>

                <!-- Master Data Section -->
                <div x-show="activeTab === 'master'" x-transition class="prose max-w-none">
                    <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-database mr-3"></i>Master Data
                    </h2>

                    <p class="text-gray-700 mb-6">
                        Master Data adalah fondasi sistem akademik. Pastikan data ini lengkap dan akurat sebelum operasional sistem dimulai.
                    </p>

                    <!-- Quick Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-gradient-to-br from-blue-100 to-blue-50 p-4 rounded-lg border-2 border-blue-300">
                            <h4 class="font-bold text-blue-700 mb-2">
                                <i class="fas fa-university mr-2"></i>Program Studi
                            </h4>
                            <p class="text-xs text-gray-600">Kelola program studi yang tersedia di kampus</p>
                        </div>

                        <div class="bg-gradient-to-br from-green-100 to-green-50 p-4 rounded-lg border-2 border-green-300">
                            <h4 class="font-bold text-green-700 mb-2">
                                <i class="fas fa-book mr-2"></i>Mata Kuliah
                            </h4>
                            <p class="text-xs text-gray-600">Kelola mata kuliah per program studi</p>
                        </div>

                        <div class="bg-gradient-to-br from-purple-100 to-purple-50 p-4 rounded-lg border-2 border-purple-300">
                            <h4 class="font-bold text-purple-700 mb-2">
                                <i class="fas fa-list mr-2"></i>Kurikulum
                            </h4>
                            <p class="text-xs text-gray-600">Atur kurikulum dan mata kuliah per semester</p>
                        </div>
                    </div>

                    <!-- Create Program Studi -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-university mr-2"></i>Membuat Program Studi
                    </h3>

                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                        <ol class="space-y-4">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">1</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Akses Menu</h5>
                                    <p class="text-sm text-gray-600">Sidebar → Program Studi → Tambah Program Studi</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">2</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Isi Form</h5>
                                    <ul class="text-sm text-gray-600 space-y-1 mt-2 ml-4 list-disc">
                                        <li><strong>Kode Prodi</strong>: Contoh: PAI-01, ES-02</li>
                                        <li><strong>Nama Prodi</strong>: Contoh: Pendidikan Agama Islam</li>
                                        <li><strong>Jenjang</strong>: S1, S2, S3</li>
                                        <li><strong>Akreditasi</strong>: A, B, C, Unggul</li>
                                        <li><strong>Ketua Prodi</strong>: (Optional, bisa di-assign nanti)</li>
                                    </ul>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">3</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Simpan</h5>
                                    <p class="text-sm text-gray-600">Klik tombol <strong class="text-[#2D5F3F]">Simpan</strong></p>
                                </div>
                            </li>
                        </ol>
                    </div>

                    <!-- Create Mata Kuliah -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-book mr-2"></i>Membuat Mata Kuliah
                    </h3>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <p class="text-sm text-gray-700 mb-4">
                            Mata kuliah terkait dengan program studi tertentu. Setiap MK memiliki kode unik, SKS, dan jenis (Wajib/Pilihan).
                        </p>

                        <div class="bg-white border-2 border-gray-300 rounded p-4">
                            <h5 class="font-bold text-gray-800 mb-3">Field yang perlu diisi:</h5>
                            <table class="w-full text-sm">
                                <tr class="border-b">
                                    <td class="py-2 font-semibold">Kode MK</td>
                                    <td class="py-2 text-gray-600">Contoh: PAI101, ES201</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-semibold">Nama MK</td>
                                    <td class="py-2 text-gray-600">Contoh: Aqidah Akhlak</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-semibold">SKS</td>
                                    <td class="py-2 text-gray-600">1, 2, 3, 4, dst</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-semibold">Semester</td>
                                    <td class="py-2 text-gray-600">1, 2, 3, ... 8</td>
                                </tr>
                                <tr class="border-b">
                                    <td class="py-2 font-semibold">Jenis</td>
                                    <td class="py-2 text-gray-600">Wajib / Pilihan</td>
                                </tr>
                                <tr>
                                    <td class="py-2 font-semibold">Program Studi</td>
                                    <td class="py-2 text-gray-600">Pilih dari dropdown</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Setup Kurikulum -->
                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-list mr-2"></i>Setup Kurikulum
                    </h3>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
                        <h4 class="font-bold text-gray-800 mb-3">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>Apa itu Kurikulum?
                        </h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Kurikulum adalah kumpulan mata kuliah yang harus ditempuh mahasiswa dalam 1 program studi,
                            tersusun per semester dengan total SKS tertentu.
                        </p>

                        <div class="bg-white p-4 rounded mt-3">
                            <p class="font-semibold text-sm mb-2">Langkah Setup:</p>
                            <ol class="text-sm text-gray-700 space-y-1">
                                <li>1. Buat Kurikulum baru (contoh: "Kurikulum 2020")</li>
                                <li>2. Set Program Studi dan Tahun Berlaku</li>
                                <li>3. Assign mata kuliah ke kurikulum</li>
                                <li>4. Tentukan semester untuk setiap mata kuliah</li>
                                <li>5. Total SKS akan dihitung otomatis</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Tips -->
                    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            Tips Setup Master Data
                        </h4>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li>💡 Setup urutan: <strong>Program Studi</strong> → <strong>Mata Kuliah</strong> → <strong>Kurikulum</strong></li>
                            <li>💡 Pastikan kode prodi dan kode MK unik dan konsisten</li>
                            <li>💡 Gunakan soft delete, jangan hapus permanen jika masih ada relasi</li>
                            <li>💡 Export data master secara berkala untuk backup</li>
                        </ul>
                    </div>
                </div>

                <!-- Jadwal Section -->
                <div x-show="activeTab === 'jadwal'" x-transition class="prose max-w-none">
                    <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-calendar mr-3"></i>Jadwal Kuliah
                    </h2>

                    <p class="text-gray-700 mb-6">
                        Kelola jadwal kuliah per semester dengan memperhatikan bentrok dosen, ruangan, dan waktu.
                    </p>

                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-6 rounded-lg mb-6">
                        <h4 class="font-bold text-gray-800 mb-2">
                            <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>Hal yang Perlu Diperhatikan
                        </h4>
                        <ul class="text-sm text-gray-700 space-y-1 list-disc ml-4">
                            <li>Pastikan tidak ada bentrok jadwal dosen (dosen mengajar 2 kelas di waktu yang sama)</li>
                            <li>Pastikan tidak ada bentrok ruangan (1 ruangan terpakai 2 jadwal di waktu yang sama)</li>
                            <li>Set semester aktif sebelum membuat jadwal</li>
                            <li>Jadwal bisa di-export untuk disebarkan ke dosen/mahasiswa</li>
                        </ul>
                    </div>

                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-plus mr-2"></i>Membuat Jadwal Baru
                    </h3>

                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                        <ol class="space-y-4">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">1</span>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Akses Menu Jadwal</h5>
                                    <p class="text-sm text-gray-600">Sidebar → Jadwal → Tambah Jadwal</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">2</span>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Pilih Semester</h5>
                                    <p class="text-sm text-gray-600">Pilih semester aktif (contoh: Ganjil 2024/2025)</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">3</span>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Isi Detail Jadwal</h5>
                                    <ul class="text-sm text-gray-600 space-y-1 mt-2 ml-4 list-disc">
                                        <li><strong>Mata Kuliah</strong>: Pilih dari dropdown</li>
                                        <li><strong>Dosen Pengampu</strong>: Pilih dosen</li>
                                        <li><strong>Hari</strong>: Senin - Sabtu</li>
                                        <li><strong>Jam Mulai - Jam Selesai</strong>: Contoh: 08:00 - 10:00</li>
                                        <li><strong>Ruangan</strong>: Pilih ruangan tersedia</li>
                                        <li><strong>Kelas</strong>: (Optional, jika ada kelas paralel)</li>
                                    </ul>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">4</span>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Cek Bentrok</h5>
                                    <p class="text-sm text-gray-600 mb-2">Sistem otomatis mengecek bentrok saat simpan. Jika ada bentrok, akan muncul warning:</p>
                                    <div class="bg-red-100 border-l-4 border-red-500 p-3 rounded text-sm">
                                        <i class="fas fa-exclamation-circle text-red-600 mr-2"></i>
                                        <strong>Bentrok!</strong> Dosen/Ruangan sudah terpakai di waktu yang sama.
                                    </div>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">5</span>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Simpan</h5>
                                    <p class="text-sm text-gray-600">Jika tidak ada bentrok, klik <strong class="text-[#2D5F3F]">Simpan</strong></p>
                                </div>
                            </li>
                        </ol>
                    </div>

                    <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded-lg">
                        <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-download text-green-600 mr-2"></i>
                            Export Jadwal
                        </h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Jadwal bisa di-export dalam format PDF atau Excel untuk disebarkan ke dosen dan mahasiswa.
                        </p>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 bg-red-600 text-white rounded text-sm">
                                <i class="fas fa-file-pdf mr-2"></i>Export PDF
                            </button>
                            <button class="px-4 py-2 bg-green-600 text-white rounded text-sm">
                                <i class="fas fa-file-excel mr-2"></i>Export Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pembayaran Section -->
                <div x-show="activeTab === 'pembayaran'" x-transition class="prose max-w-none">
                    <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-money-bill mr-3"></i>Pembayaran
                    </h2>

                    <p class="text-gray-700 mb-6">
                        Kelola dan verifikasi pembayaran mahasiswa (SPP, biaya pendaftaran, daftar ulang, wisuda, dll).
                    </p>

                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-check-circle mr-2"></i>Verifikasi Pembayaran
                    </h3>

                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                        <ol class="space-y-4">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">1</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Akses Pembayaran Pending</h5>
                                    <p class="text-sm text-gray-600">Sidebar → Pembayaran → Filter status "Pending"</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">2</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Lihat Detail</h5>
                                    <p class="text-sm text-gray-600">Klik "Lihat Detail" untuk melihat bukti transfer dan informasi pembayaran</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">3</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Validasi</h5>
                                    <p class="text-sm text-gray-600 mb-2">Cek kesesuaian:</p>
                                    <ul class="text-sm text-gray-600 ml-4 list-disc space-y-1">
                                        <li>Nominal transfer sesuai tagihan</li>
                                        <li>Tanggal transfer valid</li>
                                        <li>Rekening tujuan benar</li>
                                        <li>Bukti transfer asli (bukan editan)</li>
                                    </ul>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">4</span>
                                <div>
                                    <h5 class="font-bold text-gray-800">Verifikasi atau Tolak</h5>
                                    <div class="flex space-x-2 mt-2">
                                        <button class="px-3 py-1 bg-green-500 text-white rounded text-sm">✓ Verifikasi</button>
                                        <button class="px-3 py-1 bg-red-500 text-white rounded text-sm">✗ Tolak</button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2">Jika tolak, beri alasan yang jelas</p>
                                </div>
                            </li>
                        </ol>
                    </div>

                    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg mb-6">
                        <h4 class="font-bold text-gray-800 mb-3">
                            <i class="fas fa-chart-bar text-blue-600 mr-2"></i>Laporan Keuangan
                        </h4>
                        <p class="text-sm text-gray-700 mb-3">
                            Sistem dapat generate laporan keuangan berdasarkan periode, program studi, atau jenis pembayaran.
                        </p>
                        <button class="px-4 py-2 bg-[#2D5F3F] text-white rounded hover:bg-[#4A7C59] transition">
                            <i class="fas fa-download mr-2"></i>Export Laporan
                        </button>
                    </div>
                </div>

                <!-- Pengumuman Section -->
                <div x-show="activeTab === 'pengumuman'" x-transition class="prose max-w-none">
                    <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-bullhorn mr-3"></i>Pengumuman
                    </h2>

                    <p class="text-gray-700 mb-6">
                        Buat dan kelola pengumuman untuk mahasiswa, dosen, operator, atau semua pengguna.
                    </p>

                    <h3 class="text-2xl font-bold text-[#2D5F3F] mb-4">
                        <i class="fas fa-plus mr-2"></i>Membuat Pengumuman
                    </h3>

                    <div class="bg-white border-2 border-gray-200 rounded-lg p-6 mb-6">
                        <ol class="space-y-4">
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">1</span>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Akses Menu</h5>
                                    <p class="text-sm text-gray-600">Sidebar → Pengumuman → Tambah Pengumuman</p>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">2</span>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Isi Form</h5>
                                    <ul class="text-sm text-gray-600 space-y-1 mt-2 ml-4 list-disc">
                                        <li><strong>Judul</strong>: Judul pengumuman yang menarik</li>
                                        <li><strong>Konten</strong>: Isi pengumuman (mendukung rich text)</li>
                                        <li><strong>Target Audience</strong>: Pilih penerima:
                                            <ul class="ml-4 list-circle mt-1">
                                                <li>All (Semua pengguna)</li>
                                                <li>Mahasiswa</li>
                                                <li>Dosen</li>
                                                <li>Operator</li>
                                            </ul>
                                        </li>
                                        <li><strong>Lampiran</strong>: (Optional) Upload file PDF/DOC</li>
                                        <li><strong>Publish</strong>: Publish Now / Schedule</li>
                                    </ul>
                                </div>
                            </li>
                            <li class="flex items-start">
                                <span class="flex-shrink-0 w-10 h-10 bg-[#2D5F3F] text-white rounded-full flex items-center justify-center font-bold mr-4">3</span>
                                <div class="flex-grow">
                                    <h5 class="font-bold text-gray-800">Publish</h5>
                                    <p class="text-sm text-gray-600">Klik <strong class="text-[#2D5F3F]">Publish</strong> untuk mempublikasikan pengumuman</p>
                                </div>
                            </li>
                        </ol>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                            <h4 class="font-bold text-gray-800 mb-2">
                                <i class="fas fa-thumbtack text-yellow-600 mr-2"></i>Pin Pengumuman
                            </h4>
                            <p class="text-sm text-gray-600">
                                Pengumuman penting bisa di-pin agar selalu tampil di atas dan lebih terlihat.
                            </p>
                        </div>

                        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
                            <h4 class="font-bold text-gray-800 mb-2">
                                <i class="fas fa-eye text-green-600 mr-2"></i>Statistik Views
                            </h4>
                            <p class="text-sm text-gray-600">
                                Lihat berapa banyak pengguna yang sudah membaca pengumuman Anda.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tips & Tricks Section -->
                <div x-show="activeTab === 'tips'" x-transition class="prose max-w-none">
                    <h2 class="text-3xl font-bold text-[#2D5F3F] mb-6 pb-3 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-lightbulb mr-3"></i>Tips & Tricks
                    </h2>

                    <div class="space-y-6">
                        <!-- Tip 1 -->
                        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-6 border-l-4 border-blue-500">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center text-xl">
                                <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">1</span>
                                Gunakan Filter & Search
                            </h3>
                            <p class="text-sm text-gray-700">
                                Hampir semua halaman memiliki fitur filter dan search. Gunakan untuk menemukan data dengan cepat tanpa scroll manual.
                            </p>
                        </div>

                        <!-- Tip 2 -->
                        <div class="bg-gradient-to-r from-green-50 to-teal-50 rounded-lg p-6 border-l-4 border-green-500">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center text-xl">
                                <span class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">2</span>
                                Export Data Secara Berkala
                            </h3>
                            <p class="text-sm text-gray-700">
                                Export master data (program studi, mata kuliah, mahasiswa, dll) ke Excel/PDF setiap bulan sebagai backup.
                            </p>
                        </div>

                        <!-- Tip 3 -->
                        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg p-6 border-l-4 border-yellow-500">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center text-xl">
                                <span class="bg-yellow-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">3</span>
                                Monitor Dashboard Harian
                            </h3>
                            <p class="text-sm text-gray-700">
                                Cek dashboard setiap hari untuk melihat statistik terkini, aktivitas pending, dan notifikasi penting.
                            </p>
                        </div>

                        <!-- Tip 4 -->
                        <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-lg p-6 border-l-4 border-red-500">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center text-xl">
                                <span class="bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">4</span>
                                Soft Delete, Jangan Hard Delete
                            </h3>
                            <p class="text-sm text-gray-700">
                                Sistem menggunakan soft delete. Data yang dihapus masih bisa di-restore. Jangan paksa hard delete kecuali sangat diperlukan.
                            </p>
                        </div>

                        <!-- Tip 5 -->
                        <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg p-6 border-l-4 border-indigo-500">
                            <h3 class="font-bold text-gray-800 mb-3 flex items-center text-xl">
                                <span class="bg-indigo-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-3 text-sm">5</span>
                                Set Semester Aktif dengan Benar
                            </h3>
                            <p class="text-sm text-gray-700">
                                Hanya boleh ada 1 semester aktif. Pastikan semester aktif sudah benar sebelum operasional akademik dimulai.
                            </p>
                        </div>

                        <!-- Shortcut Keys -->
                        <div class="bg-gray-900 text-white rounded-lg p-6">
                            <h3 class="font-bold mb-4 flex items-center text-xl">
                                <i class="fas fa-keyboard mr-3 text-[#D4AF37]"></i>
                                Keyboard Shortcuts (Coming Soon)
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                <div class="bg-gray-800 p-3 rounded">
                                    <kbd class="bg-gray-700 px-2 py-1 rounded">Ctrl + K</kbd>
                                    <span class="ml-2">Quick Search</span>
                                </div>
                                <div class="bg-gray-800 p-3 rounded">
                                    <kbd class="bg-gray-700 px-2 py-1 rounded">Ctrl + N</kbd>
                                    <span class="ml-2">New Item</span>
                                </div>
                                <div class="bg-gray-800 p-3 rounded">
                                    <kbd class="bg-gray-700 px-2 py-1 rounded">Ctrl + /</kbd>
                                    <span class="ml-2">Show Shortcuts</span>
                                </div>
                                <div class="bg-gray-800 p-3 rounded">
                                    <kbd class="bg-gray-700 px-2 py-1 rounded">Esc</kbd>
                                    <span class="ml-2">Close Modal</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] rounded-lg shadow-xl p-6 text-white text-center">
        <p class="text-sm">
            <i class="fas fa-question-circle mr-2"></i>
            Butuh bantuan? Hubungi <a href="mailto:support@stai-alfatih.ac.id" class="underline hover:text-[#D4AF37]">support@stai-alfatih.ac.id</a>
        </p>
        <p class="text-xs mt-2 text-gray-200">
            Dibuat dengan ❤️ menggunakan Laravel & Claude Code
        </p>
    </div>
</div>
@endsection
