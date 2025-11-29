@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-dosen.page-header title="Detail KHS Mahasiswa Bimbingan" />

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-4 justify-between">
        <a href="{{ route('dosen.khs.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-print mr-2"></i>
            Print KHS
        </button>
    </div>

    <!-- Same content as mahasiswa KHS show -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-8">
            <div class="text-center text-white">
                <h1 class="text-3xl font-bold mb-2">KARTU HASIL STUDI (KHS)</h1>
                <p class="text-emerald-100">STAI Al-Fatih Tangerang</p>
                <p class="text-emerald-100 text-sm">Semester {{ $khs->semester ? $khs->semester->tahun_akademik . ' - ' . ucfirst($khs->semester->jenis) : '-' }}</p>
            </div>
        </div>

        <div class="p-6">
            @include('admin.khs.show')
        </div>
    </div>
</div>
@endsection
