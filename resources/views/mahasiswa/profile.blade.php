@extends('layouts.mahasiswa')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Profil Saya</h1>
            <p class="mt-2 text-gray-600">Kelola informasi profil dan keamanan akun Anda</p>
            
            <!-- TEMPORARY: Show foto status -->
            <div class="mt-4 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-sm">
                <p><strong>🔍 DEBUG INFO:</strong></p>
                <p>NIM: {{ $mahasiswa->nim }}</p>
                <p>Nama: {{ $mahasiswa->nama_lengkap }}</p>
                <p>Foto ID: <code>{{ $mahasiswa->foto ?? 'NULL (Belum ada foto)' }}</code></p>
                @if($mahasiswa->foto)
                    <p>✅ Foto TERSIMPAN di database</p>
                    <p>URL: <a href="https://drive.google.com/thumbnail?id={{ $mahasiswa->foto }}&sz=w400" target="_blank" class="text-blue-600 underline">Test URL</a></p>
                @else
                    <p>❌ Foto BELUM ADA di database</p>
                    <p>Silakan upload foto baru via tombol "Edit Profil"</p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Sidebar - Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Cover Image -->
                    <div class="h-20 bg-gradient-to-r from-blue-600 to-blue-700"></div>
                    
                    <!-- Profile Photo -->
                    <div class="relative px-6 pb-6">
                        <div class="flex flex-col items-center -mt-10">
                            <div class="relative">
                                @if($mahasiswa->foto)
                                    <img src="https://drive.google.com/thumbnail?id={{ $mahasiswa->foto }}&sz=w400"
                                         alt="Foto Profil {{ $mahasiswa->nama_lengkap }}"
                                         class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover"
                                         loading="eager"
                                         onerror="this.src='https://drive.usercontent.google.com/download?id={{ $mahasiswa->foto }}&export=view'; this.onerror=function(){this.src='https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->nama_lengkap) }}&size=200&background=3b82f6&color=fff';};">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->nama_lengkap) }}&size=200&background=3b82f6&color=fff"
                                         alt="Foto Profil {{ $mahasiswa->nama_lengkap }}"
                                         class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                                @endif
                                <button onclick="openModal('editProfileModal')" class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full shadow-lg hover:bg-blue-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="text-center mt-4">
                                <h2 class="text-2xl font-bold text-gray-900">
                                    {{ $mahasiswa->nama_lengkap }}
                                </h2>
                                <p class="text-blue-600 font-semibold mt-1">NIM: {{ $mahasiswa->nim }}</p>
                                <span class="inline-block mt-2 px-4 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                    Mahasiswa
                                </span>

                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-6 space-y-2">
                            <button onclick="openModal('editProfileModal')" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit Profil</span>
                            </button>
                            
                            @if(!$mahasiswa->user->username_changed_at)
                                <button onclick="openModal('editUsernameModal')" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Ubah Username</span>
                                </button>
                            @endif
                            
                            <button onclick="openModal('editPasswordModal')" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                                <span>Ubah Password</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content - Information Cards -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Pribadi
                        </h3>
                        <button onclick="openModal('editProfileModal')" class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                            <p class="font-semibold text-gray-900">{{ $mahasiswa->nama_lengkap }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">NIM</p>
                            <p class="font-semibold text-gray-900">{{ $mahasiswa->nim }}</p>
                            <span class="text-xs text-gray-400 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tidak dapat diubah
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Program Studi</p>
                            <p class="font-semibold text-gray-900">{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</p>
                            <span class="text-xs text-gray-400 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tidak dapat diubah
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Angkatan</p>
                            <p class="font-semibold text-gray-900">{{ $mahasiswa->angkatan }}</p>
                            <span class="text-xs text-gray-400 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tidak dapat diubah
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Semester Aktif</p>
                            <p class="font-semibold text-gray-900">{{ $mahasiswa->semester_aktif }}</p>
                            <span class="text-xs text-gray-400 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tidak dapat diubah
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $mahasiswa->status == 'aktif' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($mahasiswa->status) }}
                            </span>
                            <span class="text-xs text-gray-400 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tidak dapat diubah
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Informasi Kontak
                        </h3>
                        <button onclick="openModal('editProfileModal')" class="text-blue-600 hover:text-blue-700 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-blue-600 mb-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                </svg>
                                Email
                            </p>
                            <p class="font-semibold text-gray-900">{{ $mahasiswa->user->email ?? '-' }}</p>
                        </div>

                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-blue-600 mb-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                No. Telepon
                            </p>
                            <p class="font-semibold text-gray-900">{{ $mahasiswa->no_telepon ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Account Security -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center mb-6">
                        <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-gray-900">Keamanan Akun</h3>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Username</p>
                                    <p class="text-sm text-gray-500">{{ $mahasiswa->user->username }}</p>
                                    @if($mahasiswa->user->username_changed_at)
                                        <span class="text-xs text-orange-600 flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Sudah diubah (tidak dapat diubah lagi)
                                        </span>
                                    @else
                                        <span class="text-xs text-blue-600 flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Dapat diubah 1x
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if(!$mahasiswa->user->username_changed_at)
                                <button onclick="openModal('editUsernameModal')" 
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-sm">
                                    Ubah
                                </button>
                            @endif
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">Password</p>
                                    <p class="text-sm text-gray-500">••••••••</p>
                                </div>
                            </div>
                            <button onclick="openModal('editPasswordModal')" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold text-sm">
                                Ubah
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Profile -->
<div id="editProfileModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
            <h3 class="text-2xl font-bold text-gray-900">Edit Profil</h3>
            <button onclick="closeModal('editProfileModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
                    @error('nama_lengkap')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $mahasiswa->user->email) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon</label>
                    <input type="text" name="no_telepon" value="{{ old('no_telepon', $mahasiswa->no_telepon) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Profil</label>
                    
                    <!-- Preview Foto Existing -->
                    @if($mahasiswa->foto)
                        <div class="mb-4 flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <img src="https://drive.google.com/thumbnail?id={{ $mahasiswa->foto }}&sz=w200"
                                     alt="Foto saat ini"
                                     class="w-32 h-32 object-cover rounded-lg border-2 border-blue-500 shadow-md"
                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($mahasiswa->nama_lengkap) }}&size=200&background=3b82f6&color=fff';">
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600 mb-1">
                                    <i class="fas fa-check-circle text-blue-500 mr-1"></i>
                                    <strong>Foto saat ini tersimpan di Google Drive</strong>
                                </p>
                                <p class="text-xs text-gray-500">Upload foto baru untuk mengganti foto saat ini</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Input File Foto Baru -->
                    <input type="file" name="foto" accept="image/jpeg,image/jpg,image/png" id="fotoInput"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onchange="previewNewFoto(event)">
                    <p class="text-xs text-gray-500 mt-1">Max 2MB (JPG, PNG)</p>
                    
                    <!-- Preview Foto Baru (before upload) -->
                    <div id="newFotoPreview" class="mt-3 hidden">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Preview Foto Baru:</p>
                        <img id="newFotoImage" src="" alt="Preview" class="w-32 h-32 object-cover rounded-lg border-2 border-blue-500 shadow-md">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeModal('editProfileModal')" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Username -->
<div id="editUsernameModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-2xl">
            <h3 class="text-2xl font-bold text-white">Ubah Username</h3>
            <p class="text-blue-100 text-sm mt-1">Hanya dapat diubah 1 kali saja</p>
        </div>

        <form action="{{ route('mahasiswa.username.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
                <p class="text-sm text-yellow-700">
                    <strong>Perhatian:</strong> Username hanya dapat diubah 1 kali. Setelah diubah, Anda tidak dapat mengubahnya lagi.
                </p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Username Baru <span class="text-red-500">*</span></label>
                <input type="text" name="username" value="{{ old('username') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       pattern="[a-z0-9_.]+"
                       required>
                <p class="text-xs text-gray-500 mt-1">Hanya huruf kecil, angka, underscore (_), dan titik (.)</p>
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
                <p class="text-xs text-gray-500 mt-1">Konfirmasi dengan password akun Anda</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeModal('editUsernameModal')" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                    Ubah Username
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Password -->
<div id="editPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 rounded-t-2xl">
            <h3 class="text-2xl font-bold text-white">Ubah Password</h3>
            <p class="text-purple-100 text-sm mt-1">Buat password yang kuat dan aman</p>
        </div>

        <form action="{{ route('mahasiswa.password.update') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="current_password" id="current_password_modal"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 pr-12"
                           required>
                    <button type="button" onclick="togglePasswordVisibility('current_password_modal', 'icon_current_password_modal')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="icon_current_password_modal" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <!-- Eye Icon (default - password hidden) -->
                            <g class="eye-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </g>
                            <!-- Eye Slash Icon (hidden by default) -->
                            <g class="eye-slash-icon hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </g>
                        </svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="password" id="password_modal" minlength="8"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 pr-12"
                           required>
                    <button type="button" onclick="togglePasswordVisibility('password_modal', 'icon_password_modal')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="icon_password_modal" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <g class="eye-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </g>
                            <g class="eye-slash-icon hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </g>
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation_modal" minlength="8"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 pr-12"
                           required>
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation_modal', 'icon_password_confirmation_modal')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                        <svg id="icon_password_confirmation_modal" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <g class="eye-icon">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </g>
                            <g class="eye-slash-icon hidden">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </g>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeModal('editPasswordModal')" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-semibold">
                    Ubah Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = 'auto';
}

function togglePasswordVisibility(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    const eyeIcon = icon.querySelector('.eye-icon');
    const eyeSlashIcon = icon.querySelector('.eye-slash-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        eyeIcon.classList.add('hidden');
        eyeSlashIcon.classList.remove('hidden');
    } else {
        field.type = 'password';
        eyeIcon.classList.remove('hidden');
        eyeSlashIcon.classList.add('hidden');
    }
}

// Preview new foto before upload
function previewNewFoto(event) {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (max 2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            event.target.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match('image/jpeg') && !file.type.match('image/jpg') && !file.type.match('image/png')) {
            alert('Format file tidak valid! Hanya JPG dan PNG yang diperbolehkan');
            event.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('newFotoImage').src = e.target.result;
            document.getElementById('newFotoPreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('newFotoPreview').classList.add('hidden');
    }
}

// Close modal when clicking outside
document.querySelectorAll('[id$="Modal"]').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            if (!modal.classList.contains('hidden')) {
                closeModal(modal.id);
            }
        });
    }
});
</script>

@endsection
