@extends('layouts.mahasiswa')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-mahasiswa.page-header title="Kartu Hasil Studi (KHS)" />

    <!-- Info Banner -->
    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-400 text-xl mr-3 mt-1"></i>
            <div>
                <p class="font-semibold text-blue-800">Tentang KHS</p>
                <p class="text-sm text-blue-700">KHS menampilkan prestasi akademik Anda setiap semester. IP adalah nilai semester berjalan, sedangkan IPK adalah nilai kumulatif keseluruhan.</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    @if($khsList->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md border-l-4 border-green-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">IPK Saat Ini</p>
                    @php
                        $latestKhs = $khsList->first();
                        $ipkColor = $latestKhs->ipk >= 3.5 ? 'text-green-600' : ($latestKhs->ipk >= 3.0 ? 'text-blue-600' : ($latestKhs->ipk >= 2.5 ? 'text-yellow-600' : 'text-red-600'));
                    @endphp
                    <p class="text-4xl font-bold {{ $ipkColor }}">{{ number_format($latestKhs->ipk, 2) }}</p>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-trophy text-green-600 text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Indeks Prestasi Kumulatif</p>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-blue-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Total SKS</p>
                    <p class="text-4xl font-bold text-blue-600">{{ $latestKhs->total_sks_kumulatif }}</p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-book text-blue-600 text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">SKS yang telah ditempuh</p>
        </div>

        <div class="bg-white rounded-lg shadow-md border-l-4 border-purple-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-semibold">Total Semester</p>
                    <p class="text-4xl font-bold text-purple-600">{{ $khsList->count() }}</p>
                </div>
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-purple-600 text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Semester yang telah diselesaikan</p>
        </div>
    </div>
    @endif

    <!-- KHS List -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-file-alt mr-2"></i>
                Daftar KHS Per Semester
            </h2>
        </div>

        <div class="p-6">
            @if($khsList->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($khsList as $khs)
                        <div class="border-2 border-[#2D5F3F] rounded-lg p-6 hover:shadow-lg transition">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-[#2D5F3F]">
                                    <i class="fas fa-calendar-check mr-2"></i>
                                    {{ $khs->semester->tahun_akademik }}
                                </h3>
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                    {{ ucfirst($khs->semester->jenis) }}
                                </span>
                            </div>

                            <div class="space-y-3">
                                <!-- IP -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Indeks Prestasi (IP)</span>
                                    @php
                                        $ipColor = $khs->ip >= 3.5 ? 'text-green-600' : ($khs->ip >= 3.0 ? 'text-blue-600' : ($khs->ip >= 2.5 ? 'text-yellow-600' : 'text-red-600'));
                                    @endphp
                                    <span class="text-2xl font-bold {{ $ipColor }}">{{ number_format($khs->ip, 2) }}</span>
                                </div>

                                <!-- IPK -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">IPK</span>
                                    @php
                                        $ipkColor = $khs->ipk >= 3.5 ? 'text-green-600' : ($khs->ipk >= 3.0 ? 'text-blue-600' : ($khs->ipk >= 2.5 ? 'text-yellow-600' : 'text-red-600'));
                                    @endphp
                                    <span class="text-2xl font-bold {{ $ipkColor }}">{{ number_format($khs->ipk, 2) }}</span>
                                </div>

                                <!-- SKS -->
                                <div class="flex justify-between items-center border-t pt-3">
                                    <span class="text-sm text-gray-600">SKS Semester</span>
                                    <span class="text-lg font-semibold text-gray-700">{{ $khs->total_sks_semester }} SKS</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">SKS Kumulatif</span>
                                    <span class="text-lg font-semibold text-gray-700">{{ $khs->total_sks_kumulatif }} SKS</span>
                                </div>

                                <!-- Predikat -->
                                <div class="border-t pt-3">
                                    @if($khs->ipk >= 3.75)
                                        <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">
                                            <i class="fas fa-star mr-1"></i>
                                            Dengan Pujian (Cum Laude)
                                        </span>
                                    @elseif($khs->ipk >= 3.50)
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                            <i class="fas fa-medal mr-1"></i>
                                            Sangat Memuaskan
                                        </span>
                                    @elseif($khs->ipk >= 3.00)
                                        <span class="inline-flex items-center px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">
                                            <i class="fas fa-check mr-1"></i>
                                            Memuaskan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Cukup
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('mahasiswa.khs.show', $khs->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
                                    <i class="fas fa-eye mr-2"></i>
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg font-semibold">Belum Ada Data KHS</p>
                    <p class="text-gray-400 text-sm mt-2">KHS akan tersedia setelah dosen menginput nilai dan admin melakukan generate KHS</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Predikat Legend -->
    @if($khsList->count() > 0)
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] p-6">
        <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4">
            <i class="fas fa-info-circle mr-2"></i>
            Keterangan Predikat Kelulusan
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded-full">Cum Laude</span>
                <span class="text-sm text-gray-600">IPK ≥ 3.75</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">Sangat Memuaskan</span>
                <span class="text-sm text-gray-600">IPK 3.50 - 3.74</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-sm font-semibold rounded-full">Memuaskan</span>
                <span class="text-sm text-gray-600">IPK 3.00 - 3.49</span>
            </div>
            <div class="flex items-center space-x-3">
                <span class="px-3 py-1 bg-gray-100 text-gray-800 text-sm font-semibold rounded-full">Cukup</span>
                <span class="text-sm text-gray-600">IPK < 3.00</span>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
