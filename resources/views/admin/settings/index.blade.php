@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                <i class="fas fa-cog text-[#D4AF37]"></i>
                <span>Pengaturan Sistem</span>
            </h1>
            <p class="text-gray-600 mt-1">Kelola pengaturan umum sistem SIAKAD</p>
        </div>
    </div>

    <!-- Islamic Divider -->
    <div class="islamic-divider"></div>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- SPMB Settings -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-comments mr-3"></i>
                        Informasi Kontak SPMB
                    </h3>
                    <p class="text-sm text-emerald-100 mt-1">Kontak yang akan ditampilkan di halaman pendaftaran mahasiswa baru</p>
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
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-[#4A7C59] transition @error($setting->key) border-red-500 @enderror">
                            @else
                                <input type="text"
                                       name="{{ $setting->key }}"
                                       id="{{ $setting->key }}"
                                       value="{{ old($setting->key, $setting->value) }}"
                                       required
                                       placeholder="{{ $setting->key === 'spmb_whatsapp' ? 'Contoh: 6281234567890' : '' }}"
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-[#4A7C59] transition @error($setting->key) border-red-500 @enderror">
                            @endif
                            @error($setting->key)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Settings (Bank) -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-credit-card mr-3"></i>
                        Informasi Rekening Bank
                    </h3>
                    <p class="text-sm text-blue-100 mt-1">Rekening yang akan ditampilkan untuk pembayaran SPP mahasiswa</p>
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
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition @error($setting->key) border-red-500 @enderror">
                            @error($setting->key)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                    
                    <!-- Preview Card -->
                    <div class="mt-4 p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border-2 border-blue-200">
                        <p class="text-xs text-blue-600 font-semibold mb-2">
                            <i class="fas fa-eye mr-1"></i> Preview tampilan di halaman pembayaran:
                        </p>
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-gray-600" id="preview-bank-name">{{ $paymentSettings->where('key', 'bank_name')->first()->value ?? 'Nama Bank' }}</p>
                            <p class="text-lg font-bold text-gray-800" id="preview-account-number">{{ $paymentSettings->where('key', 'bank_account_number')->first()->value ?? 'Nomor Rekening' }}</p>
                            <p class="text-sm text-gray-600">a.n. <span id="preview-account-name">{{ $paymentSettings->where('key', 'bank_account_name')->first()->value ?? 'Nama Pemilik' }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Settings -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-[#D4AF37] to-yellow-500 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-money-bill-wave mr-3"></i>
                        Pengaturan Biaya (Default)
                    </h3>
                    <p class="text-sm text-yellow-100 mt-1">Biaya default untuk mahasiswa (dapat diatur per jalur seleksi)</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pricingSettings as $setting)
                        <div>
                            <label for="{{ $setting->key }}" class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $setting->description }}
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-semibold">Rp</span>
                                <input type="number"
                                       name="{{ $setting->key }}"
                                       id="{{ $setting->key }}"
                                       value="{{ old($setting->key, $setting->value) }}"
                                       required
                                       min="0"
                                       step="1000"
                                       class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-[#D4AF37] transition @error($setting->key) border-red-500 @enderror">
                            </div>
                            @error($setting->key)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- General/Institution Settings -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-university mr-3"></i>
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
                                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition @error($setting->key) border-red-500 @enderror">{{ old($setting->key, $setting->value) }}</textarea>
                            @else
                                <input type="text"
                                       name="{{ $setting->key }}"
                                       id="{{ $setting->key }}"
                                       value="{{ old($setting->key, $setting->value) }}"
                                       required
                                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition @error($setting->key) border-red-500 @enderror">
                            @endif
                            @error($setting->key)
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 text-gray-600">
                    <i class="fas fa-info-circle text-[#D4AF37]"></i>
                    <p class="text-sm">Pastikan semua informasi sudah benar sebelum menyimpan</p>
                </div>
                <button type="submit"
                        class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] hover:from-[#234a30] hover:to-[#3d6849] text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-2 shadow-lg">
                    <i class="fas fa-save"></i>
                    <span>Simpan Pengaturan</span>
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Live preview for bank settings
document.getElementById('bank_name')?.addEventListener('input', function(e) {
    document.getElementById('preview-bank-name').textContent = e.target.value || 'Nama Bank';
});
document.getElementById('bank_account_number')?.addEventListener('input', function(e) {
    document.getElementById('preview-account-number').textContent = e.target.value || 'Nomor Rekening';
});
document.getElementById('bank_account_name')?.addEventListener('input', function(e) {
    document.getElementById('preview-account-name').textContent = e.target.value || 'Nama Pemilik';
});
</script>
@endpush
@endsection
