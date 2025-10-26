<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Pengaturan Sistem') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-semibold">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-semibold">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- SPMB Settings -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="bg-gradient-to-r from-islamic-green to-green-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                            Informasi Kontak SPMB
                        </h3>
                        <p class="text-sm text-islamic-gold mt-1">Kontak yang akan ditampilkan di halaman pendaftaran mahasiswa baru</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($spmbSettings as $setting)
                            <div>
                                <label for="{{ $setting->key }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $setting->description }}
                                    <span class="text-red-500">*</span>
                                </label>
                                @if($setting->key === 'spmb_email')
                                    <input type="email"
                                           name="{{ $setting->key }}"
                                           id="{{ $setting->key }}"
                                           value="{{ old($setting->key, $setting->value) }}"
                                           required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-islamic-green @error($setting->key) border-red-500 @enderror">
                                @else
                                    <input type="text"
                                           name="{{ $setting->key }}"
                                           id="{{ $setting->key }}"
                                           value="{{ old($setting->key, $setting->value) }}"
                                           required
                                           placeholder="{{ $setting->key === 'spmb_whatsapp' ? 'Contoh: 6281234567890' : '' }}"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-islamic-green @error($setting->key) border-red-500 @enderror">
                                @endif
                                @error($setting->key)
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Settings -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Informasi Rekening Bank
                        </h3>
                        <p class="text-sm text-blue-100 mt-1">Rekening yang akan ditampilkan untuk pembayaran</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($paymentSettings as $setting)
                            <div>
                                <label for="{{ $setting->key }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $setting->description }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="{{ $setting->key }}"
                                       id="{{ $setting->key }}"
                                       value="{{ old($setting->key, $setting->value) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error($setting->key) border-red-500 @enderror">
                                @error($setting->key)
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Pricing Settings -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pengaturan Biaya (Default)
                        </h3>
                        <p class="text-sm text-yellow-100 mt-1">Biaya default untuk mahasiswa (dapat diatur per jalur seleksi di menu Jalur Seleksi)</p>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($pricingSettings as $setting)
                            <div>
                                <label for="{{ $setting->key }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $setting->description }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">Rp</span>
                                    <input type="number"
                                           name="{{ $setting->key }}"
                                           id="{{ $setting->key }}"
                                           value="{{ old($setting->key, $setting->value) }}"
                                           required
                                           min="0"
                                           step="1000"
                                           class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 @error($setting->key) border-red-500 @enderror">
                                </div>
                                @error($setting->key)
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- General Settings -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-6">
                    <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Informasi Institusi
                        </h3>
                        <p class="text-sm text-gray-300 mt-1">Informasi umum tentang institusi</p>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach($generalSettings as $setting)
                            <div>
                                <label for="{{ $setting->key }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ $setting->description }}
                                    <span class="text-red-500">*</span>
                                </label>
                                @if($setting->key === 'institution_address')
                                    <textarea name="{{ $setting->key }}"
                                              id="{{ $setting->key }}"
                                              rows="3"
                                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error($setting->key) border-red-500 @enderror">{{ old($setting->key, $setting->value) }}</textarea>
                                @else
                                    <input type="text"
                                           name="{{ $setting->key }}"
                                           id="{{ $setting->key }}"
                                           value="{{ old($setting->key, $setting->value) }}"
                                           required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 @error($setting->key) border-red-500 @enderror">
                                @endif
                                @error($setting->key)
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pastikan semua informasi sudah benar sebelum menyimpan
                        </p>
                        <button type="submit"
                                class="bg-islamic-green hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
