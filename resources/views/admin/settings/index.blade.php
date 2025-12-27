@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1a1a1a] flex items-center space-x-3">
                <i class="fas fa-cog text-[#D4AF37]"></i>
                <span>Pengaturan Sistem</span>
            </h1>
            <p class="text-[#555555] mt-1">Kelola pengaturan umum sistem SIAKAD</p>
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
                    <p class="text-sm text-[#d4f5dc] mt-1">Kontak yang akan ditampilkan di halaman pendaftaran mahasiswa baru</p>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Email SPMB -->
                    <div>
                        <label for="spmb_email" class="block text-sm font-semibold text-[#333333] mb-2">
                            Email Kontak SPMB
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               name="spmb_email"
                               id="spmb_email"
                               value="{{ old('spmb_email', \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id')) }}"
                               required
                               placeholder="contoh@staialfatih.ac.id"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-[#4A7C59] transition text-[#1a1a1a] @error('spmb_email') border-red-500 @enderror">
                        @error('spmb_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone SPMB -->
                    <div>
                        <label for="spmb_phone" class="block text-sm font-semibold text-[#333333] mb-2">
                            Nomor Telepon SPMB
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="spmb_phone"
                               id="spmb_phone"
                               value="{{ old('spmb_phone', \App\Models\SystemSetting::get('spmb_phone', '021-12345678')) }}"
                               required
                               placeholder="021-12345678"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-[#4A7C59] transition text-[#1a1a1a] @error('spmb_phone') border-red-500 @enderror">
                        @error('spmb_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp SPMB -->
                    <div>
                        <label for="spmb_whatsapp" class="block text-sm font-semibold text-[#333333] mb-2">
                            Nomor WhatsApp SPMB
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="spmb_whatsapp"
                               id="spmb_whatsapp"
                               value="{{ old('spmb_whatsapp', \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890')) }}"
                               required
                               placeholder="6281234567890 (format: 628xxx)"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#4A7C59] focus:border-[#4A7C59] transition text-[#1a1a1a] @error('spmb_whatsapp') border-red-500 @enderror">
                        <p class="text-xs text-[#777777] mt-1">Format: 628xxx (tanpa tanda + atau spasi)</p>
                        @error('spmb_whatsapp')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Payment Settings (Bank) -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-[#2563eb] to-[#3b82f6] px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-credit-card mr-3"></i>
                        Informasi Rekening Bank
                    </h3>
                    <p class="text-sm text-[#d4e6f5] mt-1">Rekening yang akan ditampilkan untuk pembayaran SPP mahasiswa</p>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Bank Name -->
                    <div>
                        <label for="bank_name" class="block text-sm font-semibold text-[#333333] mb-2">
                            Nama Bank
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="bank_name"
                               id="bank_name"
                               value="{{ old('bank_name', \App\Models\SystemSetting::get('bank_name', 'BCA')) }}"
                               required
                               placeholder="Contoh: BCA, Mandiri, BNI"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-[#2563eb] transition text-[#1a1a1a] @error('bank_name') border-red-500 @enderror">
                        @error('bank_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Number -->
                    <div>
                        <label for="bank_account_number" class="block text-sm font-semibold text-[#333333] mb-2">
                            Nomor Rekening
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="bank_account_number"
                               id="bank_account_number"
                               value="{{ old('bank_account_number', \App\Models\SystemSetting::get('bank_account_number', '1234567890')) }}"
                               required
                               placeholder="1234567890"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-[#2563eb] transition text-[#1a1a1a] @error('bank_account_number') border-red-500 @enderror">
                        @error('bank_account_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Account Name -->
                    <div>
                        <label for="bank_account_name" class="block text-sm font-semibold text-[#333333] mb-2">
                            Nama Pemilik Rekening
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="bank_account_name"
                               id="bank_account_name"
                               value="{{ old('bank_account_name', \App\Models\SystemSetting::get('bank_account_name', 'STAI AL-FATIH')) }}"
                               required
                               placeholder="STAI AL-FATIH"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#2563eb] focus:border-[#2563eb] transition text-[#1a1a1a] @error('bank_account_name') border-red-500 @enderror">
                        @error('bank_account_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Preview Card -->
                    <div class="mt-4 p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border-2 border-blue-200">
                        <p class="text-xs text-[#2563eb] font-semibold mb-2">
                            <i class="fas fa-eye mr-1"></i> Preview tampilan di halaman pembayaran mahasiswa:
                        </p>
                        <div class="bg-white p-3 rounded-lg shadow-sm">
                            <p class="text-sm text-[#555555]" id="preview-bank-name">{{ \App\Models\SystemSetting::get('bank_name', 'BCA') }}</p>
                            <p class="text-lg font-bold text-[#1a1a1a]" id="preview-account-number">{{ \App\Models\SystemSetting::get('bank_account_number', '1234567890') }}</p>
                            <p class="text-sm text-[#555555]">a.n. <span id="preview-account-name">{{ \App\Models\SystemSetting::get('bank_account_name', 'STAI AL-FATIH') }}</span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing Settings -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-[#D4AF37] to-[#eab308] px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-money-bill-wave mr-3"></i>
                        Pengaturan Biaya (Default)
                    </h3>
                    <p class="text-sm text-[#fffacd] mt-1">Biaya default untuk mahasiswa (dapat diatur per jalur seleksi)</p>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Uang Gedung -->
                    <div>
                        <label for="biaya_uang_gedung" class="block text-sm font-semibold text-[#333333] mb-2">
                            Biaya Uang Gedung
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#555555] font-semibold">Rp</span>
                            <input type="number"
                                   name="biaya_uang_gedung"
                                   id="biaya_uang_gedung"
                                   value="{{ old('biaya_uang_gedung', \App\Models\SystemSetting::get('biaya_uang_gedung', 5000000)) }}"
                                   required
                                   min="0"
                                   step="1000"
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-[#D4AF37] transition text-[#1a1a1a] @error('biaya_uang_gedung') border-red-500 @enderror">
                        </div>
                        @error('biaya_uang_gedung')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SPP Semester -->
                    <div>
                        <label for="biaya_spp_semester" class="block text-sm font-semibold text-[#333333] mb-2">
                            Biaya SPP per Semester
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#555555] font-semibold">Rp</span>
                            <input type="number"
                                   name="biaya_spp_semester"
                                   id="biaya_spp_semester"
                                   value="{{ old('biaya_spp_semester', \App\Models\SystemSetting::get('biaya_spp_semester', 2500000)) }}"
                                   required
                                   min="0"
                                   step="1000"
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-[#D4AF37] transition text-[#1a1a1a] @error('biaya_spp_semester') border-red-500 @enderror">
                        </div>
                        @error('biaya_spp_semester')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Wisuda -->
                    <div>
                        <label for="biaya_wisuda" class="block text-sm font-semibold text-[#333333] mb-2">
                            Biaya Wisuda
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#555555] font-semibold">Rp</span>
                            <input type="number"
                                   name="biaya_wisuda"
                                   id="biaya_wisuda"
                                   value="{{ old('biaya_wisuda', \App\Models\SystemSetting::get('biaya_wisuda', 1500000)) }}"
                                   required
                                   min="0"
                                   step="1000"
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-[#D4AF37] transition text-[#1a1a1a] @error('biaya_wisuda') border-red-500 @enderror">
                        </div>
                        @error('biaya_wisuda')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Daftar Ulang -->
                    <div>
                        <label for="biaya_daftar_ulang" class="block text-sm font-semibold text-[#333333] mb-2">
                            Biaya Daftar Ulang
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-[#555555] font-semibold">Rp</span>
                            <input type="number"
                                   name="biaya_daftar_ulang"
                                   id="biaya_daftar_ulang"
                                   value="{{ old('biaya_daftar_ulang', \App\Models\SystemSetting::get('biaya_daftar_ulang', 500000)) }}"
                                   required
                                   min="0"
                                   step="1000"
                                   class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#D4AF37] focus:border-[#D4AF37] transition text-[#1a1a1a] @error('biaya_daftar_ulang') border-red-500 @enderror">
                        </div>
                        @error('biaya_daftar_ulang')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- General/Institution Settings -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-[#4b5563] to-[#6b7280] px-6 py-4">
                    <h3 class="text-lg font-bold text-white flex items-center">
                        <i class="fas fa-university mr-3"></i>
                        Informasi Institusi
                    </h3>
                    <p class="text-sm text-[#d4d4d4] mt-1">Informasi umum tentang institusi</p>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Institution Name -->
                    <div>
                        <label for="institution_name" class="block text-sm font-semibold text-[#333333] mb-2">
                            Nama Institusi
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="institution_name"
                               id="institution_name"
                               value="{{ old('institution_name', \App\Models\SystemSetting::get('institution_name', 'STAI AL-FATIH')) }}"
                               required
                               placeholder="STAI AL-FATIH"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#4b5563] focus:border-[#4b5563] transition text-[#1a1a1a] @error('institution_name') border-red-500 @enderror">
                        @error('institution_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Institution Address -->
                    <div>
                        <label for="institution_address" class="block text-sm font-semibold text-[#333333] mb-2">
                            Alamat Institusi
                        </label>
                        <textarea name="institution_address"
                                  id="institution_address"
                                  rows="3"
                                  placeholder="Jl. Contoh No. 123, Kota, Provinsi"
                                  class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-2 focus:ring-[#4b5563] focus:border-[#4b5563] transition text-[#1a1a1a] @error('institution_address') border-red-500 @enderror">{{ old('institution_address', \App\Models\SystemSetting::get('institution_address', 'Jl. Contoh No. 123, Jakarta')) }}</textarea>
                        @error('institution_address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6 bg-white rounded-xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 text-[#555555]">
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
