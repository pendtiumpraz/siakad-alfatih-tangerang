@extends('layouts.mahasiswa')

@section('title', 'Akses KRS Ditolak')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-8">
            <svg class="w-24 h-24 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Akses KRS Ditolak</h1>
            <p class="text-gray-600">Anda belum bisa mengakses Kartu Rencana Studi (KRS)</p>
        </div>

        <!-- Warning Card -->
        <div class="bg-white rounded-lg shadow-lg p-8 border-l-4 border-red-500">
            <!-- Reason -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Alasan:</h3>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-800 font-medium">{{ $reason }}</p>
                </div>
            </div>

            <!-- Info -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Informasi:</h3>
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    @if($semester)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Semester Aktif:</span>
                        <span class="font-semibold text-gray-800">{{ $semester->tahun_akademik }} - {{ ucfirst($semester->jenis) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-gray-600">NIM:</span>
                        <span class="font-semibold text-gray-800">{{ $mahasiswa->nim }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-semibold text-gray-800">{{ $mahasiswa->nama_lengkap }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Program Studi:</span>
                        <span class="font-semibold text-gray-800">{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Langkah Selanjutnya:</h3>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-blue-800 text-sm mb-2">
                        <strong>1.</strong> Silakan lunasi pembayaran SPP terlebih dahulu
                    </p>
                    <p class="text-blue-800 text-sm mb-2">
                        <strong>2.</strong> Setelah pembayaran lunas dan terverifikasi
                    </p>
                    <p class="text-blue-800 text-sm">
                        <strong>3.</strong> Anda dapat kembali ke halaman ini untuk mengisi KRS
                    </p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('mahasiswa.pembayaran.index') }}" 
                   class="flex-1 bg-[#4A7C59] text-white px-6 py-3 rounded-lg hover:bg-[#3d6849] transition text-center font-semibold">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Lihat Pembayaran
                </a>
                <a href="{{ route('mahasiswa.dashboard') }}" 
                   class="flex-1 bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition text-center font-semibold">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <!-- Help Info -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Jika ada pertanyaan, silakan hubungi bagian akademik atau operator.
            </p>
        </div>
    </div>
</div>
@endsection
