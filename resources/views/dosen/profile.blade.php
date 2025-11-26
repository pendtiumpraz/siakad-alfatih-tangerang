@extends('layouts.dosen')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Profil Saya</h1>
            <p class="mt-2 text-gray-600">Kelola informasi profil dan keamanan akun Anda</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Sidebar - Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Cover Image -->
                    <div class="h-20 bg-gradient-to-r from-green-600 to-green-700"></div>
                    
                    <!-- Profile Photo -->
                    <div class="relative px-6 pb-6">
                        <div class="flex flex-col items-center -mt-10">
                            <div class="relative">
                                @if($dosen->foto)
                                    <img src="{{ route('image.proxy', ['id' => $dosen->foto]) }}"
                                         alt="Foto Profil {{ $dosen->nama_lengkap }}"
                                         class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover"
                                         loading="eager"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($dosen->nama_lengkap) }}&size=200&background=059669&color=fff';">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($dosen->nama_lengkap) }}&size=200&background=059669&color=fff"
                                         alt="Foto Profil {{ $dosen->nama_lengkap }}"
                                         class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover">
                                @endif
                                <button onclick="openModal('editProfileModal')" class="absolute bottom-0 right-0 bg-green-600 text-white p-2 rounded-full shadow-lg hover:bg-green-700 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            <div class="text-center mt-4">
                                <h2 class="text-2xl font-bold text-gray-900">
                                    {{ $dosen->gelar_depan ? $dosen->gelar_depan . ' ' : '' }}{{ $dosen->nama_lengkap }}{{ $dosen->gelar_belakang ? ', ' . $dosen->gelar_belakang : '' }}
                                </h2>
                                <p class="text-green-600 font-semibold mt-1">NIP: {{ $dosen->nidn }}</p>
                                <span class="inline-block mt-2 px-4 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                    Dosen
                                </span>

                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-6 space-y-2">
                            <button onclick="openModal('editProfileModal')" class="w-full flex items-center justify-center space-x-2 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span>Edit Profil</span>
                            </button>
                            
                            @if(!$dosen->user->username_changed_at)
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
                            <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Informasi Pribadi
                        </h3>
                        <button onclick="openModal('editProfileModal')" class="text-green-600 hover:text-green-700 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                            <p class="font-semibold text-gray-900">{{ $dosen->nama_lengkap }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">NIP</p>
                            <p class="font-semibold text-gray-900">{{ $dosen->nidn }}</p>
                            <span class="text-xs text-gray-400 flex items-center mt-1">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                                Tidak dapat diubah
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Gelar Depan</p>
                            <p class="font-semibold text-gray-900">{{ $dosen->gelar_depan ?? '-' }}</p>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 mb-1">Gelar Belakang</p>
                            <p class="font-semibold text-gray-900">{{ $dosen->gelar_belakang ?? '-' }}</p>
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
                        <button onclick="openModal('editProfileModal')" class="text-green-600 hover:text-green-700 font-semibold text-sm flex items-center">
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
                            <p class="font-semibold text-gray-900">{{ $dosen->user->email ?? '-' }}</p>
                        </div>

                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-green-600 mb-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                </svg>
                                No. Telepon
                            </p>
                            <p class="font-semibold text-gray-900">{{ $dosen->no_telepon ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bank Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Informasi Rekening
                        </h3>
                        <button onclick="openModal('editProfileModal')" class="text-green-600 hover:text-green-700 font-semibold text-sm flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </button>
                    </div>

                    @if(!$dosen->nama_bank || !$dosen->nomor_rekening)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 font-semibold">
                                        Data rekening belum lengkap!
                                    </p>
                                    <p class="text-xs text-yellow-600 mt-1">
                                        Silakan lengkapi data rekening untuk dapat mengajukan pencairan gaji.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-orange-50 rounded-lg p-4">
                            <p class="text-sm text-orange-600 mb-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path>
                                    <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                </svg>
                                Nama Bank
                            </p>
                            <p class="font-semibold text-gray-900">{{ $dosen->nama_bank ?? '-' }}</p>
                        </div>

                        <div class="bg-orange-50 rounded-lg p-4">
                            <p class="text-sm text-orange-600 mb-1 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                                Nomor Rekening
                            </p>
                            <p class="font-semibold text-gray-900">{{ $dosen->nomor_rekening ?? '-' }}</p>
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
                                    <p class="text-sm text-gray-500">{{ $dosen->user->username }}</p>
                                    @if($dosen->user->username_changed_at)
                                        <span class="text-xs text-orange-600 flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Sudah diubah (tidak dapat diubah lagi)
                                        </span>
                                    @else
                                        <span class="text-xs text-green-600 flex items-center mt-1">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Dapat diubah 1x
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if(!$dosen->user->username_changed_at)
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

        <form action="{{ route('dosen.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6 max-h-[calc(90vh-140px)] overflow-y-auto">
            @csrf
            @method('PUT')

            <!-- Personal Info Section -->
            <div>
                <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Informasi Pribadi
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $dosen->nama_lengkap) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               required>
                        @error('nama_lengkap')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gelar Depan</label>
                        <input type="text" name="gelar_depan" value="{{ old('gelar_depan', $dosen->gelar_depan) }}"
                               placeholder="Dr., Prof., dll"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gelar Belakang</label>
                        <input type="text" name="gelar_belakang" value="{{ old('gelar_belakang', $dosen->gelar_belakang) }}"
                               placeholder="M.Pd., M.Si., dll"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">No. Telepon</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $dosen->no_telepon) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $dosen->user->email) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Foto & Bank Info Section (Side by Side) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-200">
                <!-- Left: Foto Profile -->
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Foto Profil
                    </h4>
                    
                    <!-- Preview Foto Existing -->
                    @if($dosen->foto)
                        <div class="mb-3">
                            <img src="{{ route('image.proxy', ['id' => $dosen->foto]) }}"
                                 alt="Foto saat ini"
                                 class="w-full h-40 object-cover rounded-lg border-2 border-green-500 shadow-sm"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($dosen->nama_lengkap) }}&size=200&background=059669&color=fff';">
                            <p class="text-xs text-green-600 mt-2">
                                <i class="fas fa-check-circle mr-1"></i>
                                Foto tersimpan di Google Drive
                            </p>
                        </div>
                    @endif
                    
                    <!-- Input File Foto Baru -->
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Upload Foto Baru</label>
                    <input type="file" name="foto" accept="image/jpeg,image/jpg,image/png" id="fotoInput"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           onchange="previewNewFoto(event)">
                    <p class="text-xs text-gray-500 mt-1">Max 2MB (JPG, PNG)</p>
                    
                    <!-- Preview Foto Baru (before upload) -->
                    <div id="newFotoPreview" class="mt-3 hidden">
                        <p class="text-xs font-semibold text-gray-700 mb-1">Preview:</p>
                        <img id="newFotoImage" src="" alt="Preview" class="w-full h-32 object-cover rounded-lg border-2 border-blue-500 shadow-sm">
                    </div>
                </div>

                <!-- Right: Bank Info -->
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Informasi Rekening
                    </h4>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bank</label>
                            <input type="text" name="nama_bank" value="{{ old('nama_bank', $dosen->nama_bank) }}"
                                   placeholder="BRI, BNI, Mandiri, dll"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Rekening</label>
                            <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening', $dosen->nomor_rekening) }}"
                                   placeholder="1234567890"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        </div>
                        
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-3">
                            <p class="text-xs text-orange-700 flex items-start">
                                <i class="fas fa-info-circle mr-2 mt-0.5 flex-shrink-0"></i>
                                <span>Data rekening diperlukan untuk pencairan gaji</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeModal('editProfileModal')" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
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

        <form action="{{ route('dosen.username.update') }}" method="POST" class="p-6 space-y-4">
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

        <form action="{{ route('dosen.password.update') }}" method="POST" class="p-6 space-y-4">
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
