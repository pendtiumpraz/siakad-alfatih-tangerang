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
            <div class="card-islamic p-6" x-data="{ file: null, preview: null }">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                    <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <span>Upload Bukti Pembayaran</span>
                </h3>

                <form action="{{ route('mahasiswa.pembayaran.upload', 1) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block w-full cursor-pointer">
                            <div class="border-2 border-dashed border-[#4A7C59] rounded-lg p-8 text-center hover:bg-gray-50 transition"
                                 @dragover.prevent
                                 @drop.prevent="file = $event.dataTransfer.files[0]; preview = URL.createObjectURL(file)">
                                <div x-show="!preview">
                                    <svg class="w-16 h-16 text-[#4A7C59] mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-gray-700 font-semibold mb-2">Klik untuk upload atau drag and drop</p>
                                    <p class="text-sm text-gray-500">PNG, JPG, PDF (Max. 2MB)</p>
                                </div>
                                <div x-show="preview" class="space-y-4">
                                    <img :src="preview" class="max-w-md mx-auto rounded-lg shadow" alt="Preview">
                                    <p class="text-sm text-gray-600" x-text="file ? file.name : ''"></p>
                                    <button type="button" @click="file = null; preview = null" class="text-red-600 hover:text-red-800 text-sm">
                                        Hapus dan pilih ulang
                                    </button>
                                </div>
                            </div>
                            <input
                                type="file"
                                name="bukti"
                                accept="image/jpeg,image/png,application/pdf"
                                class="hidden"
                                @change="file = $event.target.files[0]; preview = URL.createObjectURL(file)"
                                required
                            >
                        </label>
                        @error('bukti')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea
                            name="note"
                            rows="3"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition"
                            placeholder="Tambahkan catatan jika diperlukan..."
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-[#4A7C59] hover:bg-[#3d6849] text-white py-4 rounded-lg font-bold transition transform hover:scale-105 flex items-center justify-center space-x-2"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Submit Bukti Pembayaran</span>
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
                    <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                        <p class="text-xs text-gray-600 mb-2">Bank BNI</p>
                        <p class="text-lg font-bold text-gray-800 mb-1">1234567890</p>
                        <p class="text-xs text-gray-600">a.n. STAI AL-FATIH</p>
                    </div>
                    <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                        <p class="text-xs text-gray-600 mb-2">Bank Mandiri</p>
                        <p class="text-lg font-bold text-gray-800 mb-1">0987654321</p>
                        <p class="text-xs text-gray-600">a.n. STAI AL-FATIH</p>
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
