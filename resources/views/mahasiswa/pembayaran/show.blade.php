@extends('layouts.mahasiswa')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Pembayaran</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap pembayaran</p>
        </div>
        <a href="{{ route('mahasiswa.pembayaran.index') }}"
           class="text-gray-600 hover:text-gray-800 px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2 border-2 border-gray-300 hover:border-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="islamic-divider"></div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Payment Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Status Card -->
            <div class="card-islamic p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-2xl font-bold text-gray-800">Pembayaran UTS Semester Genap</h3>
                    <span class="inline-block px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-bold">
                        JATUH TEMPO
                    </span>
                </div>
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-2">Nominal Pembayaran</p>
                    <p class="text-5xl font-bold text-red-600">Rp 1.500.000</p>
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 mb-1">Jatuh Tempo</p>
                        <p class="font-semibold text-red-600">30 Oktober 2025</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 mb-1">Semester</p>
                        <p class="font-semibold text-gray-800">Semester 5</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 mb-1">Jenis Pembayaran</p>
                        <p class="font-semibold text-gray-800">UTS</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 mb-1">Tahun Akademik</p>
                        <p class="font-semibold text-gray-800">2024/2025</p>
                    </div>
                </div>
            </div>

            <!-- Upload Bukti Payment -->
            <div class="card-islamic p-6 border-l-4 border-[#D4AF37]" x-data="{ file: null, preview: null, fileName: '' }">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-2 flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-[#4A7C59] to-[#2D5F3F] rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <span>Upload Bukti Pembayaran</span>
                    </h3>
                    <p class="text-sm text-gray-600 ml-12">Upload bukti transfer pembayaran Anda untuk proses verifikasi</p>
                </div>

                <form action="{{ route('mahasiswa.pembayaran.upload', 1) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Upload Area -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>File Bukti Pembayaran</span>
                                <span class="text-red-500">*</span>
                            </span>
                        </label>

                        <div class="relative">
                            <!-- Upload Box -->
                            <label class="block cursor-pointer">
                                <div class="border-3 border-dashed rounded-xl transition-all duration-300"
                                     :class="preview ? 'border-green-400 bg-green-50' : 'border-[#4A7C59] hover:border-[#D4AF37] hover:bg-gradient-to-br hover:from-green-50 hover:to-yellow-50'"
                                     @dragover.prevent="$el.classList.add('ring-4', 'ring-[#D4AF37]', 'ring-opacity-50')"
                                     @dragleave.prevent="$el.classList.remove('ring-4', 'ring-[#D4AF37]', 'ring-opacity-50')"
                                     @drop.prevent="
                                         $el.classList.remove('ring-4', 'ring-[#D4AF37]', 'ring-opacity-50');
                                         const droppedFile = $event.dataTransfer.files[0];
                                         if (droppedFile && (droppedFile.type.startsWith('image/') || droppedFile.type === 'application/pdf')) {
                                             file = droppedFile;
                                             fileName = droppedFile.name;
                                             if (droppedFile.type.startsWith('image/')) {
                                                 preview = URL.createObjectURL(droppedFile);
                                             } else {
                                                 preview = 'pdf';
                                             }
                                         }
                                     ">

                                    <!-- Empty State -->
                                    <div x-show="!preview" class="p-10 text-center">
                                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-[#4A7C59] to-[#2D5F3F] rounded-full mb-4">
                                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                        </div>
                                        <p class="text-lg font-bold text-gray-800 mb-2">Pilih File atau Drag & Drop</p>
                                        <p class="text-sm text-gray-600 mb-1">Format yang didukung:</p>
                                        <div class="flex items-center justify-center space-x-2 mb-3">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">JPG</span>
                                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">PNG</span>
                                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">PDF</span>
                                        </div>
                                        <p class="text-xs text-gray-500">Maksimal ukuran file: <span class="font-semibold text-gray-700">2MB</span></p>
                                    </div>

                                    <!-- Preview State -->
                                    <div x-show="preview" class="p-6">
                                        <div class="flex items-center justify-between p-4 bg-white rounded-lg border-2 border-green-400 shadow-sm">
                                            <div class="flex items-center space-x-4">
                                                <!-- Icon or Image Preview -->
                                                <div class="flex-shrink-0">
                                                    <div x-show="preview !== 'pdf'" class="w-16 h-16 rounded-lg overflow-hidden border-2 border-gray-200">
                                                        <img :src="preview" class="w-full h-full object-cover" alt="Preview">
                                                    </div>
                                                    <div x-show="preview === 'pdf'" class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center">
                                                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <!-- File Info -->
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-semibold text-gray-800 truncate" x-text="fileName"></p>
                                                    <p class="text-xs text-green-600 font-medium">✓ File siap diupload</p>
                                                </div>
                                            </div>
                                            <!-- Remove Button -->
                                            <button type="button"
                                                    @click.stop="file = null; preview = null; fileName = ''; $refs.fileInput.value = ''"
                                                    class="flex-shrink-0 p-2 text-red-600 hover:bg-red-50 rounded-lg transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <input
                                    x-ref="fileInput"
                                    type="file"
                                    name="bukti"
                                    accept="image/jpeg,image/jpg,image/png,application/pdf"
                                    class="hidden"
                                    @change="
                                        const selectedFile = $event.target.files[0];
                                        if (selectedFile) {
                                            file = selectedFile;
                                            fileName = selectedFile.name;
                                            if (selectedFile.type.startsWith('image/')) {
                                                preview = URL.createObjectURL(selectedFile);
                                            } else {
                                                preview = 'pdf';
                                            }
                                        }
                                    "
                                    required
                                >
                            </label>
                        </div>

                        @error('bukti')
                        <div class="mt-2 flex items-center space-x-2 text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm">{{ $message }}</p>
                        </div>
                        @enderror
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <span>Catatan</span>
                                <span class="text-gray-400 text-xs">(Opsional)</span>
                            </span>
                        </label>
                        <textarea
                            name="note"
                            rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-4 focus:ring-[#4A7C59]/20 transition"
                            placeholder="Contoh: Pembayaran via transfer BNI tanggal 15 Oktober 2024..."
                        ></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-gradient-to-r from-[#4A7C59] to-[#2D5F3F] hover:from-[#3d6849] hover:to-[#234a31] text-white py-4 px-6 rounded-lg font-bold transition-all duration-300 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center space-x-3"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-lg">Submit Bukti Pembayaran</span>
                    </button>
                </form>
            </div>

            <!-- Payment Details -->
            <div class="card-islamic p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Rincian Pembayaran</span>
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Biaya UTS</span>
                        <span class="font-semibold text-gray-800">Rp 1.500.000</span>
                    </div>
                    <div class="flex justify-between py-2 border-b">
                        <span class="text-gray-600">Biaya Admin</span>
                        <span class="font-semibold text-gray-800">Rp 0</span>
                    </div>
                    <div class="flex justify-between py-3 bg-gray-50 px-4 rounded-lg">
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="font-bold text-[#4A7C59] text-xl">Rp 1.500.000</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Payment Info -->
            <div class="card-islamic p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Informasi Rekening</span>
                </h3>
                <div class="space-y-3">
                    @php
                        $bankName = \App\Models\SystemSetting::get('bank_name', 'BCA');
                        $bankAccountNumber = \App\Models\SystemSetting::get('bank_account_number', '1234567890');
                        $bankAccountName = \App\Models\SystemSetting::get('bank_account_name', 'STAI AL-FATIH');
                    @endphp
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-4 rounded-lg border-l-4 border-blue-500">
                        <p class="text-xs text-gray-600 mb-2">{{ $bankName }}</p>
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-bold text-gray-800 mb-1">{{ $bankAccountNumber }}</p>
                            <button onclick="copyToClipboard('{{ $bankAccountNumber }}')" class="text-blue-600 hover:text-blue-800 p-1" title="Salin nomor rekening">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-600">a.n. {{ $bankAccountName }}</p>
                    </div>
                </div>
            </div>

            <!-- Instructions -->
            <div class="card-islamic p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Langkah Pembayaran</span>
                </h3>
                <ol class="space-y-3 text-sm">
                    <li class="flex items-start space-x-3">
                        <div class="bg-[#4A7C59] text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 font-bold text-xs">1</div>
                        <span class="text-gray-700">Transfer ke salah satu rekening yang tersedia</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="bg-[#4A7C59] text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 font-bold text-xs">2</div>
                        <span class="text-gray-700">Simpan bukti transfer (screenshot atau foto)</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="bg-[#4A7C59] text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 font-bold text-xs">3</div>
                        <span class="text-gray-700">Upload bukti transfer melalui form di atas</span>
                    </li>
                    <li class="flex items-start space-x-3">
                        <div class="bg-[#4A7C59] text-white rounded-full w-6 h-6 flex items-center justify-center flex-shrink-0 font-bold text-xs">4</div>
                        <span class="text-gray-700">Tunggu verifikasi dari admin (maks. 2x24 jam)</span>
                    </li>
                </ol>
            </div>

            <!-- Contact Support -->
            <div class="card-islamic p-6 bg-blue-50">
                <h3 class="text-lg font-bold text-gray-800 mb-3">Butuh Bantuan?</h3>
                <p class="text-sm text-gray-600 mb-4">Hubungi bagian keuangan jika ada pertanyaan:</p>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <span class="text-gray-700">+62 21-1234-5678</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-700">keuangan@staialfatih.ac.id</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success toast
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2';
        toast.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            <span>Nomor rekening berhasil disalin!</span>
        `;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endpush
