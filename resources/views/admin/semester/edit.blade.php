@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Edit Semester" />

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.semester.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Warning Alert -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mr-3 mt-1"></i>
            <div>
                <p class="font-semibold text-yellow-800">Perhatian!</p>
                <p class="text-sm text-yellow-700">Hanya satu semester yang dapat aktif pada satu waktu. Mengaktifkan semester ini akan menonaktifkan semester yang sedang aktif.</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-calendar-alt mr-2"></i>
                Edit Form Semester
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.semester.update', $semester->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Semester -->
                    <div>
                        <label for="nama_semester" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Semester <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_semester" id="nama_semester" value="{{ old('nama_semester', $semester->nama_semester) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('nama_semester') border-red-500 @enderror" placeholder="Semester Ganjil 2024/2025" required>
                        @error('nama_semester')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Akademik -->
                    <div>
                        <label for="tahun_akademik" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Akademik <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tahun_akademik" id="tahun_akademik" value="{{ old('tahun_akademik', $semester->tahun_akademik) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('tahun_akademik') border-red-500 @enderror" placeholder="2024/2025" required>
                        @error('tahun_akademik')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Format: 2024/2025</p>
                    </div>

                    <!-- Jenis Semester -->
                    <div>
                        <label for="jenis" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis" id="jenis" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('jenis') border-red-500 @enderror" required>
                            <option value="">Pilih Jenis</option>
                            <option value="ganjil" {{ old('jenis', $semester->jenis) == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="genap" {{ old('jenis', $semester->jenis) == 'genap' ? 'selected' : '' }}>Genap</option>
                            <option value="pendek" {{ old('jenis', $semester->jenis) == 'pendek' ? 'selected' : '' }}>Pendek</option>
                        </select>
                        @error('jenis')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $semester->tanggal_mulai?->format('Y-m-d')) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('tanggal_mulai') border-red-500 @enderror" required>
                            <i class="fas fa-calendar-alt absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                        </div>
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $semester->tanggal_selesai?->format('Y-m-d')) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('tanggal_selesai') border-red-500 @enderror" required>
                            <i class="fas fa-calendar-check absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                        </div>
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Harus lebih besar dari tanggal mulai</p>
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status
                        </label>
                        <div class="flex items-center space-x-4" x-data="{ isActive: {{ old('is_active', $semester->is_active ? '1' : '0') }} == 1 }">
                            <label class="flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="is_active" value="1" x-model="isActive" class="sr-only" {{ old('is_active', $semester->is_active) ? 'checked' : '' }}>
                                    <div class="w-14 h-8 rounded-full shadow-inner transition-all duration-300" :class="isActive ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gray-300 group-hover:bg-gray-400'"></div>
                                    <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full shadow-md transition-all duration-300 transform" :class="isActive ? 'translate-x-6' : 'translate-x-0'">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas text-xs transition-all duration-300" :class="isActive ? 'fa-check text-green-600' : 'fa-times text-gray-400'"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-semibold transition-colors" :class="isActive ? 'text-green-700' : 'text-gray-600'" x-text="isActive ? 'Aktif' : 'Tidak Aktif'"></span>
                                </div>
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-red-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Mengaktifkan semester ini akan menonaktifkan semester lain yang sedang aktif
                        </p>
                    </div>
                </div>

                <div class="islamic-divider my-6"></div>

                <!-- KHS Settings Section -->
                <div x-data="{ khsAutoGenerate: {{ old('khs_auto_generate', $semester->khs_auto_generate ?? false ? '1' : '0') }} == 1 }">
                    <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Pengaturan KHS (Kartu Hasil Studi)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- KHS Generate Date -->
                        <div>
                            <label for="khs_generate_date" class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Generate KHS
                            </label>
                            <div class="relative">
                                <input type="date" name="khs_generate_date" id="khs_generate_date" value="{{ old('khs_generate_date', $semester->khs_generate_date?->format('Y-m-d')) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition">
                                <i class="fas fa-calendar-alt absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Tanggal otomatis generate KHS jika auto-generate diaktifkan
                            </p>
                        </div>

                        <!-- KHS Status -->
                        <div>
                            <label for="khs_status" class="block text-sm font-semibold text-gray-700 mb-2">
                                Status KHS
                            </label>
                            <select name="khs_status" id="khs_status" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition">
                                <option value="draft" {{ old('khs_status', $semester->khs_status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="generated" {{ old('khs_status', $semester->khs_status) == 'generated' ? 'selected' : '' }}>Generated</option>
                                <option value="approved" {{ old('khs_status', $semester->khs_status) == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="published" {{ old('khs_status', $semester->khs_status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">
                                Status workflow KHS untuk semester ini
                            </p>
                        </div>

                        <!-- Auto Generate Toggle -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Auto Generate KHS
                            </label>
                            <label class="flex items-center cursor-pointer group">
                                <div class="relative">
                                    <input type="checkbox" name="khs_auto_generate" value="1" x-model="khsAutoGenerate" class="sr-only" {{ old('khs_auto_generate', $semester->khs_auto_generate ?? false) ? 'checked' : '' }}>
                                    <div class="w-14 h-8 rounded-full shadow-inner transition-all duration-300" :class="khsAutoGenerate ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gray-300 group-hover:bg-gray-400'"></div>
                                    <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full shadow-md transition-all duration-300 transform" :class="khsAutoGenerate ? 'translate-x-6' : 'translate-x-0'">
                                        <div class="w-full h-full flex items-center justify-center">
                                            <i class="fas text-xs transition-all duration-300" :class="khsAutoGenerate ? 'fa-check text-green-600' : 'fa-times text-gray-400'"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-semibold transition-colors" :class="khsAutoGenerate ? 'text-green-700' : 'text-gray-600'" x-text="khsAutoGenerate ? 'Aktif' : 'Tidak Aktif'"></span>
                                    <p class="text-xs text-gray-500">Generate otomatis saat tanggal generate tiba</p>
                                </div>
                            </label>
                        </div>

                        <!-- Ketua Prodi Signature -->
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="khs_show_ketua_prodi_signature" value="1" class="w-4 h-4 text-[#2D5F3F] border-gray-300 rounded focus:ring-[#D4AF37]" {{ old('khs_show_ketua_prodi_signature', $semester->khs_show_ketua_prodi_signature ?? true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-signature mr-1"></i>
                                    Tampilkan Tanda Tangan Ketua Prodi
                                </span>
                            </label>
                        </div>

                        <!-- Dosen PA Signature -->
                        <div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="khs_show_dosen_pa_signature" value="1" class="w-4 h-4 text-[#2D5F3F] border-gray-300 rounded focus:ring-[#D4AF37]" {{ old('khs_show_dosen_pa_signature', $semester->khs_show_dosen_pa_signature ?? true) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">
                                    <i class="fas fa-signature mr-1"></i>
                                    Tampilkan Tanda Tangan Dosen PA
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="islamic-divider my-6"></div>

                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.semester.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" id="submitBtn" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg hover:from-[#4A7C59] hover:to-[#D4AF37] transition">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isActiveCheckbox = document.querySelector('input[name="is_active"]');
    const wasActive = {{ $semester->is_active ? 'true' : 'false' }};
    
    // Alert muncul saat checkbox DIKLIK/DIUBAH (bukan saat submit)
    isActiveCheckbox.addEventListener('change', function(e) {
        // Hanya tampilkan alert jika checkbox dicentang (mengaktifkan)
        if (this.checked) {
            let message = "⚠️ PERHATIAN PENTING!\n\n";
            
            if (!wasActive) {
                // Mengaktifkan semester yang sebelumnya tidak aktif
                message += "Mengaktifkan semester ini akan:\n\n" +
                    "✓ Menonaktifkan semester yang sedang aktif\n" +
                    "✓ Mengirim tagihan pembayaran SPP ke SELURUH mahasiswa aktif\n" +
                    "✓ Mahasiswa TIDAK BISA mengakses KRS dan KHS sebelum lunas SPP\n\n";
            } else {
                // Semester sudah aktif, di-uncheck lalu di-check lagi
                message += "Anda tetap mengaktifkan semester ini!\n\n" +
                    "PERHATIAN:\n\n" +
                    "✓ Perubahan pada semester aktif dapat mempengaruhi akses mahasiswa\n" +
                    "✓ Mahasiswa yang belum lunas SPP TIDAK BISA mengakses KRS dan KHS\n" +
                    "✓ Pastikan semua perubahan sudah benar sebelum menyimpan\n\n";
            }
            
            message += "Apakah Anda yakin ingin melanjutkan?";
            
            const confirmed = confirm(message);
            
            if (!confirmed) {
                // User membatalkan, uncheck checkbox
                this.checked = false;
            }
        }
    });
});
</script>
@endpush

@endsection
