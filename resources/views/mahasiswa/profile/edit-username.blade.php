@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#1C3F3A] via-[#2D5F4F] to-[#1C3F3A] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('mahasiswa.dashboard') }}" class="inline-flex items-center text-white hover:text-gray-200 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full mb-4 shadow-lg">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Edit Username</h1>
            <p class="text-gray-200">Customize username Anda (hanya bisa dilakukan 1x)</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Current Info -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Informasi Saat Ini
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm text-gray-600">NIM</label>
                            <p class="font-semibold text-gray-900">{{ auth()->user()->mahasiswa->nim }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Nama Lengkap</label>
                            <p class="font-semibold text-gray-900">{{ auth()->user()->mahasiswa->nama_lengkap }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Username Saat Ini</label>
                            <p class="font-semibold text-gray-900">{{ auth()->user()->username }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600">Email</label>
                            <p class="font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->username_changed_at)
                    <!-- Already Changed -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                        <svg class="w-16 h-16 text-yellow-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <h3 class="text-xl font-bold text-yellow-800 mb-2">Username Sudah Pernah Diubah</h3>
                        <p class="text-yellow-700 mb-2">
                            Anda sudah mengubah username pada:
                            <strong>{{ \Carbon\Carbon::parse(auth()->user()->username_changed_at)->format('d F Y, H:i') }}</strong>
                        </p>
                        <p class="text-sm text-yellow-600">
                            Username hanya dapat diubah 1x untuk keamanan. Jika ada masalah, hubungi admin.
                        </p>
                    </div>
                @else
                    <!-- Edit Form -->
                    <form action="{{ route('mahasiswa.username.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Username Baru <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="username" 
                                value="{{ old('username', auth()->user()->username) }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-[#4A7C59] transition"
                                placeholder="Masukkan username baru"
                            >
                            <p class="mt-2 text-sm text-gray-600">
                                <strong>Ketentuan:</strong> Min 3 karakter, hanya huruf kecil, angka, underscore (_), dan titik (.).
                            </p>
                        </div>

                        <!-- Warning Notice -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-start">
                            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div>
                                <h4 class="font-semibold text-red-800 mb-1">Perhatian!</h4>
                                <ul class="text-sm text-red-700 space-y-1">
                                    <li>• Username hanya bisa diubah <strong>1 kali</strong></li>
                                    <li>• Setelah disimpan, <strong>tidak bisa diubah lagi</strong></li>
                                    <li>• Pastikan username yang Anda pilih sudah benar</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Password Confirmation -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-[#4A7C59] transition"
                                placeholder="Masukkan password untuk konfirmasi"
                            >
                            <p class="mt-2 text-sm text-gray-600">
                                Masukkan password Anda untuk mengkonfirmasi perubahan.
                            </p>
                        </div>

                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="{{ route('mahasiswa.dashboard') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold">
                                Batal
                            </a>
                            <button type="submit" class="px-6 py-3 bg-[#4A7C59] text-white rounded-lg hover:bg-[#3d6849] transition font-semibold shadow-lg">
                                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan Username
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-white/10 backdrop-blur-sm rounded-lg p-4 text-white text-sm">
            <h3 class="font-semibold mb-2 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Mengapa hanya 1x?
            </h3>
            <p class="text-gray-200">
                Untuk keamanan dan konsistensi data akademik, username hanya dapat diubah sekali. 
                Jika terjadi kesalahan atau ada kebutuhan khusus, silakan hubungi admin SIAKAD.
            </p>
        </div>
    </div>
</div>
@endsection
