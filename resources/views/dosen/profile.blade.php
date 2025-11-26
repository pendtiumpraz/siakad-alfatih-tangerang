@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#D4AF37]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span>Profil Dosen</span>
            </h1>
            <p class="text-gray-600 mt-1">Informasi data pribadi dosen</p>
        </div>
    </div>

    <div class="islamic-divider"></div>

    <!-- Profile Card -->
    <div class="card-islamic p-8">
        <!-- Profile Photo Section -->
        <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6 mb-8 pb-6 border-b border-gray-200">
            <div class="relative">
                <div class="w-32 h-32 rounded-full islamic-border overflow-hidden bg-white p-1">
                    @if($dosen->foto && Storage::disk('public')->exists($dosen->foto))
                        <img src="{{ asset('storage/' . $dosen->foto) }}"
                             alt="Foto Profil"
                             class="w-full h-full rounded-full object-cover">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($dosen->nama_lengkap) }}&size=200&background=4A7C59&color=fff"
                             alt="Foto Profil"
                             class="w-full h-full rounded-full object-cover">
                    @endif
                </div>
            </div>

            <div class="flex-1 text-center md:text-left">
                <h2 class="text-2xl font-bold text-[#4A7C59] mb-2">
                    {{ $dosen->gelar_depan ? $dosen->gelar_depan . ' ' : '' }}{{ $dosen->nama_lengkap }}{{ $dosen->gelar_belakang ? ', ' . $dosen->gelar_belakang : '' }}
                </h2>
                <div class="flex flex-wrap justify-center md:justify-start gap-2 mt-3">
                    <span class="px-4 py-1.5 bg-[#4A7C59] text-white rounded-full text-sm font-semibold">
                        NIP: {{ $dosen->nidn }}
                    </span>
                    <span class="px-4 py-1.5 bg-[#D4AF37] text-white rounded-full text-sm font-semibold">
                        Dosen
                    </span>
                </div>
            </div>

            <a href="#"
               onclick="event.preventDefault(); document.getElementById('edit-form-toggle').classList.toggle('hidden')"
               class="btn-islamic px-6 py-3">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Profil
            </a>
        </div>

        <!-- Profile Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-[#4A7C59]">
                <h3 class="text-sm font-medium text-gray-500 mb-1">NIP</h3>
                <p class="text-lg text-gray-900 font-semibold">{{ $dosen->nidn }}</p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-[#4A7C59]">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Nama Lengkap</h3>
                <p class="text-lg text-gray-900 font-semibold">{{ $dosen->nama_lengkap }}</p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-[#D4AF37]">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Gelar Depan</h3>
                <p class="text-lg text-gray-900 font-semibold">{{ $dosen->gelar_depan ?? '-' }}</p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-[#D4AF37]">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Gelar Belakang</h3>
                <p class="text-lg text-gray-900 font-semibold">{{ $dosen->gelar_belakang ?? '-' }}</p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Email Akun</h3>
                <p class="text-lg text-gray-900 font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    {{ $dosen->user->email ?? '-' }}
                </p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Email Dosen</h3>
                <p class="text-lg text-gray-900 font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    {{ $dosen->email_dosen ?? '-' }}
                </p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-green-500">
                <h3 class="text-sm font-medium text-gray-500 mb-1">No. Telepon</h3>
                <p class="text-lg text-gray-900 font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                    {{ $dosen->no_telepon ?? '-' }}
                </p>
            </div>

            <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-purple-500">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Username</h3>
                <p class="text-lg text-gray-900 font-semibold flex items-center">
                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    {{ $dosen->user->name ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Edit Form (Hidden by default) -->
    <div id="edit-form-toggle" class="hidden card-islamic p-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit Profil
        </h3>

        <form action="{{ route('dosen.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text"
                           name="nama_lengkap"
                           id="nama_lengkap"
                           value="{{ old('nama_lengkap', $dosen->nama_lengkap) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent"
                           required>
                    @error('nama_lengkap')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gelar_depan" class="block text-sm font-medium text-gray-700 mb-2">Gelar Depan</label>
                    <input type="text"
                           name="gelar_depan"
                           id="gelar_depan"
                           value="{{ old('gelar_depan', $dosen->gelar_depan) }}"
                           placeholder="Dr., Prof., dll"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    @error('gelar_depan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gelar_belakang" class="block text-sm font-medium text-gray-700 mb-2">Gelar Belakang</label>
                    <input type="text"
                           name="gelar_belakang"
                           id="gelar_belakang"
                           value="{{ old('gelar_belakang', $dosen->gelar_belakang) }}"
                           placeholder="M.Pd, M.A, Ph.D, dll"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    @error('gelar_belakang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email_dosen" class="block text-sm font-medium text-gray-700 mb-2">Email Dosen</label>
                    <input type="email"
                           name="email_dosen"
                           id="email_dosen"
                           value="{{ old('email_dosen', $dosen->email_dosen) }}"
                           placeholder="email@example.com"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    @error('email_dosen')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <input type="text"
                           name="no_telepon"
                           id="no_telepon"
                           value="{{ old('no_telepon', $dosen->no_telepon) }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    @error('no_telepon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                    <input type="file"
                           name="foto"
                           id="foto"
                           accept="image/jpeg,image/jpg,image/png"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                    @error('foto')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <button type="button"
                        onclick="document.getElementById('edit-form-toggle').classList.add('hidden')"
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                    Batal
                </button>
                <button type="submit"
                        class="btn-islamic px-6 py-3">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
