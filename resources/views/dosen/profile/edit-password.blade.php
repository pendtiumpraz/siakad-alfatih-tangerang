@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Ubah Password</h1>
            <p class="text-gray-600 mt-1">Ubah password akun Anda</p>
        </div>
        <a href="{{ route('dosen.profile') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Info Card -->
    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Perhatian:</strong> Password baru minimal 8 karakter. Pastikan password Anda kuat dan mudah diingat.
                </p>
            </div>
        </div>
    </div>

    <!-- Edit Password Form -->
    <x-islamic-card title="Form Ubah Password">
        <form action="{{ route('dosen.password.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current User Info -->
            <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-green-500">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Nama</label>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->dosen->nama_lengkap }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">NIP</label>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->dosen->nidn }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Username</label>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->username }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600">Email</label>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Password Lama <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('current_password') border-red-500 @enderror"
                        required>
                    <button type="button" onclick="togglePassword('current_password')" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                    Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        minlength="8"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('password') border-red-500 @enderror"
                        required>
                    <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                    Konfirmasi Password Baru <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        minlength="8"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        required>
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('dosen.profile') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    <i class="fas fa-save mr-2"></i>Simpan Password
                </button>
            </div>
        </form>
    </x-islamic-card>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
    } else {
        field.type = 'password';
    }
}
</script>
@endsection
