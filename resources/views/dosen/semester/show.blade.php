@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Detail Semester</h1>
            <p class="text-gray-600 mt-1">Informasi lengkap semester {{ $semester->tahun_akademik }}</p>
        </div>
        <a href="{{ route('dosen.semester.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Main Info Card -->
    <x-islamic-card>
        <div class="bg-gradient-to-r from-green-600 to-green-700 -m-6 mb-6 px-6 py-6 rounded-t-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center mr-4">
                        <svg class="w-8 h-8 text-green-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $semester->nama_semester ?? ($semester->tahun_akademik . ' ' . $semester->periode) }}</h2>
                        <p class="text-green-100">Tahun Akademik {{ $semester->tahun_akademik }}</p>
                    </div>
                </div>
                <div>
                    @if($semester->is_active)
                        <span class="px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-full">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Aktif
                        </span>
                    @else
                        <span class="px-4 py-2 bg-gray-500 text-white text-sm font-semibold rounded-full">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Tidak Aktif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Detail Information -->
            <div class="md:col-span-2 space-y-4">
                <h3 class="text-lg font-semibold text-green-800 border-b-2 border-yellow-400 pb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informasi Semester
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama Semester</p>
                        <p class="text-lg font-semibold text-green-700">{{ $semester->nama_semester ?? ($semester->tahun_akademik . ' ' . $semester->periode) }}</p>
                    </div>
                    @if(isset($semester->jenis))
                    <div>
                        <p class="text-sm text-gray-500">Jenis Semester</p>
                        <p class="text-lg font-semibold {{ $semester->jenis == 'ganjil' ? 'text-blue-600' : ($semester->jenis == 'genap' ? 'text-purple-600' : 'text-orange-600') }}">
                            {{ ucfirst($semester->jenis) }}
                        </p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Tahun Akademik</p>
                        <p class="text-lg font-semibold text-green-700">{{ $semester->tahun_akademik }}</p>
                    </div>
                    @if(isset($semester->tanggal_mulai))
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Mulai</p>
                        <p class="text-lg font-semibold text-gray-700">
                            <svg class="w-4 h-4 inline text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($semester->tanggal_mulai)->format('d F Y') }}
                        </p>
                    </div>
                    @endif
                    @if(isset($semester->tanggal_selesai))
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Selesai</p>
                        <p class="text-lg font-semibold text-gray-700">
                            <svg class="w-4 h-4 inline text-yellow-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($semester->tanggal_selesai)->format('d F Y') }}
                        </p>
                    </div>
                    @endif
                    @if(isset($semester->tanggal_mulai) && isset($semester->tanggal_selesai))
                    <div>
                        <p class="text-sm text-gray-500">Durasi</p>
                        <p class="text-lg font-semibold text-indigo-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ \Carbon\Carbon::parse($semester->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($semester->tanggal_selesai)) }} Hari
                        </p>
                    </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <p class="text-lg font-semibold {{ $semester->is_active ? 'text-green-600' : 'text-gray-600' }}">
                            {{ $semester->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-green-800 border-b-2 border-yellow-400 pb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Statistik
                </h3>

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-blue-600">Total Jadwal</p>
                            <p class="text-3xl font-bold text-blue-700">{{ $semester->jadwal_count ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-green-600">Mahasiswa Aktif</p>
                            <p class="text-3xl font-bold text-green-700">{{ $semester->mahasiswa_aktif_count ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-yellow-600">Total Kelas</p>
                            <p class="text-3xl font-bold text-yellow-700">{{ $semester->kelas_count ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-islamic-card>

    <!-- Timeline View -->
    @if(isset($semester->tanggal_mulai) && isset($semester->tanggal_selesai))
    <x-islamic-card title="Timeline Semester">
        <div class="relative">
            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-yellow-400"></div>

            <div class="mb-8 flex items-start">
                <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-4 relative z-10">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-1 bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
                    <p class="font-semibold text-green-800">Mulai Semester</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($semester->tanggal_mulai)->format('l, d F Y') }}</p>
                </div>
            </div>

            <div class="mb-8 flex items-start">
                <div class="w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center text-green-800 font-bold mr-4 relative z-10">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-1 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-4">
                    <p class="font-semibold text-yellow-800">Hari Ini</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</p>
                    @php
                        $today = \Carbon\Carbon::now();
                        $start = \Carbon\Carbon::parse($semester->tanggal_mulai);
                        $end = \Carbon\Carbon::parse($semester->tanggal_selesai);
                        $total = $start->diffInDays($end);
                        $elapsed = $start->diffInDays($today);
                        $percentage = $total > 0 ? min(100, ($elapsed / $total) * 100) : 0;
                    @endphp
                    <div class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-600 mt-1">{{ number_format($percentage, 1) }}% dari semester telah berlalu</p>
                    </div>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center text-white font-bold mr-4 relative z-10">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8 7a1 1 0 00-1 1v4a1 1 0 001 1h4a1 1 0 001-1V8a1 1 0 00-1-1H8z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="flex-1 bg-red-50 border-l-4 border-red-500 rounded-lg p-4">
                    <p class="font-semibold text-red-800">Selesai Semester</p>
                    <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($semester->tanggal_selesai)->format('l, d F Y') }}</p>
                </div>
            </div>
        </div>
    </x-islamic-card>
    @endif

    <!-- Active Classes -->
    @if(isset($activeClasses) && count($activeClasses) > 0)
    <x-islamic-card title="Kelas Aktif di Semester Ini">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($activeClasses as $class)
            <div class="border-2 border-green-200 rounded-lg p-4 hover:shadow-lg transition-shadow bg-gradient-to-br from-white to-green-50">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="font-semibold text-green-800">{{ $class->mataKuliah->nama_mk ?? '-' }}</p>
                        <p class="text-sm text-gray-600">Kelas {{ $class->kelas ?? $class->nama }}</p>
                        <p class="text-sm text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $class->dosen->nama ?? '-' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            {{ $class->students_count ?? 0 }} Mahasiswa
                        </p>
                    </div>
                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                        {{ $class->mataKuliah->sks ?? 0 }} SKS
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </x-islamic-card>
    @endif
</div>
@endsection
